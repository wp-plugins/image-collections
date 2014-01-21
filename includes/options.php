<?php
/*
 * Options
 */
function abcfic_options_default_optns() {

    $optns = array(
    'max_img_w' => 900,
    'max_img_h' => 600,
    'img_quality' => 90,
    'img_resize' => 1,
    'client_side_resize' => 0,
    'unique_filename' => 1,
    'fixed_img_wh' => 0,
    'img_publish' => 1,
    'max_thumb_w' => 60,
    'max_thumb_h' => 60,
    'fixed_thumb_wh' => 1,
    'thumb_quality' => 90,
    'colls_folder'  => 'img-collections',
    'colls_spath'  => abcfic_default_colls_spath()

    );
    return $optns;
}

function abcfic_default_colls_spath() {

    return 'wp-content/uploads';
}

function abcfic_options_get_optns() {

   $defaults = abcfic_options_default_optns();
   $optns =  wp_parse_args(get_option( 'abcficl_options', array() ) , $defaults );

   $collsQPath = trailingslashit( ABCFIC_ABSPATH ) . trailingslashit( $optns['colls_spath'] ) . trailingslashit( $optns['colls_folder'] );
   $qPath = array( 'collsQPath' => $collsQPath);
   return wp_parse_args($optns , $qPath );

}

function abcfic_options_update_optns() {

    $optns = abcfic_options_get_optns();
    update_option('abcficl_options', $optns);
}

function abcfic_update_abcfic_options($input) {

    $optns = wp_parse_args( abcfic_options_get_optns(), array( 'collsFolder' => '') );

    $optns['max_img_w'] = abcfic_optn_int('max_img_w', $input, $optns);
    $optns['max_img_h'] = abcfic_optn_int('max_img_h', $input, $optns);
    $optns['img_quality'] = abcfic_optn_int('img_quality', $input, $optns);
    $optns['img_resize'] = abcfic_optn_chk('img_resize', $input, 0);
    $optns['unique_filename'] = abcfic_optn_chk('unique_filename', $input, 0);
    $optns['fixed_img_wh'] = abcfic_optn_chk('fixed_img_wh', $input, 0);
    $optns['img_publish'] = abcfic_optn_chk('img_publish', $input, 1);
    $optns['max_thumb_w'] = abcfic_optn_int('max_thumb_w', $input, $optns);
    $optns['max_thumb_h'] = abcfic_optn_int('max_thumb_h', $input, $optns);
    $optns['fixed_thumb_wh'] = abcfic_optn_chk('fixed_thumb_wh', $input, 0);
    $optns['thumb_quality'] = abcfic_optn_int('thumb_quality', $input, $optns);
    if(!empty($optns['collsFolder'])) {
        $optns['colls_folder'] = $optns['collsFolder'];
        $optns['collsFolder'] = '';
    }
    update_option( 'abcficl_options', $optns );
}


function abcfic_reset_options() {

    $optns = abcfic_options_default_optns();
    update_option( 'abcficl_options', $optns );
}
//==Collection tabs======================================================================
function abcfic_optns_default_coll_url_params() {
     $options = array(
        'page' => '',
        'mode' => '',
        'collid' => '0',
        'tab' => 'options',
        'order' => '',
        'orderby' => 'sort_order'
     );
     return $options;
}
//Array ( [page] => abcfic-collection-manger [mode] => tabscoll [collid] => 41 [tab] => published )
function abcfic_optns_coll_url_params( $get ) {

    //$optns = $_GET;
    if(!$get){ $get = array();}
    $defaults = abcfic_optns_default_coll_url_params();

    $params = wp_parse_args( $get, $defaults );
    $order_dir = ( !empty($params['order']) && $params['order'] == 'desc' ) ? 'DESC' : 'ASC';
    $order_by = (!empty($params['orderby'])) ? $params['orderby'] : 'sort_order';
    $params['order'] = $order_dir;
    $params['orderby'] = $order_by;

    return wp_parse_args( $params, $defaults );
}

function abcfic_optns_default_tab_get_params() {
     $options = array(
        'page' => 'abcfic-collection-manger',
        'mode' => 'pg-collections',
        'collid' => '0',
        'img_id' => '0',
        'tab' => 'options'
     );
     return $options;
}
function abcfic_optns_tab_get_params( $get ) {

    //$optns = $_GET;
    if(!$get){ $get = array();}
    $defaults = abcfic_optns_default_tab_get_params();

    return wp_parse_args( $get, $defaults );
}

function abcfic_optns_default_bulk_params() {
     $options = array(
        'page' => '',
        'bulkActionType' => '',
        'collID' => 0,
        'collID_Dest' => 0
     );
     return $options;
}

function abcfic_optns_bulk_params( $post ) {

    if(!$post){ $post = array();}
    $defaults = abcfic_optns_default_bulk_params();

    return wp_parse_args( $post, $defaults );
}

//=========================================================================
function abcfic_optns_full_path($collsSPath, $collsFolder, $collFolder, $subFolder='', $file='') {

    if(!empty($collsSPath)){ $collsSPath = trailingslashit($collsSPath);}
    if(!empty($collsFolder)){ $collsFolder = trailingslashit($collsFolder);}
    if(!empty($collFolder)){ $collFolder = trailingslashit($collFolder);}
    if(!empty($subFolder)){ $subFolder = trailingslashit($subFolder);}

    return untrailingslashit(trailingslashit( ABCFIC_ABSPATH ) . $collsSPath . $collsFolder . $collFolder . $subFolder . $file );
}

function abcfic_optns_coll_path($collsSPath, $collsFolder, $collFolder) {

    if(!empty($collsSPath)){ $collsSPath = trailingslashit($collsSPath);}
    if(!empty($collsFolder)){ $collsFolder = trailingslashit($collsFolder);}
    if(!empty($collFolder)){ $collFolder = trailingslashit($collFolder);}

    return untrailingslashit(trailingslashit( ABCFIC_ABSPATH ) . $collsSPath . $collsFolder . $collFolder );
}

function abcfic_optns_file_path($collPath, $subFolder='', $file='') {

    if(empty($collPath)) { return '';}
    if(!empty($subFolder)){ $subFolder = trailingslashit($subFolder);}
    if(!empty($file)){ $file = trailingslashit($file);}
    return untrailingslashit(trailingslashit( $collPath ) . $subFolder . $file );
}

function abcfic_optns_full_url($collsSPath, $collsFolder, $collFolder, $subFolder='', $file='', $slash = false) {

    if(!empty($collsSPath)){ $collsSPath = trailingslashit($collsSPath);}
    if(!empty($collsFolder)){ $collsFolder = trailingslashit($collsFolder);}
    if(!empty($collFolder)){ $collFolder = trailingslashit($collFolder);}
    if(!empty($subFolder)){ $subFolder = trailingslashit($subFolder);}

    if($slash){
        return trailingslashit(trailingslashit( site_url() ) . $collsSPath . $collsFolder . $collFolder . $subFolder . $file );
    }
    else{
        return untrailingslashit(trailingslashit( site_url() ) . $collsSPath . $collsFolder . $collFolder . $subFolder . $file );
    }

}

function abcfic_optns_coll_url($collsSPath, $collsFolder, $collFolder) {

    if(!empty($collsSPath)){ $collsSPath = trailingslashit($collsSPath);}
    if(!empty($collsFolder)){ $collsFolder = trailingslashit($collsFolder);}
    if(!empty($collFolder)){ $collFolder = trailingslashit($collFolder);}

    return untrailingslashit(trailingslashit( site_url() ) . $collsSPath . $collsFolder . $collFolder );
}

function abcfic_optns_file_url($collURL, $subFolder='', $file='') {

    if(!empty($subFolder)){ $subFolder = trailingslashit($subFolder);}

    return untrailingslashit( trailingslashit($collURL) . $subFolder . $file );
}


function abcfic_update_colls_path($oldSPath, $newSPath) {

    $optns = abcfic_options_get_optns();
    if($optns["collPath"] == $oldSPath){
        $optns["collPath"] = $newSPath;
        update_option( 'abcficl_options', $optns );
    }
}


function abcfic_optn_int($optn, $input, $optns) {
    return ( isset( $input[$optn] ) ? abcfic_valid_int( $input[$optn], $optns[$optn]) : $optns[$optn] );
}

function abcfic_optn_chk($optn, $input, $default) {
    return ( isset( $input[$optn] ) ?  $input[$optn] : $default );
}

function abcfic_valid_colls_path($in) {
    $default = get_colls_path();
    if(abcfic_IsBlank($in)) { return $default; }
    else { return $in; }
}

function abcfic_valid_str($in, $default) {

    if(abcfic_IsBlank($in)) { return $default; }
    else { return $in; }
}

function abcfic_valid_int( $in, $default) {
    return abcfic_convert_to_int($in, $default);
}

function abcfic_convert_to_int($in, $default = '') {

    $out = $default;
    if(!isset($in)){return $default;}
    if(is_string($in)){ $in = abcfic_remove_non_numeric($in);}
    if(is_numeric($in)){$out = (int)$in;}
    return $out;
}

function abcfic_remove_non_numeric($string) { return preg_replace('/\D/', '', $string); }

function abcfic_IsBlank($in){
    return (!isset($in) || trim($in)==='');
}
function abcfic_plugoptns_get_guid(){
    $out = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    return str_replace('-', '', $out);
}
