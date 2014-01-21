<?php
/*
 *TODO:
 */
function abcfic_tab_uploader($collID) {

    abcfic_cntbldr_permission_check();

    $url = admin_url() . 'admin.php?page=' . $_GET['page'];
    $url .= '&amp;mode=tabscoll&amp;collid=' . $collID .'&amp;tab=uploader';
    $objColl = new ABCFIC_Collection();
    $objColl->get_collection_options($collID);

    if($objColl->coll_id == 0) {
        echo abcfic_msgs_error(55);
        return;
    }

    //Page URL to where the files will be uploaded to.
    $urlUploadPg = admin_url('/?abcficupload');

    // Set the post params, which plupload will post back with the file, and pass them through a filter.
    $post_params = array(
                    "auth_cookie" => (is_ssl() ? $_COOKIE[SECURE_AUTH_COOKIE] : $_COOKIE[AUTH_COOKIE]),
                    "logged_in_cookie" => $_COOKIE[LOGGED_IN_COOKIE],
                    "_wpnonce" => wp_create_nonce('abcfic_upload_nonce'),
                    "collid" => $collID,
    );
    $p = array();

    foreach ( $post_params as $param => $val ) {
            $val = esc_js( $val );
            $p[] = "'$param' : '$val'";
    }

    $post_params_str = implode( ',', $p ). "\n";
    $resizeOnClient = 0;
    $maxUploadSize = wp_max_upload_size() . 'b';
 ?>
<script type="text/javascript">
//<![CDATA[
var max_img_w = <?php echo (int) $objColl->max_img_w; ?>;
var max_img_h = <?php echo (int) $objColl->max_img_h; ?>;
var img_resize = <?php echo (int) $resizeOnClient; ?>;
var img_quality = <?php echo (int) $objColl->img_quality; ?>;
var coll_id = <?php echo (int) $collID ?>;

jQuery(document).ready(function($) {
    window.uploader = new plupload.Uploader({
        runtimes: '<?php echo apply_filters('plupload_runtimes', 'html5,flash,silverlight,html4'); ?>',
        browse_button: 'plupload-browse-button',
        container: 'plupload-upload-ui',
        drop_element: 'uploadimage',
        file_data_name: 'Filedata',
        max_file_size: '<?php echo $maxUploadSize; ?>',
        url: '<?php echo esc_js( $urlUploadPg ); ?>',
        flash_swf_url: '<?php echo esc_js( includes_url('js/plupload/plupload.flash.swf') ); ?>',
        silverlight_xap_url: '<?php echo esc_js( includes_url('js/plupload/plupload.silverlight.xap') ); ?>',
        filters: [ {title: 'Image Files', extensions: '<?php echo esc_js( abcfic_inputbldr_ok_file_types_list() ); ?>'} ],
        multipart: true,
        urlstream_upload: true,
        multipart_params:  <?php echo '{' . $post_params_str  . '}'; ?>,
        debug: false,
        preinit : { Init: function() { initUploader(); } },
        i18n : {
                'remove' : 'Remove',
                'browse' : 'Browse...',
                'upload' : 'Upload Images'
        }
});

    uploader.bind('FilesAdded', function(up, files) {
        $.each(files, function(i, file) {
                fileQueued(file);
        });
        up.refresh();
    });

    uploader.bind('BeforeUpload', function() {
        uploadStart(coll_id);
    });

    uploader.bind('UploadProgress', function(up, file) {
        uploadProgress(file);
    });

    uploader.bind('Error', function(up, err) {
            uploadError(err.file, err.code, err.message);
            up.refresh();
    });

    uploader.bind('FileUploaded', function( up, file) {
            uploadSuccess(file);
    });

    uploader.bind('UploadComplete', function(up) { uploadComplete(); });

    // on load change the upload to plupload
    uploader.init();

    uploaderProgressBarOptions = {
            header: "Uploading...",
            maxStep: 100
    };
});
//]]>
</script>

<!-- upload images -->
<?php
echo abcfic_info_tbl($objColl);
echo abcfic_inputbldr_hdivider502(); ?>

<form name="frmUploadImg" id="frm_uploader" method="POST" enctype="multipart/form-data" action="<?php echo $url.'#uploadimage' ?>" accept-charset="utf-8" >
    <?php wp_nonce_field('abcfic_uploadimgs') ?>
    <table class="form-table" >
        <tr valign="top">
                <td>
                    <div id="abcficUploadErr" class="abcicErrUpload">
                    </div>
                    <div id="plupload-upload-ui">
                        <input id="plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" />
                    </div>
                    <div id='uploadQueue' class="abcficPadT10"></div>
                    <p></p>
                </td>
        </tr>
    </table>
    <div class="submit">
        <span id="abcficUploadBtnPlaceholder"></span>
    </div>
    <?php
    echo abcfic_inputbldr_input_hidden( 'collid', 'collid', $objColl->coll_id, true );
    ?>
</form>
<?php echo abcfic_inputbldr_hdivider502();
}

function abcfic_info_tbl($objColl){

    $resizeMsg = "No. Upload images as is.";
    if($objColl->img_resize == 1){$resizeMsg = "Yes. Resize images on upload.";}

    $fixedThumbs = "Fixed dimensions. Ignore the aspect ratio.";
    if($objColl->fixed_thumb_wh == 0){$fixedThumbs = "Crop thumbnails. Keep the aspect ratio.";}

    $uniqueFilename = "Yes. No duplicates.";
    if($objColl->unique_filename == 0){$uniqueFilename = "No. Allow duplicates.";}
?>
<div>
<div id="uploadStatusCntr"></div>
<div><h2>Collection: <?php echo $objColl->coll_name; ?></h2></div>
<table class="abcficUploadInfoCntr">
<tr>
<td>
    <table>
        <tr>
            <td class="abcficUploadOptionLbl">Unique File Names: </td>
            <td><?php echo $uniqueFilename ?></td>
        </tr>
    <tr>
        <td class="abcficUploadOptionLbl">Scale images: </td>
        <td><?php echo $resizeMsg ?></td>
    </tr>
  <?php if($objColl->img_resize == 1){?>
    <tr>
        <td class="abcficUploadOptionLbl">Images max WxH: </td>
        <td> <?php echo $objColl->max_img_w . "x" . $objColl->max_img_h; ?> pixels</td>
    </tr>
   <tr>
        <td class="abcficUploadOptionLbl">Image quality: </td>
        <td> <?php echo $objColl->img_quality; ?>%</td>
    </tr>
  <?php }?>
    <tr>
        <td class="abcficUploadOptionLbl">Maximum file size: </td>
        <td><?php  echo esc_html(abcfic_max_size()); ?></td>
    </tr>
    </table>
</td>
<td>
    <table>
        <tr>
            <td class="abcficUploadOptionLbl abcficPadL20">Thumbs: </td>
            <td><?php echo $fixedThumbs ?></td>
        </tr>
        <tr>
            <td class="abcficUploadOptionLbl abcficPadL20">Thumbs WxH: </td>
                <td> <?php echo $objColl->max_thumb_w . "x" . $objColl->max_thumb_h; ?> pixels</td>
            </tr>
        <tr>
            <td class="abcficUploadOptionLbl abcficPadL20">Thumbs quality: </td>
            <td> <?php echo $objColl->thumb_quality; ?>%</td>
        </tr>
    </table>
</td>
</tr>
<tr>
    <td colspan="2"> <?php echo abcfic_inputbldr_hlp_data( 6, $objColl->collQPath ); ?></td></tr>
<tr>
    <td colspan="2"> <?php echo abcfic_img_editor_support(); ?></td>
</tr>
</table>
<?php
}

function abcfic_img_editor_support(){

   $arg = array(
	'mime_type' => 'image/jpeg',
	'methods' => array('resize','save')
    );

   $out = '';
    $imgEditorTest = wp_image_editor_supports($arg);
    if ($imgEditorTest == false) {$out = 'Error: Your image do not support file resize.'; }
    return $out;
}

function abcfic_max_size(){

    $maxSize = wp_max_upload_size();
    $sizes = array( 'KB', 'MB', 'GB' );

    for ( $u = -1; $maxSize > 1024 && $u < count( $sizes ) - 1; $u++ ) { $maxSize /= 1024; }

    if ( $u < 0 ) {
            $maxSize = 0;
            $u = 0;
    }
    else {
            $maxSize = (int) $maxSize;
    }

    return $maxSize . $sizes[$u];
}

