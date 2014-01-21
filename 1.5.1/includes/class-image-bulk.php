<?php
/*
 * Bulk operations on images.
 * OK: 1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( 'ABCFIC_Image_Bulk' ) ) {
class ABCFIC_Image_Bulk {

    var $errmsg = '';
    var $errmsgs = '';
    var $error = FALSE;

    var $img_id = 0;
    var $filename = '';
    var $colls_spath = '';
    var $colls_folder = '';
    var $coll_folder = '';
    var $unique_filename = 1;

    var $collQPath = '';
    var $imgQName = '';
    var $thumbQName = '';

    var $colls_spath_dest = '';
    var $coll_folder_dest = '';
    var $colls_folder_dest = '';
    var $unique_filename_dest = 1;

    var $collQPath_Dest = '';
    var $imgQName_Dest = '';
    var $thumbQName_Dest = '';

    public function __construct() {
    }


    public function delete_files($imgIDs, $collID) {

        $errors = '';
        if ( !is_array($imgIDs) ){ $imgIDs = array($imgIDs);}

        $this->get_collection_source( $collID );

        if ( $this->err_dir_permissions( $this->collQPath ) == 'KO') { return; }
        $files = abcfic_db_get_images_by_ids( $imgIDs );
        if ( $this->err_files( $files ) == 'KO') { return; }

        foreach ($files as $f) {
            $file = $f->filename;

            $fileQName = $this->get_qfilename($this->collQPath, $file );

            //Delete main image
            if ( !@unlink( $fileQName )) {
                if(file_exists( $fileQName )){
                    $errors .= $this->err_file_not_deleted( $file );
                    continue;
                }
            }

            //Delete other images
            $this->process_thumbs($file, '', 'delete');

            $rowsAffected = abcfic_db_delete_image($f->img_id);
            $statusMsg = $this->err_update_db($rowsAffected, $file);
            if( $statusMsg != 'OK') { $errors .= $statusMsg; continue;}
        }

        abcfic_db_update_collection_dt($collID);
        if ( $errors != '' ){ abcfic_msgs_error(0, $errors); }  else { abcfic_msgs_ok(); }
        return;
    }
//=======================================================================================
    private function get_collection_source( $collID ) {
        //$dbRow = colls_spath, colls_folder, coll_folder, unique_filename
        $dbRow = abcfic_db_get_collection_info($collID);

        $this->colls_spath = $dbRow[0];
        $this->colls_folder = $dbRow[1];
        $this->coll_folder = $dbRow[2];
        $this->unique_filename = $dbRow[3];

        $this->collQPath = abcfic_optns_coll_path($this->colls_spath, $this->colls_folder, $this->coll_folder);
    }


    private function get_qfilename($collQPath, $filename ) {
        return trailingslashit($collQPath) . $filename;
    }

    private function get_thumb_qfilename($collQPath, $fldr, $filename ) {
        return trailingslashit($collQPath) . trailingslashit($fldr) . $filename;
    }

    private function process_thumbs($fnOld, $fnNew, $action){

        $imgFolders = unserialize( ABCFIC_FOLDERS );

        foreach ($imgFolders as $f){
            $qNameSource = $this->get_thumb_qfilename($this->collQPath, $f, $fnOld );
            $qNameDest = $this->get_thumb_qfilename($this->collQPath_Dest, $f, $fnNew );

            switch ( $action ) {
                case 'copy' :
                    @copy($qNameSource, $qNameDest);
                    break;
                case 'move' :
                    @rename($qNameSource, $qNameDest);
                    break;
                case 'delete' :
                    @unlink($qNameSource);
                    break;
                default:
                    break;
            }
        }
    }

//===============================================================
    private function err_dir_permissions($collQPath) {
        if ( !is_writeable( $collQPath ) ) {
                echo abcfic_msgs_error(69, esc_html( $collQPath ));
                return 'KO';
        }
        return 'OK';
    }

    private function err_files($files) {
        if (!$files) {
            echo abcfic_msgs_error(68);
            return 'KO';
        }
        return 'OK';
    }

    private function err_update_db($rowsAffected, $fileName) {
        if($rowsAffected === 0){
                return sprintf(__('Threre was a problem updating database for file  %1$s','abcfic-td'),
                    '<strong>' . esc_html( $fileName ) . '</strong>' ) . '<br />';
                //continue;
        }
        return 'OK';
    }

    private function err_file_not_deleted($filename) {
        return sprintf('Failed to delete image: <strong>' . esc_html($filename)) . '</strong><br />';
    }
}
}