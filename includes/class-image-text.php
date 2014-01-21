<?php
/*
 *TODO: Validate inputs
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists('ABCFIC_Image_Text') ) {
class ABCFIC_Image_Text{

    var $errmsg = '';
    var $error = FALSE;

    var $img_id = 0;
    var $coll_id = 0;
    var $filename = '';
    var $alt = '';
    var $img_title = '';
    var $caption1 = '';
    var $caption2 = '';
    var $caption3 = '';
    var $caption4 = '';
    var $href1 = '';
    var $href1_target = '';
    var $href2 = '';
    var $href2_target = '';
    var $set_no = 0;
    var $description = '';

    function __construct( $imgID = 0 ) {
        $this->img_id = intval($imgID);
    }

    public function get_text_for_edit() {

       $dbRow = abcfic_db_get_text_for_edit( $this->img_id );
       foreach ($dbRow as $key => $value){
           $this->$key = $value ;
       }
    }

   public function get_img_text() {

       $dbRow = abcfic_db_get_text_for_edit( $this->img_id );
       foreach ($dbRow as $key => $value){
           $this->$key = $value ;
       }
       $out = array(
           'error' => false,
           'alt' => $this->alt,
           'img_title' => $this->img_title,
           'cap1' => $this->caption1,
           'cap2' => $this->caption2,
           'cap3' => $this->caption3,
           'cap4' => $this->caption4,
           'href1' => $this->href1,
           'hreft1' => $this->href1_target,
           'href2' => $this->href2,
           'hreft2' => $this->href2_target,
           'setno' => $this->set_no,
           'description' => $this->description
           );

       return $out;

    }

   public function update_text($post) {

    $post = stripslashes_deep($post);

    $optnDefaults = $this->default_options();
    $optns =  wp_parse_args( $post, $optnDefaults );
    $rowsAffected = abcfic_db_update_text($optns);
    $imgID = $optns['img_id'];

    if ( $rowsAffected === 1){
        $data = abcfic_db_get_text_for_edit( $imgID );
        $txtDiv = abcfic_cntbldr_tbl_txt_box($data);
        $out = array(
           'error' => false,
           'html' => $txtDiv);
    }
    else{
        $out = array(
           'error' => true,
           'error_msg' => 'Error: Text not updated. ' . $imgID);
    }
     return $out;
}

//============================================================
function default_options() {
     $options = array(
        'img_id' => 0,
        'alt' =>  '',
        'imgt' => '',
        'cap1' => '',
        'cap2' => '',
        'cap3' => '',
        'cap4' => '',
        'href1' => '',
        'hreft1' => '',
        'href2' => '',
        'hreft2' => '',
        'setno' => '',
        'description' => ''
     );
     return $options;
}
}
}