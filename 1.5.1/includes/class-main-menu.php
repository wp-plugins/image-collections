<?php
/*
 * Add menu
 *OK: 1
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists('ABCFIC_Main_Menu') ) {
class ABCFIC_Main_Menu{

    function __construct() {
        add_action( 'admin_menu', array (&$this, 'add_menu') );
    }

    function add_menu()  {
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page( 'abcCollections', 'Collections', 'manage_options', ABCFIC_PLUGIN_FOLDER, array( &$this, 'load_page' ), ABCFIC_PLUGIN_URL . 'images/icbw-16x16.png');

        //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function )
        add_submenu_page( ABCFIC_PLUGIN_FOLDER , 'Add Collection', 'Add Collection', 'manage_options', ABCFIC_PLUGIN_FOLDER, array (&$this, 'load_page'));
        add_submenu_page( ABCFIC_PLUGIN_FOLDER , 'Manage Collections', 'Manage Collections', 'manage_options', 'abcfic-collection-manger', array (&$this, 'load_page'));
        add_submenu_page( ABCFIC_PLUGIN_FOLDER , 'Default Options', 'Default Options', 'manage_options', 'abcfic-default-options', array (&$this, 'load_page'));
        add_submenu_page( ABCFIC_PLUGIN_FOLDER , 'Uninstall', 'Uninstall', 'manage_options', 'abcfic-uninstall', array (&$this, 'load_page'));
    }

    function load_page() {

        $abcfic = ABCFIC_Main();

        switch ($_GET['page']){
            case ABCFIC_PLUGIN_FOLDER :
                include_once (ABCFIC_PLUGIN_DIR . 'includes/class-collection.php');
                include_once ( ABCFIC_PLUGIN_DIR . 'includes/pg-add-collection.php' );
                abcfic_pg_add_collection();
                break;
            case 'abcfic-collection-manger' :
                include_once ( ABCFIC_PLUGIN_DIR . 'includes/class-upload.php' );
                include_once ( ABCFIC_PLUGIN_DIR . 'includes/class-collection-manger.php' );
                $abcfic->coll_manager = new ABCFIC_Collection_Manager();
                $abcfic->coll_manager->render_collection_page();
                break;
            case 'abcfic-default-options' :
                include_once ( ABCFIC_PLUGIN_DIR . 'includes/pg-default-options.php' );
                abcfic_default_options();
                break;
            case 'abcfic-uninstall' :
                include_once ( ABCFIC_PLUGIN_DIR . 'includes/pg-uninstall.php' );
                break;
        }
    }
}
}