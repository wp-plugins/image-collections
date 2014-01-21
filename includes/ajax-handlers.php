<?php
add_action('wp_ajax_abcfic_resize_images', 'abcfic_ajax_resize_image' );

/**
* Process abcfic-ajax-resize.js.
* The script handling AJAX data should always die.
* Called: abcfic-ajax-resize.js
*/
function abcfic_ajax_resize_image() {

    //Bad nounce returns die(-1)
    check_ajax_referer( 'abcfic_resize_images_nounce' );

    //$_POST is created by ajax-resize.js
    $optnDefaults = abcfic_ajax_default_options();
    $optns =  wp_parse_args( $_POST, $optnDefaults );

    if ( $optns['img_id'] > 0) {
        switch ( $optns['operation'] ) {
            case 'ajax_recreate_thumbnails' :
                $param = array('img_id' => $optns['img_id'], 'isUpload' => 0);
                $objImg = new ABCFIC_Image($param);
                $result = $objImg->resize_img_create_thumbs();
                break;
            case 'ajax_resize_img_create_thumbs' :
                $param = array('img_id' => $optns['img_id'], 'isUpload' => 1);
                $objImg = new ABCFIC_Image($param);
                $result = $objImg->resize_img_create_thumbs();
                break;
            default :
                die('no_operation');
                break;
        }
        die ($result);
    }
    die('no_img_id');
}

function abcfic_ajax_default_options() {
     $options = array(
        'img_id' => 0,
        'operation' =>  ''
     );
     return $options;
}

//----------------------------------------------------------------------------------
add_action('wp_ajax_abcfic_update_img_text', 'abcfic_ajax_update_img_text');


function abcfic_ajax_update_img_text() {

    if( !isset( $_POST['abcfic_nounce'] ) || !wp_verify_nonce($_POST['abcfic_nounce'], 'abcfic_ajax_nounce') ){
        $out = array( 'error' => true, 'msg' => 'Error: Permissions check failed');
        echo $out;
        die();
    }

    include_once (ABCFIC_PLUGIN_DIR . 'includes/class-image-text.php');

    $imgTxt = new ABCFIC_Image_Text();
    $response = $imgTxt->update_text( $_POST );
    wp_send_json( $response );
    die();
}

add_action('wp_ajax_abcfic_get_img_text', 'abcfic_ajax_get_img_text');


function abcfic_ajax_get_img_text() {

    if( !isset( $_POST['abcfic_nounce'] ) || !wp_verify_nonce($_POST['abcfic_nounce'], 'abcfic_ajax_nounce') ){
        $out = array( 'error' => true, 'msg' => 'Error: Permissions check failed');
        echo $out;
        die();
    }

    $imgID = 0;
    if( isset( $_POST['img_id'] )) { $imgID = intval( $_POST['img_id'] );}

    if($imgID == 0){
        $out = array( 'error' => true, 'msg' => 'Error: Image ID is missing.');
        echo $out;
        die();
    }

    include_once (ABCFIC_PLUGIN_DIR . 'includes/class-image-text.php');

    $imgTxt = new ABCFIC_Image_Text($imgID);
    $response = $imgTxt->get_img_text();
    wp_send_json( $response );
    die();
}
