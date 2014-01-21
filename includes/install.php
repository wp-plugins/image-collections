<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Create all tables. Set default options
 * Called on register_activation hook
 */
register_activation_hook( ABCFIC_PLUGIN_FILE, 'abcfic_install_single_activate' );

function abcfic_install_single_activate() {
   abcfic_install_create_collection_tbls();
   abcfic_options_update_optns();
}

function abcfic_install_create_collection_tbls() {

    //To create tables we need dbDelta function located in upgrade.php. We'll have to load this file, as it is not loaded by default
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    global $wpdb;
    global $charset_collate;

    $tblImages = $wpdb->abcficimgs;
    $tblCollections = $wpdb->abcficcolls;

    if( $wpdb->get_var("SHOW TABLES LIKE '$tblImages'") != $tblImages ) {
        $sql = "CREATE TABLE " . $tblImages . " (
            img_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            coll_id INT UNSIGNED  NOT NULL,
            filename VARCHAR(255) NOT NULL,
            img_w INT UNSIGNED  NOT NULL DEFAULT '0',
            img_h INT UNSIGNED  NOT NULL DEFAULT '0',
            thumb_w INT UNSIGNED  NOT NULL DEFAULT '0',
            thumb_h INT UNSIGNED  NOT NULL DEFAULT '0',
            medium_w INT UNSIGNED  NOT NULL DEFAULT '0',
            medium_h INT UNSIGNED  NOT NULL DEFAULT '0',
            original_w INT UNSIGNED  NOT NULL DEFAULT '0',
            original_h INT UNSIGNED  NOT NULL DEFAULT '0',
            alt NVARCHAR(500) NOT NULL DEFAULT '',
            img_title NVARCHAR(500) NOT NULL DEFAULT '',
            caption1 NVARCHAR(1000) NOT NULL DEFAULT '',
            caption2 NVARCHAR(1000) NOT NULL DEFAULT '',
            caption3 NVARCHAR(1000) NOT NULL DEFAULT '',
            caption4 NVARCHAR(1000) NOT NULL DEFAULT '',
            href1 VARCHAR(500) NOT NULL DEFAULT '',
            href1_target TINYINT UNSIGNED NOT NULL DEFAULT '0',
            href2 VARCHAR(500) NOT NULL DEFAULT '',
            href2_target TINYINT UNSIGNED NOT NULL DEFAULT '0',
            set_no TINYINT UNSIGNED NOT NULL DEFAULT '0',
            published TINYINT UNSIGNED NOT NULL DEFAULT '1',
            archived TINYINT UNSIGNED NOT NULL DEFAULT '0',
            sort_order INT UNSIGNED NOT NULL DEFAULT '0',
            description NVARCHAR(5000) NOT NULL DEFAULT '',
            jscript VARCHAR(1000) NOT NULL DEFAULT '',
            added_dt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  pk_imgs_imgid (img_id),
            UNIQUE KEY  uidx_colid_imgid ( coll_id,img_id)
            ) $charset_collate;";
      dbDelta($sql);
    }

    if( $wpdb->get_var("SHOW TABLES LIKE '$tblCollections'") != $tblCollections ) {
        $sql = "CREATE TABLE " . $tblCollections . " (
            coll_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            coll_name NVARCHAR(255) NOT NULL,
            coll_desc NVARCHAR(255) NOT NULL DEFAULT '',
            colls_spath VARCHAR(255)  NOT NULL,
            colls_folder  VARCHAR(255) NOT NULL DEFAULT '',
            coll_folder VARCHAR(255)  NOT NULL,
            max_img_w INT UNSIGNED  NOT NULL DEFAULT '900',
            max_img_h INT UNSIGNED  NOT NULL DEFAULT '600',
            img_resize TINYINT UNSIGNED NOT NULL DEFAULT '0',
            client_side_resize TINYINT UNSIGNED NOT NULL DEFAULT '0',
            img_quality TINYINT UNSIGNED NOT NULL DEFAULT '90',
            unique_filename TINYINT UNSIGNED NOT NULL DEFAULT '1',
            fixed_img_wh TINYINT UNSIGNED NOT NULL DEFAULT '0',
            img_publish TINYINT UNSIGNED NOT NULL DEFAULT '1',
            max_thumb_w INT UNSIGNED  NOT NULL DEFAULT '60',
            max_thumb_h INT UNSIGNED  NOT NULL DEFAULT '60',
            thumb_quality TINYINT UNSIGNED NOT NULL DEFAULT '90',
            fixed_thumb_wh TINYINT UNSIGNED NOT NULL DEFAULT '0',
            max_medium_w INT UNSIGNED  NOT NULL DEFAULT '0',
            max_medium_h INT UNSIGNED  NOT NULL DEFAULT '0',
            medium_quality TINYINT UNSIGNED NOT NULL DEFAULT '90',
            fixed_medium_wh TINYINT UNSIGNED NOT NULL DEFAULT '0',
            keep_originals TINYINT UNSIGNED NOT NULL DEFAULT '0',
            img_meta_save TINYINT UNSIGNED NOT NULL DEFAULT '0',
            coll_archived TINYINT UNSIGNED NOT NULL DEFAULT '0',
            coll_published TINYINT UNSIGNED NOT NULL DEFAULT '1',
            featured_img_id INT UNSIGNED NOT NULL DEFAULT '0',
            added_by INT UNSIGNED  NOT NULL  DEFAULT '0',
            updated_by INT UNSIGNED  NOT NULL  DEFAULT '0',
            added_dt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_dt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  pk_colls_collid (coll_id)
            ) $charset_collate;";
      dbDelta($sql);
   }

   update_option( 'abcficl_db_version', '1' );
}