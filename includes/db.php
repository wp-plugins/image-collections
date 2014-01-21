<?php
/*
 * TODO:
 * abcfic_db_collection_options? abcfic_db_image_options ?
 */
//=== PLUGINS & THEMES =======================================================
//Dropdown Collections. Can be used by front end to create Select Collections dropdown.
function abcfic_db_cbo_collections() {
    global $wpdb;
    $colls = array();
    $colls[0] = abcfic_inputbldr_txt(97); //Select Collection
    $dbRows = $wpdb->get_results( "SELECT coll_id, coll_name FROM $wpdb->abcficcolls WHERE coll_published = 1 ORDER BY coll_name" );
    if ($dbRows) { foreach ($dbRows as $row) {$colls[$row->coll_id] = $row->coll_name;} }
    return $colls;
}

//====SELECT==================================================================
function abcfic_db_coll_exists( $collName ) {

    global $wpdb;
    return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM $wpdb->abcficcolls WHERE coll_folder = %s", $collName ) );
}

function abcfic_db_cbo_bulk_colls($collID) {
    global $wpdb;
    $cbo = array();
    $rows = $wpdb->get_results( $wpdb->prepare( "SELECT coll_id, coll_name FROM $wpdb->abcficcolls WHERE coll_id != %d ORDER BY coll_name", $collID ));
    if ($rows) { foreach ($rows as $row) {$cbo[$row->coll_id] = $row->coll_name;} }
    return $cbo;
}

function abcfic_db_collection_images( $collID ) {
    global $wpdb;
    $imgs = $wpdb->get_col( $wpdb->prepare( "SELECT filename FROM $wpdb->abcficimgs WHERE coll_id = %d", $collID) );
    return $imgs;
}

function abcfic_db_upload_options( $collID ) {

    if(is_int($collID)) {
        global $wpdb;
        $dbRow = $wpdb->get_row( $wpdb->prepare( "SELECT coll_id, colls_spath, colls_folder, coll_folder, unique_filename, img_publish
                FROM $wpdb->abcficcolls WHERE coll_id = %d", $collID, ARRAY_A ) );
        if($dbRow != null) { return $dbRow; }
    }
    return array();
}

function abcfic_db_coll_paths( $collID ) {

    if(is_int($collID)) {
        global $wpdb;
        $dbRow = $wpdb->get_row( $wpdb->prepare( "SELECT colls_spath, colls_folder, coll_folder
                FROM $wpdb->abcficcolls WHERE coll_id = %d", $collID, ARRAY_A ) );
        if($dbRow != null) { return $dbRow; }
    }
    return array();
}

function abcfic_db_collection_name( $collID ) {

    if( is_int($collID)) {
        global $wpdb;
        $collName = $wpdb->get_var( $wpdb->prepare( "SELECT coll_name FROM $wpdb->abcficcolls WHERE coll_id = %d", $collID ) );
    }
    return isset( $collName ) ? $collName : '';
}

function abcfic_db_collection( $collID ) {

    if(is_int($collID)) {
        global $wpdb;
        $dbRow = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->abcficcolls WHERE coll_id = %d", $collID, ARRAY_A ));
        if($dbRow != null) { return $dbRow; }
    }
    return array();
}

function abcfic_db_collection_options( $collID ) {

    if(is_int($collID)) {
        global $wpdb;
        $dbRow = $wpdb->get_row( $wpdb->prepare(
        "SELECT coll_id, coll_name, colls_spath, colls_folder, coll_folder,
                max_img_w, max_img_h, img_resize, img_quality, unique_filename,
                fixed_img_wh, max_thumb_w, max_thumb_h, fixed_thumb_wh, thumb_quality
        FROM $wpdb->abcficcolls
        WHERE coll_id = %d", $collID, ARRAY_A ));

        if($dbRow != null) { return $dbRow; }
    }
    return array();
}

function abcfic_db_get_collection_info($collID) {
    global $wpdb;

    $dbRow = $wpdb->get_row( $wpdb->prepare(
    "SELECT colls_spath, colls_folder, coll_folder, unique_filename
    FROM $wpdb->abcficcolls
    WHERE coll_id = %d ", $collID ) ,ARRAY_N );

    if($dbRow != null) { return $dbRow; }
    return array();
}


function abcfic_db_tbl_collection( $collID, $orderBy, $orderDir ) {

    global $wpdb;
    return $wpdb->get_results(
            $wpdb->prepare( "SELECT i.*, '' thumb, c.colls_spath, c.colls_folder, c.coll_folder, c.max_thumb_w, c.max_thumb_h
                    FROM  $wpdb->abcficimgs i
                    JOIN  $wpdb->abcficcolls c ON (i.coll_id = c.coll_id)
                    WHERE i.coll_id = %d
                    ORDER BY {$orderBy} {$orderDir}", $collID ), ARRAY_A);
}

function abcfic_db_tbl_collections( $orderBy, $orderDir ) {

    global $wpdb;
    $out = $wpdb->get_results(
            $wpdb->prepare( "SELECT c.coll_id AS rno, c.coll_id, c.coll_name, i.imgs_qty,
                CONCAT_WS('x', convert(c.max_thumb_w, char), convert(c.max_thumb_h,char)) as thumb_wh,
                CONCAT_WS('x', convert(c.max_img_w, char), convert(c.max_img_h,char)) as img_wh,
                added_dt, updated_dt
                FROM  $wpdb->abcficcolls c
                LEFT JOIN  (SELECT coll_id, COUNT(1) AS imgs_qty
                FROM $wpdb->abcficimgs GROUP BY coll_id) i ON (c.coll_id = i.coll_id)
                ORDER BY {$orderBy} {$orderDir}",''), ARRAY_A);

    return $out;
}

function abcfic_db_imgs_for_sort($collID, $orderBy = 'sort_order', $orderDir = 'ASC') {

    global $wpdb;
    $thumbs = array();

    // Say no to any other value
    $orderDir = ( $orderDir == 'DESC') ? 'DESC' : 'ASC';
    $orderBy  = ( empty($orderBy) ) ? 'sort_order' : $orderBy;

    if( is_numeric($collID) ){
        $dbRows = $wpdb->get_results( $wpdb->prepare(
        "SELECT i.img_id, i.filename, c.colls_spath, c.colls_folder, c.coll_folder
        FROM $wpdb->abcficcolls c JOIN $wpdb->abcficimgs i ON c.coll_id = i.coll_id
        WHERE i.coll_id = %d
        ORDER BY i.{$orderBy} {$orderDir}", $collID ), OBJECT_K );

        if ($dbRows) {
            $url = abcfic_optns_full_url(current($dbRows)->colls_spath, current($dbRows)->colls_folder,  current($dbRows)->coll_folder, 'thumbs2', '', true);
            foreach ( $dbRows as $dbRow ) {
                //$thumbs[$dbRow->img_id] = $url . '/' . $dbRow->filename;
                $thumbs[$dbRow->img_id] = $url . $dbRow->filename;
            }
        }
    }
    return $thumbs;
}

function abcfic_db_img_for_custom_crop($imgID) {

    global $wpdb;
    $dbRow = $wpdb->get_row( $wpdb->prepare(
            "SELECT i.img_id, i.filename, i.img_w, i.img_h,
            c.colls_spath, c.colls_folder, c.coll_folder, c.max_thumb_w, c.max_thumb_h, c.fixed_thumb_wh
            FROM $wpdb->abcficcolls c
            JOIN $wpdb->abcficimgs AS i ON i.coll_id = c.coll_id
            WHERE i.img_id = %d ", $imgID ));

    if($dbRow != null) { return $dbRow; }
    return array();
}

function abcfic_db_image_filenames ( $collID ) {

    if(is_int($collID)) {
        global $wpdb;
        $files = $wpdb->get_col("SELECT filename FROM $wpdb->abcficimgs WHERE coll_id = '$collID' ");
        if($files != null) { return $files; }
    }
    return array();
}

function abcfic_db_image_options( $imgID ) {

    global $wpdb;
    $dbRow = $wpdb->get_row( $wpdb->prepare(
    "SELECT c.colls_spath, c.colls_folder, c.coll_folder, c.img_resize, c.img_quality,
            c.max_img_w, c.max_img_h, c.fixed_img_wh, c.max_thumb_w, c.max_thumb_h,
            c.fixed_thumb_wh, c.thumb_quality, i.img_id, i.filename, i.img_w,
            i.img_h
    FROM $wpdb->abcficcolls c JOIN $wpdb->abcficimgs i ON (c.coll_id = i.coll_id)
    WHERE i.img_id = %d ", $imgID ));

    if($dbRow != null) { return $dbRow; }
    return array();
}

function abcfic_db_thumb_for_custom_resize( $imgID ) {

    global $wpdb;
    $dbRow = $wpdb->get_row( $wpdb->prepare(
        "SELECT c.coll_id, c.colls_spath, c.colls_folder, c.coll_folder, c.thumb_quality,
                i.filename
        FROM $wpdb->abcficcolls c JOIN $wpdb->abcficimgs i ON (c.coll_id = i.coll_id)
        WHERE i.img_id = %d ", $imgID ));

    if($dbRow != null) { return $dbRow; }
    return array();
}

function abcfic_db_get_images_by_ids($imgIDs ) {

    global $wpdb;
    $ids = implode(",", $imgIDs);
    return $wpdb->get_results( "SELECT img_id, filename FROM $wpdb->abcficimgs WHERE img_id IN ($ids)");
}

function abcfic_db_get_text_for_edit( $imgID ) {

   global $wpdb;
   $dbRow = $wpdb->get_row( $wpdb->prepare(
       "SELECT img_id, filename, alt, img_title, caption1
       FROM $wpdb->abcficimgs
       WHERE img_id = %d ", $imgID ), ARRAY_A);

    //var_dump( $wpdb->last_query );
   //print_r($dbRow); die;

    if($dbRow != null) { return $dbRow; }
    return array();
}

//--INSERT----------------------------------------------------------
function abcfic_db_add_collection( $collName, $collFolderName ) {

    global $user_ID, $wpdb;
    $dt = abcfic_db_dt_time();

    $opts = abcfic_options_get_optns();
    $collSPath = $opts['colls_spath'];
    $collsFolder = $opts['colls_folder'];
    $maxImgW = $opts['max_img_w'];
    $maxImgH = $opts['max_img_h'];
    $imgQuality = $opts['img_quality'];
    $imgResize = $opts['img_resize'];
    $clientSideResize = $opts['client_side_resize'];
    $uniqueFileName = $opts['unique_filename'];
    $fixedImgWH = $opts['fixed_img_wh'];
    $maxThumbW = $opts['max_thumb_w'];
    $maxThumbH = $opts['max_thumb_h'];
    $fixedThumbWH = $opts['fixed_thumb_wh'];
    $thumbQuality = $opts['thumb_quality'];
    $imgPublish = $opts['img_publish'];

    $rowsAffected = $wpdb->query(
        $wpdb->prepare("INSERT $wpdb->abcficcolls
            (coll_name, colls_spath, colls_folder, coll_folder, added_by,
            max_img_w, max_img_h, img_resize, client_side_resize, img_quality,
            unique_filename, fixed_img_wh, max_thumb_w, max_thumb_h, fixed_thumb_wh,
            thumb_quality, added_dt, updated_dt, img_publish)
            VALUES (%s, %s, %s, %s, %u,
                %u, %u, %u, %u, %u,
                %u, %u, %u, %u, %u,
                %u, %s, %s, %u)",
            $collName, $collSPath, $collsFolder, $collFolderName, $user_ID,
            $maxImgW, $maxImgH, $imgResize, $clientSideResize, $imgQuality,
            $uniqueFileName, $fixedImgWH, $maxThumbW, $maxThumbH, $fixedThumbWH,
            $thumbQuality, $dt, $dt, $imgPublish));

    //exit( var_dump( $wpdb->last_query ) );

    if($rowsAffected != 1) { return 0; }
    return (int)$wpdb->insert_id;
}


function abcfic_db_add_image($collID, $filename, $imgW, $imgH, $published ) {

    global $wpdb;
    $dt = abcfic_db_dt_time();
    $rowsAffected = $wpdb->query($wpdb->prepare(
            "INSERT $wpdb->abcficimgs (coll_id, filename, img_w, img_h, published, added_dt )
            VALUES (%u, %s, %u, %u, %u, %s)", $collID, $filename, $imgW, $imgH, $published, $dt ));

    if($rowsAffected != 1) { return 0; }
    return (int)$wpdb->insert_id;
}

 //--UPDATE----------------------------------------------------------
function abcfic_db_update_collection( $optns ) {

    global $wpdb;

    $rowsAffected = $wpdb->query(
    $wpdb->prepare(
        "UPDATE $wpdb->abcficcolls
        SET coll_name = %s,
            max_img_w = %d,
            max_img_h = %d,
            img_quality = %d,
            img_resize = %d,
            unique_filename = %d,
            fixed_img_wh = %d,
            img_publish = %d,
            max_thumb_w = %d,
            max_thumb_h = %d,
            thumb_quality = %d,
            fixed_thumb_wh = %d
        WHERE coll_id = %d",
        $optns['coll_name'],
        $optns['max_img_w'], $optns['max_img_h'], $optns['img_quality'], $optns['img_resize'],
        $optns['unique_filename'], $optns['fixed_img_wh'], $optns['img_publish'],
        $optns['max_thumb_w'], $optns['max_thumb_h'], $optns['thumb_quality'], $optns['fixed_thumb_wh'], $optns['coll_id']));

        if($rowsAffected != 1) { return 0;}
        return $rowsAffected;
}

function abcfic_db_update_collection_dt($collID) {

    global $wpdb;
    $dt = abcfic_db_dt_time();
    $wpdb->query(
    $wpdb->prepare(
        "UPDATE $wpdb->abcficcolls
        SET updated_dt = %s
        WHERE coll_id = %d", $dt, $collID ));

}

function abcfic_db_update_sort_order ( $imgID, $sortIndex ) {

    global $wpdb;
    $wpdb->query($wpdb->prepare( "UPDATE $wpdb->abcficimgs SET sort_order = %d WHERE img_id = %d AND sort_order != %d", $sortIndex, $imgID, $sortIndex) );
}

function abcfic_db_update_image_wh ( $imgID, $imgW, $imgH ) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->abcficimgs SET img_w = %u, img_h = %u WHERE img_id = %d", $imgW, $imgH, $imgID));
}

function abcfic_db_update_thumb_wh ( $imgID, $thumbW, $thumbH ) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->abcficimgs SET thumb_w = %u, thumb_h = %u WHERE img_id = %d", $thumbW, $thumbH, $imgID));
}

function abcfic_db_update_medium_wh ( $imgID, $mediumW, $mediumH ) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->abcficimgs SET medium_w = %u, medium_h = %u WHERE img_id = %d", $mediumW, $mediumH, $imgID));
}

function abcfic_db_update_published($imgIDs, $published ) {

   global $wpdb;
   $ids = implode(",", $imgIDs);
   if($published === 0) { $wpdb->query( "UPDATE $wpdb->abcficimgs SET published = 0, archived = 0, sort_order = 0 WHERE img_id IN ($ids)"); }
   else{ $wpdb->query( "UPDATE $wpdb->abcficimgs SET published = $published WHERE img_id IN ($ids)");}
}

function abcfic_db_update_coll_id($imgID, $collID) {
    global $wpdb;

    $rowsAffected = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->abcficimgs SET coll_id = %d, sort_order = 0, set_no = 0 WHERE img_id = %d", $collID, $imgID) );

    if($rowsAffected == 0) {$rowsAffected = 1;}
    if($rowsAffected == 1) {return $rowsAffected;}
    return 0;
}

 function abcfic_db_update_text($optns) {
    global $wpdb;
    $rowsAffected = 0;

    $imgID = (int) $optns['img_id'];
    $alt = trim($optns['alt']);
    $imgt = trim($optns['imgt']);
    $cap1 = trim($optns['cap1']);

    //Returns 0 or 1 if update OK. Otherwise returns empty
    if ( $imgID > 0){
        $rowsAffected = $wpdb->query(
        $wpdb->prepare(
            "UPDATE $wpdb->abcficimgs
            SET alt = %s,
                img_title = %s,
                caption1 = %s
            WHERE img_id = %d",
            $alt, $imgt, $cap1, $imgID));

            if($rowsAffected > -1) { $rowsAffected = 1;}
            else { $rowsAffected = 0; }
        }
   return $rowsAffected;
}

//--DELETE----------------------------------------------------------
function abcfic_db_delete_collection( $collID ) {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->abcficimgs WHERE coll_id = %d", $collID) );
    $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->abcficcolls WHERE coll_id = %d", $collID) );
}

function abcfic_db_delete_image( $imgID ) {
    global $wpdb;
    $rowsAffected = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->abcficimgs WHERE img_id = %d", $imgID) );

    if($rowsAffected == 1) { return 1;}
    else { return 0; }
}

//-----------------------------------------------------------------------
function abcfic_db_dt_time( ){
    return current_time( 'mysql' );
}

