<?php
function abcfic_default_options() {

    abcfic_cntbldr_permission_check();

    if ( isset($_POST['btnUpdateDefaultOptions']) ){
        check_admin_referer( 'abcfic_defaultoptions' );
        abcfic_update_abcfic_options($_POST);
        abcfic_msgs_ok();
    }

    $opts = abcfic_options_get_optns();
    ?>
    <div class="wrap">
    <div class="abcicIcon32"><br></div>
    <h2><?php echo abcfic_inputbldr_txt(88);?></h2><div class="clear"></div>
    <p>&nbsp;</p>
    <?php echo abcfic_inputbldr_hlp_data( 23, '<b>' . $opts['collsQPath'] . '</b>', 12);?>

    <form id="frm-default-options" method="POST" accept-charset="utf-8">
    <?php wp_nonce_field('abcfic_defaultoptions');

    if ($opts) {
        $cboYN = abcfic_cbos_yn();
        $cboQuality = abcfic_cbos_quality();

        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 20, true );
        echo abcfic_inputbldr_input_cbo('unique_filename', '', $cboYN, $opts['unique_filename'], 3, 16, "30%");
        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 21 );
        echo abcfic_inputbldr_hlp_top(15);
        echo abcfic_inputbldr_input_cbo('img_resize', '', $cboYN, $opts['img_resize'], 4, 0, "30%");
        echo abcfic_iputbldr_floats_cntr_s();
        echo abcfic_inputbldr_input_txt('max_img_w', '', $opts['max_img_w'], 11, 0, '100%', '', '', 'abcficImgW');
        echo abcfic_inputbldr_input_txt('max_img_h', '', $opts['max_img_h'], 12, 0, '100%', '', '', 'abcficImgH');
        echo abcfic_inputbldr_input_cbo('img_quality', '', $cboQuality, $opts['img_quality'], 13, 0, "100%", true, '', '', 'abcficImgQ');
        echo abcfic_iputbldr_floats_cntr_e();
        echo abcfic_inputbldr_hlp_under(14);
        echo abcfic_inputbldr_input_cbo('fixed_img_wh', '', $cboYN, $opts['fixed_img_wh'], 7, 8, "30%");
        echo abcfic_inputbldr_hdivider502();
        echo abcfic_inputbldr_section_header( 22, true );
        echo abcfic_iputbldr_floats_cntr_s();
        echo abcfic_inputbldr_input_txt('max_thumb_w', '', $opts['max_thumb_w'], 11, 0, '100%', '', '', 'abcficImgW');
        echo abcfic_inputbldr_input_txt('max_thumb_h', '', $opts['max_thumb_h'], 12, 0, '100%', '', '', 'abcficImgH');
        echo abcfic_inputbldr_input_cbo('thumb_quality', '', $cboQuality, $opts['thumb_quality'], 13, 0, "100%", true, '', '', 'abcficImgQ');
        echo abcfic_iputbldr_floats_cntr_e();
        echo abcfic_inputbldr_hlp_under(14);
        echo abcfic_inputbldr_input_cbo('fixed_thumb_wh', '', $cboYN, $opts['fixed_thumb_wh'], 7, 8, "30%");

        echo abcfic_inputbldr_hdivider502();
        ?><div class="submit"><?php echo abcfic_inputbldr_input_button( 'btnUpdateDefaultOptions', 'btnUpdateDefaultOptions', 'submit', 38, 'button-primary abcficBtnWide' );?></div>
        </form>
    </div><?php
    }
}