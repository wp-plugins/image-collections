<?php
/*
 * Resize img if needed. Create thumb
 *
 * TODO:
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists('ABCFIC_Image') ) {

class ABCFIC_Image{

    var $img_id = 0;
    var $filename = '';

    var $img_w = 0;
    var $img_h = 0;
    var $max_img_w = 0;
    var $max_img_h = 0;
    var $fixed_img_wh = 0;
    var $img_resize = 0;
    var $img_quality = 90;

    var $max_thumb_w = 0;
    var $max_thumb_h = 0;
    var $fixed_thumb_wh = 0;
    var $thumb_quality = 80;

    var $max_thumb2_w = 80;
    var $max_thumb2_h = 80;
    var $thumb2_quality = 60;
    var $fixed_thumb2_wh = 0;

    var $colls_spath = '';
    var $colls_folder = '';
    var $coll_folder = '';
    var $collQPath = '';
    var $imgQName = '';
    var $thumbQName = '';
    var $thumb2QName = '';

    var $isUpload = 0;

    const IMG_MAIN = 1;
    const IMG_THUMB = 2;
    const IMG_THUMB2 = 4;

    function __construct( $optns = array() ) {

        $optnDefaults = $this->default_options();
        $optns =  wp_parse_args( $optns, $optnDefaults );

        foreach ($optns as $key => $value){
            $this->$key = $value ;
        }
    }

    //Copy image to originals folder if selected
    //Creade medium size if selected
    //Create thumbs
    //Resize uploaded image if needed.
    public function resize_img_create_thumbs() {

        if(!$this->img_id > 0) { return 'no_img'; }

        //Get collection options
        $dbRow = abcfic_db_image_options($this->img_id);
        if(empty($dbRow)) { return 'no_img'; }
        foreach ($dbRow as $key => $value) { $this->$key = $value; }
        $this->set_paths();
        $this->set_thumb2_wh();
        $originalQName = ''; //$this->originalQName

        //return 'Before thumb resize';

        //Create thumb
        $out = $this->resize_image( $this->img_id, $originalQName, $this->imgQName, $this->thumbQName,
            $this->max_thumb_w, $this->max_thumb_h, $this->fixed_thumb_wh, $this->thumb_quality, $this->img_w, $this->img_h, self::IMG_THUMB );

        if($out !== 'OK') { return $out; }

        if($this->isUpload == 1) {
            //Create thumb2
            $this->resize_image( $this->img_id, $originalQName, $this->imgQName, $this->thumb2QName,
                $this->max_thumb2_w, $this->max_thumb2_h, $this->fixed_thumb2_wh, $this->thumb2_quality, $this->img_w, $this->img_h, self::IMG_THUMB2 );

            if( $this->img_resize == 1 ) {
                //Resize main image
                $this->resize_image( $this->img_id, $originalQName, $this->imgQName, $this->imgQName,
                    $this->max_img_w, $this->max_img_h, $this->fixed_img_wh, $this->img_quality, $this->img_w, $this->img_h, self::IMG_MAIN );
            }
        }

        return 'OK';
    }
    //======================================================================================
    private function set_thumb2_wh(){

        if($this->fixed_img_wh == 1){
            $this->fixed_thumb2_wh = 1;

            if($this->max_img_w > $this->max_img_h){
                $x = $this->max_img_h/$this->max_img_w;
                $max_h = $this->max_thumb2_h * $x;
                $this->max_thumb2_h = ceil( $max_h );
            }
            else{
                $x = $this->max_img_w/$this->max_img_h;
                $max_w = $this->max_thumb2_w * $x;
                $this->max_thumb2_w = ceil( $max_w );
            }
        }
    }

    private function resize_image( $imgID, $originalQName, $sourceQName, $destQName, $maxW, $maxH, $fixedWH, $quality, $imgW, $imgH, $imgType ) {

        if( $maxW == 0 || $maxH == 0 ) { return 'OK'; }
        if( !empty($originalQName) ) {
            if(is_file($originalQName)) { $sourceQName = $originalQName; }
        }

        $crop = true;
        //$resize = false;
        //$saved = false;
        if($fixedWH == 0) { $crop = false; }

        $img = wp_get_image_editor( $sourceQName );
        if ( is_wp_error( $img ) ){
           $errStr = $img->get_error_message();
           return $errStr;
        }

        $img->set_quality( $quality );
        $resize = $img->resize( $maxW, $maxH, $crop );

        if ($resize == false ) { return 'Error: Imagemg resize failed.'; }
        $saved = $img->save($destQName);

        if ($saved == false ){ return 'Error: Failed to save resized image.'; }

        $imgW = $saved['width'];
        $imgH = $saved['height'];
        if ($imgType == self::IMG_MAIN ){ abcfic_db_update_image_wh ( $imgID, $imgW, $imgH ); }
        if ($imgType == self::IMG_THUMB ){ abcfic_db_update_thumb_wh ( $imgID, $imgW, $imgH ); }

        return 'OK';
    }

    private function set_paths() {
        $collDir = abcfic_optns_coll_path($this->colls_spath, $this->colls_folder, $this->coll_folder);
        if(empty($collDir)) { return; }

        $this->collQPath = $collDir;
        $this->imgQName = abcfic_optns_file_path($collDir, $this->filename);
        $this->thumbQName = abcfic_optns_file_path($collDir, 'thumbs', $this->filename);
        $this->thumb2QName = abcfic_optns_file_path($collDir, 'thumbs2', $this->filename);
     }

     private function default_options() {
        $options = array(
           'img_id' => 0,
           'coll_id' =>  0,
           'collQPath' => '',
           'isUpload' => 0
        );
        return $options;
    }
}
}