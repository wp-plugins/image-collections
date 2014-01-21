<?php
/*
 *TODO: Replace with jQuery UI Sortable
 */
function abcfic_tab_sort($collID = 0){

    abcfic_cntbldr_permission_check();

    if ( isset($_POST['btnSortColl']) ){ $collID = esc_attr( $_POST['coll_id']);}
    if ($collID == 0) { return; }
    $collID = (int) $collID;

    if (isset ($_POST['updateSortOrder']))  {
        check_admin_referer( 'abcfic_updatesortorder' );

        parse_str($_POST['sortorder']);
        //$sortArray is created and populated in sorter.js
        if (is_array($sortArray)){
            $newOrder = array();

            foreach($sortArray as $imgID) {
                $imgID = substr($imgID, 7); // get id from "img_id-x"
                if((int) $imgID > 0){ $newOrder[] = (int) $imgID;}
            }

            $sortIndex = 1;
            foreach($newOrder as $imgID) {
                abcfic_db_update_sort_order ( $imgID, $sortIndex );
                $sortIndex++;
            }
            abcfic_msgs_ok();
        }
    }

    $presort = isset($_GET['presort']) ? $_GET['presort'] : 'sort_order';
    $dir = ( isset($_GET['dir']) && $_GET['dir'] == 'DESC' ) ? 'DESC' : 'ASC';
    $thumbs = abcfic_db_imgs_for_sort($collID, $presort, $dir, false);

    //this is the url without any presort variable
    $clean_url = 'admin.php?page=abcfic-collection-manger&amp;mode=tabscoll&tab=sort&amp;collid=' . $collID;

    // In the case somebody presort, then we take this url
    if ( isset($_GET['dir']) || isset($_GET['presort']) ){  $base_url = $_SERVER['REQUEST_URI']; }
    else{ $base_url = $clean_url; }
?>

<div class="wrap abcficSortImgsPg">
    <form id="frmSortImgs" method="POST" action="<?php echo $clean_url ?>" onsubmit="saveImageOrder()" accept-charset="utf-8">
         <?php wp_nonce_field('abcfic_updatesortorder') ?>
        <input name="sortorder" type="hidden" />
        <div class="abcficUSortBtnCntr"><?php echo abcfic_inputbldr_input_button( 'updateSortOrder', 'updateSortOrder', 'submit', 91, 'button button-primary', 'saveImageOrder()' );?></div>
        <ul class="subsubsub">
            <li>Sort By :</li>
            <li><a href="<?php echo esc_attr(add_query_arg('presort', 'sort_order', $base_url)); ?>" <?php if ($presort == 'sort_order') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(61);?></a> |</li>
            <li><a href="<?php echo esc_attr(add_query_arg('presort', 'img_id', $base_url)); ?>" <?php if ($presort == 'img_id') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(63);?></a> |</li>
            <li><a href="<?php echo esc_attr(add_query_arg('presort', 'filename', $base_url)); ?>" <?php if ($presort == 'filename') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(62);?></a> |</li>
            <li><a href="<?php echo esc_attr(add_query_arg('presort', 'added_dt', $base_url)); ?>" <?php if ($presort == 'added_dt') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(64);?></a> |</li>
            <li><a href="<?php echo esc_attr(add_query_arg('dir', 'ASC', $base_url)); ?>" <?php if ($dir == 'ASC') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(65);?></a> |</li>
            <li><a href="<?php echo esc_attr(add_query_arg('dir', 'DESC', $base_url)); ?>" <?php if ($dir == 'DESC') echo 'class="current"'; ?>><?php echo abcfic_inputbldr_txt(66);?></a></li>
       </ul>

<div class="abcficClr"></div>
    </form>
    <div id="debug" style="clear:both"></div>
    <div id="abcficSortCntr" class="abcficSortImgsCntr">
        <?php if($thumbs) { foreach($thumbs as $key => $value) {  ?>
        <div class="imageBox" id="img_id-<?php echo $key ?>">
            <div class="imageBox_theImage" style="background-image:url('<?php echo esc_url( $value ); ?>')"></div>
        </div>
            <?php
            }
        }
        ?>
    <div class="abcficClr"></div>
    </div>
    <div id="insertionMarker">
    <img src="<?php echo ABCFIC_PLUGIN_URL . 'images/marker_top.gif'?>"/>
    <img src="<?php echo ABCFIC_PLUGIN_URL . 'images/marker_middle.gif'?>" id="insertionMarkerLine"/>
    <img src="<?php echo ABCFIC_PLUGIN_URL . 'images/marker_bottom.gif'?>"/>
    </div>
    <div id="dragDropContent"></div>
</div>
<?php
}