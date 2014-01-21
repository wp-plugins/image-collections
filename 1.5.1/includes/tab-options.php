<?php
/*
 *TODO:
 */
function abcfic_tab_options( $collID ) {

    abcfic_cntbldr_permission_check();
    $objColl = new ABCFIC_Collection();

    if ( isset($_POST['btnUpdateColl']) ){
        check_admin_referer( 'abcfic_colloptions' );
        $objColl->update_collection($_POST);
        if($objColl->coll_id > 0){ abcfic_msgs_ok(); }
        //else {abcfic_msgs_error(55);}
    }

    $objColl->get_collection($collID);
    if($objColl->coll_id == 0) {
        echo abcfic_msgs_error(55);
        return;
    }?>

    <form id="frm-coll-options" method="POST" accept-charset="utf-8">
        <?php wp_nonce_field('abcfic_colloptions');

        $cboYN = abcfic_cbos_yn();
        $cboQuality = abcfic_cbos_quality();

        echo abcfic_inputbldr_input_txt('coll_name', '', $objColl->coll_name, 1, 2, '50%', 'abcficCollTitle');
        echo abcfic_inputbldr_hlp_data( 6, $objColl->collQPath );
        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 20, true );
        echo abcfic_inputbldr_input_cbo('unique_filename', '', $cboYN, $objColl->unique_filename, 3, 16, '30%');
        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 21 );
        echo abcfic_inputbldr_hlp_top(15);
        echo abcfic_inputbldr_input_cbo('img_resize', '', $cboYN, $objColl->img_resize, 4, 0, "30%");

        echo abcfic_iputbldr_floats_cntr_s();
        echo abcfic_inputbldr_input_txt ('max_img_w', '',$objColl->max_img_w, 11, 0, '100%', '', '', 'abcficImgW');
        echo abcfic_inputbldr_input_txt ('max_img_h', '',$objColl->max_img_h, 12, 0, '100%', '', '', 'abcficImgH');
        echo abcfic_inputbldr_input_cbo('img_quality', '', $cboQuality, $objColl->img_quality, 13, 0, '100%', true, '', '', 'abcficImgQ');
        echo abcfic_iputbldr_floats_cntr_e();
        echo abcfic_inputbldr_hlp_under(14);

        echo abcfic_inputbldr_input_cbo('fixed_img_wh', '', $cboYN, $objColl->fixed_img_wh, 7, 8, "30%");

        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 22, true );
        echo abcfic_iputbldr_floats_cntr_s();
        echo abcfic_inputbldr_input_txt ('max_thumb_w', '',$objColl->max_thumb_w, 11, 0, '100%', '', '', 'abcficImgW');
        echo abcfic_inputbldr_input_txt ('max_thumb_h', '',$objColl->max_thumb_h, 12, 0, '100%', '', '', 'abcficImgH');
        echo abcfic_inputbldr_input_cbo('thumb_quality', '', $cboQuality, $objColl->thumb_quality, 13, 0, '100%', true, '', '', 'abcficImgQ');
        echo abcfic_iputbldr_floats_cntr_e();
        echo abcfic_inputbldr_hlp_under(14);
        echo abcfic_inputbldr_input_cbo('fixed_thumb_wh', '', $cboYN, $objColl->fixed_thumb_wh, 7, 8, '30%');

        echo abcfic_inputbldr_input_hidden( '', 'coll_id', $objColl->coll_id, true );
        echo abcfic_inputbldr_hdivider502();
        ?>
        <div class="submit"><?php echo abcfic_inputbldr_input_button( 'btnUpdateColl', 'btnUpdateColl', 'submit', 38, 'button-primary abcficBtnWide' );?></div>
        </form><?php
}

