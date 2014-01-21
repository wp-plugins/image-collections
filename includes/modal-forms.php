<?php
//Image text dialog form
function abcfic_dialog_img_text() {
    $nonce = wp_create_nonce('abcfic_ajax_nounce');
?>
    <script type="text/javascript">
        jQuery(document).ready(function(e){window.imgTextDialog=function(t){e.ajax({url:ajaxurl,type:"POST",dataType:"json",data:{action:"abcfic_get_img_text",abcfic_nounce:"<?php echo $nonce; ?>",img_id:t},cache:false,success:function(n){if(!n.error){e("#imgID").val(t);e("#alt").val(n.alt);e("#imgt").val(n.img_title);e("#cap1").val(n.cap1);e("#cap2").val(n.cap2);e("#cap3").val(n.cap3);e("#cap4").val(n.cap4);e("#href1").val(n.href1);e("#hreft1").val(n.hreft1);e("#href2").val(n.href2);e("#hreft2").val(n.hreft2);e("#setno").val(n.setno);e("#description").val(n.description);e("#frmImgTxtCntr").dialog({modal:true,width:600,height:"auto",resizable:true,title:t}).dialog("open")}else{alert(n.msg)}},error:function(){alert("Error in ajax call!")}});return false};window.imgTextSave=function(){var t=e("#imgID").val()?e("#imgID").val():"0";var n=e("#frmImgTxtCntr #alt").val()?e("#frmImgTxtCntr #alt").val():"";var r=e("#frmImgTxtCntr #imgt").val()?e("#frmImgTxtCntr #imgt").val():"";var i=e("#frmImgTxtCntr #cap1").val()?e("#frmImgTxtCntr #cap1").val():"";e.ajax({url:ajaxurl,type:"POST",dataType:"json",data:{action:"abcfic_update_img_text",abcfic_nounce:"<?php echo $nonce; ?>",img_id:t,alt:n,imgt:r,cap1:i},cache:false,success:function(n){if(!n.error){e("#txtCntr"+t).replaceWith(n.html);e("#frmImgTxtCntr").dialog("close")}else{alert(n.error_msg)}},error:function(){alert("Error in ajax call!")}})}})
    </script>

<div id="frmImgTxtCntr" class="abcfidHide">
    <fieldset>
        <div class="abcficMTxtHdr">
        </div>
        <div class="abcficFrmTDivider"></div><?php
        $clsCntr = 'abcficMFldCntr';
        $clsLbl = 'abcficMFldLbl';
        $cls = 'ui-corner-all';
        echo abcfic_inputbldr_input_txt('alt', '', '', 10, 0, '100%', $cls, '', $clsCntr, $clsLbl);
        echo abcfic_inputbldr_input_txt('imgt', '', '', 24, 0, '100%', $cls, '', $clsCntr, $clsLbl);
        echo abcfic_inputbldr_input_txt('cap1', '', '', 26, 0, '100%', $cls, '', $clsCntr, $clsLbl, ' 1');
        echo abcfic_inputbldr_input_hidden('imgID', 'imgID', '', true );?>
    </fieldset>
    <div class="abcficMBtnsCntr"><?php
        echo abcfic_inputbldr_input_button( 'btnSave', '', 'button', 38, 'button-primary abcficBtnRM', 'imgTextSave();' );?>
    </div>
</div>
<?php
}