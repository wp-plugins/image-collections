<?php
/*
 * TODO:
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
include_once (ABCFIC_PLUGIN_DIR . 'includes/class-image.php');
include_once (ABCFIC_PLUGIN_DIR . 'includes/class-collection.php');

if ( !class_exists('ABCFIC_Upload') ) {

class ABCFIC_Upload{

    var $collID = 0;

    function __construct( $collID ) {
        $this->collID = $collID ;
    }

/**
 * Copy uploaded image from temp folder to the collection folder.
 * Called: by multifile uploader after each image has been uploaded to temp folder
 * In: $collID
 * Out: string $result 0 = OK or error msg
 * Called: abcf-async-upload.php
 */
function save_uploaded_image() {

    if ($this->collID == 0){ return 'No gallery selected !'; }

    //$_FILES = an associative array of items uploaded to the current script via the HTTP POST method
    //is_uploaded_file() — Tells whether the file was uploaded via HTTP POST
    if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0){
        return 'Invalid upload. Error Code : ' . $_FILES['Filedata']['error'];
    }

    // get the filename and extension
    $tempQFilename = $_FILES['Filedata']['tmp_name'];

    //Sanitize filename
    //$fileData = abcfic_clean_img_filename( $_FILES['Filedata']['name'] );

    $fileData = clean_img_filename( $_FILES['Filedata']['name'] );
    if(empty($fileData)) { return esc_html( $_FILES['Filedata']['name'] ) . ' is no valid image file.'; }

    $cleanFilename = $fileData['basename'];

    $objColl = new ABCFIC_Collection();
    $objColl->get_upload_options($this->collID);
    $collQPath = $objColl->collQPath;
    $uniqueFilename = $objColl->unique_filename;

    //Delete file if collection folder doesn't exists
    if ( empty($collQPath) ){
        @unlink($tempQFilename);
        return 'Theare is no collection path set in the database!';
    }

    //===Build filename and Q path=================================
    $newFilename = $this->get_unique_filename( $collQPath, $cleanFilename, $tempQFilename, $uniqueFilename );
    if (empty($newFilename)){ return 'File already exists!'; }
    $newQFileName = trailingslashit($collQPath) . $newFilename;

    //==Save file===================================
    if ( !@move_uploaded_file($tempQFilename, $newQFileName) ){ return 'Error, the file could not be moved to : ' . esc_html( $newQFileName ); }
    if ( !$this->chmod($newQFileName) ){ return 'Error, the file permissions could not be set';}

    return '0';
}

/**
 * Add files to DB, create thumbs, display status message
 * Runs after function swfupload_image finished uploading images to the collection folder
 */
function process_saved_images() {

    $collID = (int)$this->collID;

    //$collID = (int)$_POST['collid'];
    if( !is_int($collID) ){ echo abcfic_msgs_error(59); return ; }
    if( !$collID > 0 ){ echo abcfic_msgs_error(59); return ; }

    $objColl = new ABCFIC_Collection();
    $objColl->get_upload_options($collID);
    $collQPath = $objColl->collQPath;

    $imgPublish = $objColl->img_publish;
    if( empty($collQPath) ){ echo abcfic_msgs_error(55, 'empty'); return ; }
    if (!is_dir($collQPath )) { echo abcfic_msgs_error(55, 'dir'); return; }

    // get the list of all files from collection folder
    $imgsInFolder = $this->scan_folder($collQPath);
    if (empty($imgsInFolder)) { echo abcfic_msgs_error(60, esc_html($collQPath)); return; }

    //Create thumbnail folders if don't exist
    $objColl->add_thumb_folders( $collQPath );

    // Get image list from DB
    $imgsInDB = abcfic_db_image_filenames ( $collID );

    // Find not added to DB
    $imgsNotInDB = array_diff($imgsInFolder, $imgsInDB);

    //==Add to DB;===================================
    $addedImgIDs = $this->add_images_to_db( $collID, $collQPath, $imgPublish, $imgsNotInDB );

    //==Resize as needed, create thumbs==abcfic-resize-ajax.js=================================
    $this->do_ajax_operation( 'ajax_resize_img_create_thumbs' , $addedImgIDs, 'Crunching...' );

    //get_currentuserinfo(); use later for getting user ID
    abcfic_db_update_collection_dt($collID);
    return;
}

//====================================================================================
/**
 * Purpose:Initate the Ajax operation
 * In: $operation: name of the function to execute; ids: image IDs
 * Out: string the javascript output
 */
function do_ajax_operation( $operation, $arrayImgIDs, $title ) {

    if ( !is_array($arrayImgIDs) || empty($arrayImgIDs) ){ return; }
    $strImgs  = implode('","', $arrayImgIDs);

    //print_r($strImgs); die;


    // Initate the ajax operation
    ?>
    <script type="text/javascript">
            jsArray = new Array("<?php echo $strImgs; ?>");

            abcfic_pbar_vars = {
                    header: "<?php echo $title; ?>",
                    maxStep: jsArray.length
            };

            abcfic_resize_vars = {
                    operation: "<?php echo $operation; ?>",
                    ids: jsArray,
                    end_ok: '<?php echo esc_js( abcfic_msgs_ok() ); ?>'
            };


            abcfic_ajax_vars = {
                    operation: "<?php echo $operation; ?>",
                    ids: jsArray,
                    header: "<?php echo $title; ?>",
                    end_ok: '<?php echo esc_js( abcfic_msgs_ok() ); ?>',
                    maxStep: jsArray.length
            };

            jQuery(document).ready( function(){
                    abcficPBar.init( abcfic_pbar_vars );
                    abcficAxResize.init( abcfic_resize_vars );
            } );
    </script>
    <?php
}



//===PRIVATE===========================================================================
private function add_images_to_db( $collID, $collQPath, $imgPublish, $imgsNotInDB ) {

    $addedImgIDs = array();

    if ( !empty($imgsNotInDB) ){
        foreach($imgsNotInDB as $filename) {

            $imgWH = $this->get_image_size( trailingslashit($collQPath) . $filename );
            $imgID = abcfic_db_add_image($collID, $filename , $imgWH[0], $imgWH[1], $imgPublish );
            if ( $imgID > 0) { $addedImgIDs[] = $imgID; }
        }
    }

    // WP update space used in WP dashboard
    delete_transient( 'dirsize_cache' );
    do_action('abcfic_after_new_images_added', $collID, $addedImgIDs );

    return $addedImgIDs;
}

private function get_image_size( $imgQName ) {

    //Returns an array with 7 elements. Index 0 and 1 contains the width and the height of the image. On failure, FALSE is returned.
    $getSize = @getimagesize ( $imgQName );
    $imgWH = array('0', '0');
    if($getSize){
        $imgWH[0] = $getSize[0];
        $imgWH[1] = $getSize[1];
    }
    return $imgWH;
}

/**
 * Purpose: Get list of filenames
 * In: Full path
 * Out: Array of filenames (images only)
 */
private function scan_folder( $fileQName ) {
    $ext = apply_filters('abcfic_allowed_file_types', array('jpeg', 'jpg', 'png', 'gif') );

    $files = array();
    $handle = opendir( $fileQName );
    if( $handle ) {
        while( false !== ( $file = readdir( $handle ) ) ) {
            $info = pathinfo( $file );
            // just look for images with the correct extension
            if ( isset($info['extension']) )
            if ( in_array( strtolower($info['extension']), $ext) )
            $files[] = utf8_encode( $file );
        }
        closedir( $handle );
    }
    sort( $files );
    return ( $files );
}

/**
 * Set correct file permissions (taken from wp core). Should be called after writing any file.
 * In: Full path
 * Out: true/false
 */
private function chmod($fileQName = '') {

    $stat = @stat( dirname($fileQName) );
    $perms = $stat['mode'] & 0000666; // Remove execute bits for files
    if ( @chmod($fileQName, $perms) ){ return true; }

    return false;
}


/**
 * Check for duplicate file name. Delete or rename as needed.
 */
private function get_unique_filename( $collQPath, $cleanFilename, $tempQFilename, $uniqueFilename ) {

    $collQPath = trailingslashit($collQPath);
    $newQFilename = $collQPath . $cleanFilename;
    $fileExists = is_file( $newQFilename );

    //No dups. Delete temp file if there is already file with the same name
    if($uniqueFilename == 1){
        if( $fileExists ){
            @unlink( $tempQFilename );
            return '';
        }
    }
    else {
        if( $fileExists ){
            // separate the filename into a name and extension
            $info = pathinfo($cleanFilename);
            $ext = !empty($info['extension']) ? '.' . $info['extension'] : '';

            // Increment the file number until we have a unique filename.
            $number = '';
            while ( file_exists( $collQPath . $cleanFilename ) ) { $cleanFilename = str_replace( $number . $ext,  ++$number . $ext, $cleanFilename ); }
        }
    }
    //return $collQPath . $cleanFilename;
    return $cleanFilename;
}

/**
 * Purpose: Decode upload error to normal message.
 * In: error code
 * Out: error msg
 * Called: tab-uploader.php
 */
function decode_upload_error( $code ) {

        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $msg = __ ( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'abcfic-td' );
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $msg = __ ( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'abcfic-td' );
                break;
            case UPLOAD_ERR_PARTIAL:
                $msg = __ ( 'The uploaded file was only partially uploaded', 'abcfic-td' );
                break;
            case UPLOAD_ERR_NO_FILE:
                $msg = __ ( 'No file was uploaded', 'abcfic-td' );
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $msg = __ ( 'Missing a temporary folder', 'abcfic-td' );
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $msg = __ ( 'Failed to write file to disk', 'abcfic-td' );
                break;
            case UPLOAD_ERR_EXTENSION:
                $msg = __ ( 'File upload stopped by extension', 'abcfic-td' );
                break;
            default:
                $msg = __ ( 'Unknown upload error', 'abcfic-td' );
                break;
        }

        return $msg;
}

}

    /**
    * Slightly modfifed version of pathinfo(), clean up filename & rename jpeg to jpg
    *
    * @param string $name The name being checked.
    * @return array containing information about file
    */
    function fileinfo( $name ) {

            //Sanitizes a filename replacing whitespace with dashes
            $name = sanitize_file_name($name);

            //get the parts of the name
            $filepart = pathinfo ( strtolower($name) );

            if ( empty($filepart) )
                    return false;

            // required until PHP 5.2.0
            if ( empty($filepart['filename']) )
                    $filepart['filename'] = substr($filepart['basename'],0 ,strlen($filepart['basename']) - (strlen($filepart['extension']) + 1) );

            $filepart['filename'] = sanitize_title_with_dashes( $filepart['filename'] );

            //extension jpeg will not be recognized by the slideshow, so we rename it
            $filepart['extension'] = ($filepart['extension'] == 'jpeg') ? 'jpg' : $filepart['extension'];

            //combine the new file name
            $filepart['basename'] = $filepart['filename'] . '.' . $filepart['extension'];

            return $filepart;
    }


    function clean_img_filename( $fileBasename ) {

            //Sanitizes a filename replacing whitespace with dashes
            $fileBasename = sanitize_file_name($fileBasename);

            //Convert to lowercase. Get filename and extension
            $filepart = pathinfo ( strtolower($fileBasename) );

            if ( empty($filepart) ) { return ''; }
            if ( empty($filepart['filename']) )  { return ''; }
            if ( empty($filepart['extension']) )  { return ''; }

            $filepart['extension'] = ($filepart['extension'] == 'jpeg') ? 'jpg' : $filepart['extension'];

            $ext = apply_filters('abcfic_allowed_file_types', abcfic_inputbldr_ok_file_types() );
            if (!in_array( $filepart['extension'], $ext)) { return ''; }

            //Clean file name and extension
            $filepart['basename'] = $filepart['filename'] . '.' . $filepart['extension'];

            return $filepart;
}
}