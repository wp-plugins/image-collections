<?php
/*
 * TODO: Add parameters to construct.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists('ABCFIC_Collection') ) {
class ABCFIC_Collection {

        public $coll_id = 0;
        public $collID = 0;
        public $coll_name = '';
        public $colls_spath = '';
        public $colls_folder = '';
        public $coll_folder = '';

        public $max_img_w = 0;
        public $max_img_h = 0;
        public $img_resize = 0;

        public $img_quality = 0;
        public $unique_filename = 0;
        public $fixed_img_wh  = 0;
        public $img_publish  = 1;
        public $max_thumb_w = 0;
        public $max_thumb_h= 0;
        public $fixed_thumb_wh = 0;
        public $thumb_quality = 0;

        public $collsQPath  = '';
        public $collQPath = '';


    public function __construct() {
    }

    public function get_collection($collID){

        $dbRow = abcfic_db_collection($collID);
        if(empty($dbRow)) {
            $this->coll_id = 0;
            return;
        }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }
        $this->set_coll_qpath();
     }

    public function add_collection( $collName ) {

        $collName = trim($collName);
        //Collection folder name
        $folderName = $this->valid_folder_name($collName);

        if ( abcfic_lib_isblank($folderName) ) {
            abcfic_msgs_error(53);
            return;
        }

        $this->coll_folder = $folderName;

        $opts = abcfic_options_get_optns();
        $this->colls_spath = $opts['colls_spath'];
        $this->colls_folder = $opts['colls_folder'];
        $this->collsQPath = $opts['collsQPath'];
        $this->set_coll_qpath();

        //===Create folders==============================================
        // Create collections folder if doesn't exists
        if ( !$this->create_folder( $this->collsQPath )){ $this->coll_id = 0; return; }

        if ( !is_writeable( $this->collsQPath ) ) {
            abcfic_msgs_error(52, '<b> ' . $this->collsQPath . '</b>');
            return;
        }

        // Create collection folder
        if ( !$this->create_folder( $this->collQPath )){ $this->coll_id = 0; return; }

        if ( !is_writeable( $this->collQPath ) ) {
            abcfic_msgs_error(52, '<b> ' . $this->collQPath . '</b>');
            return;
        }

        //Create thumb folders
        if ( !$this->add_thumb_folders( $this->collQPath )){ return; }

        //Add collection to DB
        $this->coll_id = abcfic_db_add_collection($collName, $this->coll_folder);
        return;
    }

    public function update_collection( $optns ) {

        $optns =  wp_parse_args( $optns, $this->coll_default_options() );

        $dbRow = abcfic_db_coll_paths( intval($optns['coll_id']) );
        if(empty($dbRow)) {
            return;
        }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }

        $rowsAffected = abcfic_db_update_collection( $optns );
        if($rowsAffected != 1) {
            return;
        }

        //Success status
        $this->coll_id = intval($optns['coll_id']);

        $this->set_coll_qpath();
        $this->add_thumb_folders( $this->collQPath);
    }

    //Delete images, folders and databse records
    public function delete_collection($collID) {

        abcfic_cntbldr_permission_check();
        check_admin_referer('abcfic_delcollection');

        $dbRow = abcfic_db_coll_paths( $collID );
        if(empty($dbRow)) { return; }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }

        $this->set_coll_qpath();
        $collQPath = $this->collQPath;

        if(file_exists($collQPath)){
            $imgs = abcfic_db_collection_images( $collID );
            $imgFolders = unserialize( ABCFIC_FOLDERS );

            if (is_array($imgs)) {
                foreach ($imgs as $filename) {
                    $this->delete_image( $collQPath, '', $filename);
                    foreach ($imgFolders as $subfolderName) {
                        $this->delete_image( $collQPath, $subfolderName, $filename);
                    }
                }
            }
            // Delete folders
            foreach ($imgFolders as $subfolderName) {
                @rmdir( trailingslashit($collQPath) . $subfolderName );
            }
            rmdir($collQPath);
        }


       //Delete DB records
       abcfic_db_delete_collection( $collID );
       if(file_exists($collQPath)) { abcfic_msgs_error(56, $collQPath); }
    }

    public function get_collection_options( $collID ){

        $dbRow = abcfic_db_collection_options($collID);
        if(empty($dbRow)) {
            $this->coll_id = 0;
            return;
        }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }
        $this->set_coll_qpath();
     }

    public function get_upload_options( $collID ) {

        //coll_id, colls_spath, colls_folder, coll_folder, unique_filename, img_publish
        $dbRow = abcfic_db_upload_options( $collID );

        if(empty($dbRow)) {
            $this->collQPath = '';
            return;
        }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }

//        if(empty($this->coll_spath)) {return;}
//        if(empty($this->coll_folder)) {return;}
//        $this->collQPath = $this->build_coll_q_path( $this->coll_spath, $this->coll_folder);
        $this->set_coll_qpath();
    }

    public function add_thumb_folders( $collQPath ) {

        if (!file_exists( $collQPath )) {
            abcfic_msgs_error(48, '<b> ' . $collQPath . '</b>');
            return false;
        }

        $imgFolders = unserialize( ABCFIC_FOLDERS );
        foreach ($imgFolders as $subfolderName) {
            if(!$this->add_thumb_folder( $collQPath , $subfolderName)) { return false; }
        }
        return true;
    }

 //===================================================================================
     private function valid_folder_name($collName) {

        if(abcfic_lib_isblank($collName)) { return '';}
        //Remove invalid characters and spaces. Convert to lowercase
        $fn = sanitize_file_name( sanitize_title($collName) );
        if(abcfic_lib_isblank($fn)) { return '';}

        //Check DB for collection folder
        $qty = abcfic_db_coll_exists( $fn );
        if( $qty > 0 ){
            $fn = strtolower($fn . '_' . substr(abcfic_plugoptns_get_guid(), 1, 8));
        }
        return $fn;
    }

    private function create_folder( $collQPath ) {

        if ( !wp_mkdir_p( $collQPath ) ) {
            abcfic_msgs_error(48, '<b> ' . $collQPath . '</b>');
            return false;
        }
        return true;
    }

    private function add_thumb_folder( $collQPath , $folderName) {

        $newFolder = trailingslashit( $collQPath ) .  trailingslashit($folderName);
        if ( !wp_mkdir_p( $newFolder ) ) {
            abcfic_msgs_error(48, '<b> ' . $newFolder . '</b>');
            return false;
        }
        return true;
    }

    private function set_coll_qpath() {

        $collsSPath = '';
        $collsFolder = '';
        $collFolder = '';
        if(!empty($this->colls_spath)){ $collsSPath = trailingslashit($this->colls_spath);}
        if(!empty($this->colls_folder)){ $collsFolder = trailingslashit($this->colls_folder);}
        if(!empty($this->coll_folder)){ $collFolder = trailingslashit($this->coll_folder);}
        $path = $collsSPath . $collsFolder . $collFolder;

        if(!empty($path)) {
            $this->collQPath = untrailingslashit(trailingslashit( ABCFIC_ABSPATH ) . $path );
        }
    }

    private function delete_image( $collQPath, $subfolderName, $filename) {

         if(!empty($subfolderName)){ $subfolderName = trailingslashit($subfolderName);}
         @unlink(trailingslashit($collQPath). $subfolderName . $filename);
    }



    private function coll_default_options() {

        $optns = array(
        'coll_id'  => 0,
        'coll_name' => '',
        'max_img_w' => 0,
        'max_img_h' => 0,
        'img_quality' => 0,
        'img_resize' => 0,
        'client_side_resize' => 0,
        'unique_filename' => 0,
        'fixed_img_wh' => 0,
        'img_publish' => 1,
        'max_thumb_w' => 0,
        'max_thumb_h' => 0,
        'fixed_thumb_wh' => 0,
        'thumb_quality' => 0
        );
        return $optns;
    }
}
}