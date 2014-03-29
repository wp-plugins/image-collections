<?php
//=== Database functions to use by PLUGINS & THEMES ==V1===========================================

//Selects all collections with coll_published = 1
if ( !function_exists( 'abcfic_dbu_cbo_collections' ) ){
    function abcfic_dbu_cbo_collections() {
        global $wpdb;
        $colls = array();
        $colls[0] = '---'; //Select Collection
        $dbRows = $wpdb->get_results( "SELECT coll_id, coll_name FROM $wpdb->abcficcolls WHERE coll_published = 1 ORDER BY coll_name" );
        if ($dbRows) { foreach ($dbRows as $row) {$colls[$row->coll_id] = $row->coll_name;} }
        return $colls;
    }
}

if ( !function_exists( 'abcfic_dbu_count_published' ) ){
    function abcfic_dbu_count_published( $collID ) {

        global $wpdb;
        $all = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->abcficimgs WHERE coll_id = %d", $collID ) );
        $published = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->abcficimgs WHERE coll_id = %d AND published = 1", $collID ) );
        return array('all' => $all, 'published' => $published);
    }
}

if ( !function_exists( 'abcfic_dbu_coll_url' ) ){
    function abcfic_dbu_coll_url($collID) {

        global $wpdb;
        $dbRow = $wpdb->get_row( $wpdb->prepare(
        "SELECT colls_spath, colls_folder, coll_folder
        FROM $wpdb->abcficcolls
        WHERE coll_id = %d", $collID ) ,ARRAY_N );

        //$dbRow Returns NULL if no result is found
        if(is_null($dbRow)) { return ''; }

        $collsSPath = $dbRow[0];
        $collsFolder = $dbRow[1];
        $collFolder = $dbRow[2];
        return abcfic_dbu_coll_url_bldr($collsSPath, $collsFolder, $collFolder);
    }
}

if ( !function_exists( 'abcfic_dbu_coll_url_bldr' ) ){
    function abcfic_dbu_coll_url_bldr($collsSPath, $collsFolder, $collFolder) {

        if(!empty($collsSPath)){ $collsSPath = trailingslashit($collsSPath);}
        if(!empty($collsFolder)){ $collsFolder = trailingslashit($collsFolder);}
        if(!empty($collFolder)){ $collFolder = trailingslashit($collFolder);}

        return untrailingslashit(trailingslashit( site_url() ) . $collsSPath . $collsFolder . $collFolder );
    }
}