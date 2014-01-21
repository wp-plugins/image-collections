<?php
/**
 * Add scripts, styles and icons
 *TODO:
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_enqueue_scripts', 'abcfic_scripts_add_admin_css' );
add_action( 'admin_enqueue_scripts', 'abcfic_scripts_add_admin_js' );

function abcfic_scripts_add_admin_css() {

    wp_register_style( 'abcfich-css-admin', trailingslashit(ABCFIC_PLUGIN_URL) .'css/abcfic-122-min.css', array(), ABCFIC_VERSION, 'all' );
    wp_register_style( 'abcicf-jqueryui', ABCFIC_PLUGIN_URL .'css/smoothness/jquery-ui-1103-min.css', array(), ABCFIC_VERSION, 'all' );
    wp_register_style( 'abcfic-css-shutter', ABCFIC_PLUGIN_URL .'js/shutter/shutter-reloaded-133-min.css', array(), ABCFIC_VERSION, 'screen');

    wp_enqueue_style('abcfich-css-admin');
    wp_enqueue_style('abcicf-jqueryui');
    wp_enqueue_style('abcfic-css-shutter');
}

function abcfic_scripts_add_admin_js() {

    // no need to go on if it's not a plugin page ?????????
    if( !isset($_GET['page']) ){ return; }

    //wp_register_script( $handle, $src, $deps, $ver, $in_footer );
    wp_register_script('abcfic-ajax-resize', ABCFIC_PLUGIN_URL . 'js/abcfic-ajax-resize-03-min.js', array('jquery'), ABCFIC_VERSION);
    wp_localize_script('abcfic-ajax-resize', 'abcfic_localize_vars', array(
                            'ajaxurl' => admin_url('admin-ajax.php'),
                            'action' => 'abcfic_resize_images',
                            'nonce' => wp_create_nonce( 'abcfic_resize_images_nounce' ),
                            'ids' => '',
                            'timeout' => 60000,
                            'no_img' => 'Image is missing',
                            'permission' => 'You do not have the correct permission',
                            'no_operation' => 'Error in abcfic-ajax-resize. Operation name not found.',
                            'no_img_id' => 'Error in abcfic-ajax-resize. Img Id is missing.',
                            'error' => 'Unexpected Error in abcfic-ajax-resize.',
                            'failure' => 'A failure occurred.',
                            'err_timeout' => 'A timeout occurred.'
    ));
    wp_register_script( 'abcfic-plupload-handler', ABCFIC_PLUGIN_URL .'js/abcfic-plupload-03-min.js', array('plupload-all'), ABCFIC_VERSION );
    wp_register_script('abcfic-progress-bar', ABCFIC_PLUGIN_URL .'js/abcfic-progressbar-04-min.js', array('jquery'), ABCFIC_VERSION);
    wp_register_script('shutter', ABCFIC_PLUGIN_URL .'js/shutter/shutter-reloaded-133-min.js', false ,ABCFIC_VERSION, true);
    wp_register_script('abcfic-sorter', ABCFIC_PLUGIN_URL .'js/abcfic-sorter-01-min.js', false ,ABCFIC_VERSION, true);

    $params = abcfic_optns_coll_url_params( $_GET );

    switch ($params['page']) {
    case "abcfic-collection-manger" :
        wp_enqueue_script( 'jquery-ui-dialog' );
        $tab = $params['tab'];
        if($tab == 'published' || $tab == 'unpublished' || $tab == 'archived') {
            wp_enqueue_script( 'shutter' );
            wp_enqueue_script( 'abcfic-ajax-resize' );
            wp_enqueue_script( 'abcfic-progress-bar' );
        }
        if($tab == 'uploader') {
            wp_enqueue_script( 'abcfic-plupload-handler' );
            wp_enqueue_script( 'abcfic-ajax-resize' );
            wp_enqueue_script( 'abcfic-progress-bar' );
        }
        if($tab == 'sort') {
            wp_enqueue_script( 'abcfic-sorter' );
        }
        break;
    default:
        break;
    }
}