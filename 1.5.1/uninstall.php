<?php
/**
 * Fired when the plugin is uninstalled.
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )  { exit; }

// Leave no trail
if ( !is_multisite() ) {
    abcfic_uninstall_single();
}

function abcfic_uninstall_single() {

    delete_option( 'abcficl_options' );
    delete_option( 'abcficl_db_version' );
    abcfic_uninstall_drop_tbls();
}

function abcfic_uninstall_drop_tbls(){

    global $wpdb;
    $dropTbls = false;
    $qtyColl = 0;
    $qtyImgs = 0;

    $wpdb->abcficimgs = $wpdb->prefix . 'abcfic_images';
    $wpdb->abcficcolls  = $wpdb->prefix . 'abcfic_collections';

    $tblImages      = $wpdb->abcficimgs;
    $tblCollections = $wpdb->abcficcolls;

    if( $wpdb->get_var("SHOW TABLES LIKE '$tblCollections'") == $tblCollections ){ $qtyColl = $wpdb->get_var( "SELECT COUNT(1) FROM $tblCollections" );}
    if( $wpdb->get_var("SHOW TABLES LIKE '$tblImages'") == $tblImages ){ $qtyImgs = $wpdb->get_var( "SELECT COUNT(1) FROM $tblImages" );}

    if( $qtyColl == 0 && $qtyImgs == 0) { $dropTbls = true;}

    if( $dropTbls ){
	$wpdb->query("DROP TABLE IF EXISTS $tblImages");
	$wpdb->query("DROP TABLE IF EXISTS $tblCollections");
    }
}