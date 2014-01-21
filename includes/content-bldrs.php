<?php
/*
 *TODO:
 */
function abcfic_cntbldr_tbl_file_box($item) {

    $imgID = $item['img_id'];
    $filename = $item['filename'];
    $imgW = $item['img_w'];
    $imgH = $item['img_h'];
    $thumbW = $item['thumb_w'];
    $thumbH = $item['thumb_h'];

    $maxThumbW = $item['max_thumb_w'];
    $maxThumbH = $item['max_thumb_h'];

    $dispalyCT = true;

    if($maxThumbW == 0 || $maxThumbH == 0) { $dispalyCT = false; }

    $dt = date("Y-m-d", strtotime($item['added_dt']));
    $title = $imgW . 'x' . $imgH;
    $href = abcfic_optns_full_url($item['colls_spath'], $item['colls_folder'], $item['coll_folder'], $filename);
    $imgTxt = '<a href="#" onclick="imgTextDialog(' . $imgID . '); return false;">' . abcfic_inputbldr_txt(42) . '</a>';

    $imgName = '<strong><a class="shutter" href="' . $href . '" title="' . $title . '">' . $filename . '</a></strong>';
    $dt = '<br/>' . $imgW . 'x' . $imgH . '<span><b>&nbsp;|&nbsp;</b></span>' . $thumbW . 'x' . $thumbH . '<span><b>&nbsp;|&nbsp;</b></span>'. $dt;
    $actions = array(
        'edit_text' => $imgTxt
    );

    return array('fileName' => $imgName, 'dt' => $dt, 'actions' => $actions);

}

function abcfic_cntbldr_tbl_thumb($item) {

    $thumbFldrUrl = abcfic_optns_full_url($item['colls_spath'], $item['colls_folder'], $item['coll_folder'], 'thumbs', $item['filename']);
    $thumbUrl = esc_url( add_query_arg('i', mt_rand(), $thumbFldrUrl) );
    $title = $item['thumb_w'] . 'x' . $item['thumb_h'];
    return '<a id="athumb' . $item['img_id'] . '" class="shutter" href="' . $thumbUrl . '" title="' . $title . '" ><img id="thumb' . $item['img_id'] . '" class="thumb" src="' . $thumbUrl . '" /></a>';
}

function abcfic_cntbldr_tbl_thumb2($item) {

    $filename = $item['filename'];
    $imgUrl = esc_url( abcfic_optns_full_url($item['colls_spath'], $item['colls_folder'], $item['coll_folder'], $filename) );
    $thumb2Url = esc_url( abcfic_optns_full_url($item['colls_spath'], $item['colls_folder'], $item['coll_folder'], 'thumbs2', $filename ) );
    $title = "- " . $filename . " -" ;

    return '<a class="shutter" href="' . $imgUrl . '" title="' . $title . '" ><img id="thumb2' . $item['img_id'] . '" class="thumb" src="' . $thumb2Url . '" /></a>';
}

function abcfic_cntbldr_tbl_txt_box($item) {

    $imgID = $item['img_id'];

    $alt = abcfic_inputbldr_tbl_lbl_substr(10, stripslashes($item['alt']), 50, ':');
    $img_title = abcfic_inputbldr_tbl_lbl_substr(24, stripslashes($item['img_title']), 50, ':');
    $caption1 = abcfic_inputbldr_tbl_lbl_substr(34, stripslashes($item['caption1']), 50, '1:');

    $txtCntrS = '<div id="txtCntr' . $imgID . '" class="abcficTblTxtCntr">';
    $txtCntrE = '</div>';
    return $txtCntrS . $alt . $img_title . $caption1 . $txtCntrE;
}

function abcfic_cntbldr_tbl_txt_box_lnk($href, $targetID, $len) {

    if(empty($href)){return array('lnk'=>'', 'target'=>'');}

    $href = esc_url($href);
    $lnkTxt = $href;
    if(strlen($href) > $len){ $lnkTxt = substr($href, 0, $len) . "..."; }

    $target = '';
    $targetTxt = abcfic_inputbldr_txt(36);
    if($targetID == 1) {
        $targetTxt = abcfic_inputbldr_txt(37);
        $target = ' target="_blank"';
    }
    $lnk = '<a href="' . $href . '"' . $target . '>' . $lnkTxt . '</a>';

    return array('lnk'=>$lnk, 'target'=>$targetTxt);
}

function abcfic_cntbldr_tbl_headers() {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'rno' => '#',
        'img_id' => 'ID',
        'sort_order' => abcfic_inputbldr_txt(43),
        'set_no' => abcfic_inputbldr_txt(44),
        'thumb' => abcfic_inputbldr_txt(45),
        'thumb2' => abcfic_inputbldr_txt(21),
        'filename' => abcfic_inputbldr_txt(46),
        'txt' => abcfic_inputbldr_txt(47)
    );
    return $columns;
}

function abcfic_cntbldr_tbl_sortable_cols() {
    $sortable_columns = array(
        'img_id' => array('img_id', true),
        'sort_order' => array('sort_order', true),
        'set_no' => array('set_no', true),
        'filename' => array('filename', true)
    );
    return $sortable_columns;
}

function abcfic_cbldrs_bulk_dialog( $collID, $suffix, $bulkActionType, $cbo=false) {

    $nonce = wp_nonce_field('abcfic_modal_form');

    $out = '<div id="div' . $suffix . '" style="display: none;" >';
    $out .= '<form id="frm' . $suffix . '" method="POST" accept-charset="utf-8">';
    $out .= $nonce;
    $out .= abcfic_inputbldr_input_hidden('', 'page', 'bulk-process-imgs');
    $out .= abcfic_inputbldr_input_hidden('', 'bulkActionType', $bulkActionType);
    $out .= abcfic_inputbldr_input_hidden('lst' . $suffix, 'lst' . $suffix, '');
    $out .= abcfic_inputbldr_input_hidden('collID', 'collID', $collID);
    $out .= '<div id="txt' . $suffix . '" class="abcficBMMsg"></div>';
    if($cbo) {
        $cboCollections = abcfic_db_cbo_bulk_colls( $collID );
        $out .= abcfic_inputbldr_input_cbo('collID_Dest', '', $cboCollections, '', 33, 0, '100%');
    }
    $out .= '<div class="abcficBMBtns">';
    $out .= abcfic_inputbldr_input_button( 'submit' . $suffix, '', 'submit', 31, 'button-primary' );
    $out .= '&nbsp;';
    $out .= abcfic_inputbldr_input_button( 'cancel' . $suffix, '', 'reset', 32, 'button-secondary dialog-cancel' );
    $out .= '</div></form></div>';
    return $out;
}

function abcfic_cntbldr_bulk_actions_frm($currAction){

    if (empty($currAction)) { return;}
    $i = 0;
    $imgLst = '';

    if (!isset($_POST['abcfictblimgss'])) {
        </script><?php?><script>alert('No images selected');</script><?php
        return;
    } else {
        foreach ($_POST['abcfictblimgss'] as $image) {
            $i++;
            $imgLst .= $image . ',';
        }
        $imgLst = rtrim($imgLst, ',');
    }

    switch ($currAction) {
        case 'delete_images' :
            $title = 'Delete images';
            echo(abcfic_cntbldr_js_bulk_actions('DeleteImgs', $title, $title . '? : ' . $i, $imgLst));
            break;

        default:
            break;
    }
}

function abcfic_cntbldr_js_bulk_actions( $suffix,  $title, $msg, $imgLst, $w=350, $h=140 ){
    $out = '<script>';
    $out .= 'jQuery(document).ready(function($) {';
    $out .= '$("#txt' . $suffix . '").html("' . $msg . '");';
    $out .= '$("#lst' . $suffix . '").val("' . $imgLst . '");';
    $out .= '$("#div' . $suffix . '").dialog({';
    $out .= 'width:' . $w . ', height:' . $h . ', resizable : false, modal: true, title: "' . $title .'" });';
    $out .= '$("#cancel' . $suffix . '").click(function() { $( "#div' . $suffix . '" ).dialog("close"); });});';
    $out .= '</script>';

    return $out;
}

function abcfic_cntbldr_coll_tbl( $collID, $collsTbl, $type ) {

    $collsTbl->prepare_items(); ?>
    <div class="wrap">
        <h3>Collection: <?php echo abcfic_db_collection_name( $collID ); ?></h3>
        <div id="spinn" class="spinn" style="display:none;">
            <img src="<?php echo(ABCFIC_PLUGIN_URL . 'images/spinner.gif');?>" alt="Loading..."/>
        </div>
        <!-- Forms are NOT created automatically, so we need to wrap the table in one to use features like bulk actions -->
        <form id="abcficFrmImgs" method="POST" >
            <!-- For plugins, we need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $collsTbl->display() ?>
        </form>
    </div>
    <?php

    echo abcfic_cbldrs_bulk_dialog( $collID, 'DeleteImgs', 'delete_images');
    echo abcfic_dialog_img_text();
}

function abcfic_cntbldr_permission_check() {
    if ( !current_user_can('upload_files')){
        echo abcfic_msgs_error(90);
        die();
    }
}