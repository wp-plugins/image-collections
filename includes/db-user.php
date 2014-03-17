<?php
//=== Database functions for PLUGINS & THEMES =======================================================

//Can be used by front end to create Select Collections dropdown.
//Selects all collections with coll_published = 1
function abcfic_dbu_cbo_collections() {
    global $wpdb;
    $colls = array();
    $colls[0] = '---'; //Select Collection
    $dbRows = $wpdb->get_results( "SELECT coll_id, coll_name FROM $wpdb->abcficcolls WHERE coll_published = 1 ORDER BY coll_name" );
    if ($dbRows) { foreach ($dbRows as $row) {$colls[$row->coll_id] = $row->coll_name;} }
    return $colls;
}

function abcfic_dbu_count_published( $collID ) {

    global $wpdb;
    $all = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->abcficimgs WHERE coll_id = %d", $collID ) );
    $published = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->abcficimgs WHERE coll_id = %d AND published = 1", $collID ) );
    return array('all' => $all, 'published' => $published);
}

