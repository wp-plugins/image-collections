<?php
/*
 * TODO:
 */
function abcfic_pg_add_collection() {

    abcfic_cntbldr_permission_check();

    // if check_admin_referer() fails it will print a "Are you sure you want to do this?" page and die.
    if ( isset($_POST['btnAddColl']) ){
        check_admin_referer( 'abcfic_addcollection' );
        $collName = (isset( $_POST['collName'] ) ? esc_attr($_POST['collName']) : '');
        if(empty($collName)){  abcfic_msgs_error(49); }
        else {
            $coll = new ABCFIC_Collection();
            $coll->add_collection($collName);
            $collID = $coll->coll_id;
            if($collID > 0) {
                $url = admin_url('/admin.php?page=abcfic-collection-manger&mode=tabscoll&tab=options&collid=' . $collID);
                ?><script type="text/javascript">window.location = "<?php echo $url?>"</script><?php
            }
            else { abcfic_msgs_error(50); }
        }
    }
?>
<div class="wrap">
<div class="abcicIcon32"><br></div>
<h2><?php echo abcfic_inputbldr_txt(85);?></h2>
   <div class="clear"></div>
        <form name="frmAddColl" id="frm-add-collection" method="POST" accept-charset="utf-8" >
        <?php wp_nonce_field('abcfic_addcollection');
        echo abcfic_inputbldr_input_txt('collName', '', '', 1, 2, '50%', 'abcficCollTitle');?>
        <div class="submit">
            <?php echo abcfic_inputbldr_input_button( 'btnAddColl', 'btnAddColl', 'submit', 38, 'button-primary abcficBtnWide' );?>
        </div>
        </form>
    </div>
    <?php echo abcfic_inputbldr_hdivider502(); ?>
    <div>&nbsp;</div>
    <p><?php echo abcfic_inputbldr_txt(51);?></p>
    <p><a href="http://codex.wordpress.org/Changing_File_Permissions">http://codex.wordpress.org/Changing_File_Permissions</a></p>
</div>
<?php
}