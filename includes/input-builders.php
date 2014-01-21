<?php
/*
 *TODO:
 */
function abcfic_inputbldr_txt($id, $suffix='') {

    switch ($id){
        case 0:
            $out = '';
            break;
        case 1:
            $out = __('Collection Name', 'abcfic-td');
            break;
        case 2:
            $out = __('Allowed characters: <strong>a-z, A-Z, 0-9, -, _ </strong>', 'abcfic-td');
            break;
        case 3:
            $out = __('Unique File Names', 'abcfic-td');
            break;
        case 4:
            $out = __('Resize Images on Upload', 'abcfic-td');
            break;
        case 5:
            $out = __('', 'abcfic-td');
            break;
        case 6:
            $out = __('Collection folder: ', 'abcfic-td');
            break;
        case 7:
            $out = __('Fixed Dimensions', 'abcfic-td');
             break;
        case 8:
            $out = __('<b>Fixed dimensions</b> = all images have identical width and height.', 'abcfic-td');
            break;
        case 9:
            $out = __('', 'abcfic-td');
            break;
        case 10:
            $out = __('Alt', 'abcfic-td');
            break;
        case 11:
            $out = __('Width', 'abcfic-td');
            break;
        case 12:
            $out = __('Height', 'abcfic-td');
            break;
        case 13:
            $out = __('Quality', 'abcfic-td');
            break;
        case 14:
            $out = __('<b>Max</b> image dimensions in pixels.', 'abcfic-td');
            break;
        case 15:
            $out = __('All settings in this section are disregarded when <b>Resize Images</b> = <b>No!</b>', 'abcfic-td');
            break;
        case 16:
            $out = __('<b>No</b> = Upload all. Rename duplicates. <b>Yes</b> = Skip duplicates.', 'abcfic-td');
            break;
        case 17:
            $out = __('Images resized on the server tend to have better quality but require large amount of the server"s memory.', 'abcfic-td');
            break;
       case 18:
            $out = __('Server resize is NOT recomended for large files, large number of files or shared hosting accounts.', 'abcfic-td');
            break;
       case 19:
            $out = __('Thumbnails are auto generated during the upload. Later you can custom crop or change dimension.', 'abcfic-td');
            break;
       case 20:
            $out = __('Upload Options', 'abcfic-td');
            break;
       case 21:
            $out = __('Images', 'abcfic-td');
            break;
       case 22:
            $out = __('Thumbnails', 'abcfic-td');
            break;
       case 23:
            $out = __('Collections Folder: ', 'abcfic-td');
            break;
       case 24:
            $out = __('Title', 'abcfic-td');
            break;
       case 25:
            $out = __('', 'abcfic-td');
            break;
       case 26:
            $out = __('Caption', 'abcfic-td');
            break;
       case 27:
            $out = __('', 'abcfic-td');
            break;
       case 28:
            $out = __('', 'abcfic-td');
            break;
       case 29:
            $out = __('', 'abcfic-td');
            break;
       case 30:
            $out = __('', 'abcfic-td');
            break;
        case 31:
            $out = __('OK', 'abcftl-td');
            break;
        case 32:
            $out = __('Cancel', 'abcftl-td');
            break;
        case 33:
            $out = __('', 'abcfic-td');
            break;
        case 34:
            $out = __('C', 'abcfic-td');
            break;
        case 35:
            $out = __('', 'abcfic-td');
            break;
        case 36:
            $out = __('Current tab', 'abcfic-td');
            break;
        case 37:
            $out = __('New tab', 'abcfic-td');
            break;
        case 38:
            $out = __('Save', 'abcfic-td');
            break;
        case 39:
            $out = __('Cancel', 'abcfic-td');
            break;
        case 40:
            $out = __('Preview', 'abcfic-td');
            break;
        case 41:
            $out = __('Edit Thumb', 'abcfic-td');
            break;
        case 42:
            $out = __('Edit Text', 'abcfic-td');
            break;
        case 43:
            $out = __('Sort', 'abcfic-td');
            break;
        case 44:
            $out = __('Set', 'abcfic-td');
            break;
        case 45:
            $out = __('Thumbs', 'abcfic-td');
            break;
        case 46:
            $out = __('Files', 'abcfic-td');
            break;
        case 47:
            $out = __('Text', 'abcfic-td');
            break;
        case 48:
            $out = __('Unable to create directory: ', 'abcfic-td');
            break;
        case 49:
            $out = __('Collection name is required', 'abcfic-td');
            break;
        case 50:
            $out = __('Error: Collection not created!', 'abcfic-td');
            break;
        case 51:
            $out = __('File or folder permission issues?', 'abcfic-td');
            break;
        case 52:
            $out = __('Directory is not writeable: ', 'abcfic-td');
            break;
        case 53:
            $out = __('Collection name is invalid! Try another one.', 'abcfic-td');
            break;
        case 54:
            $out = __('Cheatin&#8217; uh?', 'abcfic-td');
            break;
        case 55:
            $out = __('Error: Collection not found.', 'abcfic-td');
            break;
        case 56:
            $out = __('Error: Collection folder not deleted: ', 'abcfic-td');
            break;
        case 57:
            $out = __('Upload failed.', 'abcfic-td');
            break;
        case 58:
            $out = __('No collection selected', 'abcfic-td');
            break;
        case 59:
            $out = __('Collection ID is missing', 'abcfic-td');
            break;
        case 60:
            $out = __('Error: Upload Failed. There are no images in collection folder: ', 'abcfic-td');
            break;
        case 61:
            $out = __('Sort Order', 'abcfic-td');
            break;
        case 62:
            $out = __('Filename', 'abcfic-td');
            break;
        case 63:
            $out = __('Image ID', 'abcfic-td');
            break;
        case 64:
            $out = __('Date', 'abcfic-td');
            break;
        case 65:
            $out = __('Ascending', 'abcfic-td');
            break;
        case 66:
            $out = __('Descending', 'abcfic-td');
            break;
        case 67:
            $out = __('Collections info is missing!', 'abcfic-td');
            break;
        case 68:
            $out = __('There are no files to process.', 'abcfic-td');
            break;
        case 69:
            $out = __('Unable to write to directory: ', 'abcfic-td');
            break;
        case 70:
            $out = __('Error! Path not changed.', 'abcfic-td');
            break;
        case 71:
            $out = __('Select Files', 'abcfic-td');
            break;
        case 72:
            $out = __('Invalid file: ', 'abcfic-td');
            break;
        case 73:
            $out = __('', 'abcfic-td');
            break;
        case 74:
            $out = __('File already exists: ', 'abcfic-td');
            break;
        case 76:
            $out = __('Error, the file could not be moved to:', 'abcfic-td');
            break;
        case 77:
            $out = __('Error, file permissions could not be set.', 'abcfic-td');
            break;
        case 78:
            $out = __('Uninstall', 'abcfic-td');
            break;
        case 79:
            $out = __('Unpublish images', 'abcfic-td');
            break;
        case 80:
            $out = __('Archive images', 'abcfic-td');
            break;
        case 81:
            $out = __('Delete images', 'abcfic-td');
            break;
        case 82:
            $out = __('Recreate thumbnails', 'abcfic-td');
            break;
        case 83:
            $out = __('Unarchive images', 'abcfic-td');
            break;
        case 84:
            $out = __('Publish images', 'abcfic-td');
            break;
        case 85:
            $out = __('Add Image Collection', 'abcfic-td');
            break;
        case 86:
            $out = __('Image Collections', 'abcfic-td');
            break;
        case 87:
            $out = __('Add New', 'abcfic-td');
            break;
        case 88:
            $out = __('Default Options', 'abcfic-td');
            break;
        case 89:
            $out = __('', 'abcfic-td');
            break;
        case 90:
            $out = __('You have no perrmision to upload files.', 'abcfic-td');
            break;
        case 91:
            $out = __('Update Sort Order', 'abcfic-td');
            break;
        case 92:
            $out = __('Yes', 'abcfic-td');
            break;
        case 93:
            $out = __('No', 'abcfic-td');
            break;
        case 94:
            $out = __('Ddelete all collections (Step 1) before deactivating and deleting the plugin.', 'abcfic-td');
            break;
        case 95:
            $out = __('Custom data tables will be removed only when there are no collections in the database.', 'abcfic-td');
            break;
        case 96:
            $out = __('Collection name already exists! Try another name.', 'abcfic-td');
            break;
       case 97:
            $out = __('Select Collection', 'abcfic-td');
            break;
        default:
            $out = '';
            break;
    }
    return $out . $suffix;

}

function abcfic_inputbldr_ok_file_types() { return array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG'); }

function abcfic_inputbldr_ok_file_types_list() {
    $list = implode(',', abcfic_inputbldr_ok_file_types());
    return rtrim($list, ',');
}
//==Messages===========================================================
function abcfic_msgs_error($id, $suffix='') { echo '<div class="wrap"><div class="error" id="error"><p>' . abcfic_inputbldr_txt($id, $suffix) . '</p></div></div>'; }

function abcfic_msgs_info($id, $suffix='') { echo '<div class="wrap"><div class="updated fade" id="message"><p>' . abcfic_inputbldr_txt($id, $suffix) . '</p></div></div>' . "\n"; }

function abcfic_msgs_ok() {
    echo '<div class="wrap"><div id="abcficOK" class="updated" style="line-height: 1px;"><img src="'  . ABCFIC_PLUGIN_URL .  'images/msgok_32x32.png"></div></div>';
}
//===DIV Builders=======================================================================
function abcfic_inputbldr_hdivider() { return "<div class=\"abcficHDivider\">&nbsp;</div>"; }
function abcfic_inputbldr_hdivider2() { return "<div class=\"abcficHDivider2\">&nbsp;</div>"; }
function abcfic_inputbldr_hdivider502() { return "<div class=\"abcficHDivider502\">&nbsp;</div>"; }

function abcfic_iputbldr_floats_cntr_s(){ return '<div class="abcfFloatsCntr">'; }
function abcfic_iputbldr_floats_cntr_e(){ return '<div class="abcficClr"></div></div>'; }
function abcfic_iputbldr_clr(){ return '<div class="abcficClr"></div>'; }

//===INPUTS=======================================================================
//abcfic_input_cbo($field_id, $fld_name, $values, $selected, $lbl, $hlp='', $size='', $cls='', $style='', $cls_cntr='', $cls_lbl='')
function abcfic_inputbldr_input_cbo($fldID, $fldName, $values, $selected, $lblID=0, $hlpID=0, $size='', $isInt=true, $cls='', $style='',  $clsCntr='', $clsLbl='', $lblSuffix = '') {

    $optns = abcfic_inputbldr_input_options( $fldID, $fldName, $lblID, $hlpID, $size, $cls, $style, $clsCntr, $clsLbl, $lblSuffix, $values, $selected );
    extract( $optns );

    return  $divs . $lbl . '</div><select id="' . $fldID . '" type="text"' . $cls . $style . ' name="' . $fldName . '" >' . $options . '</select>' . $hlp . '</div>';
}

function abcfic_inputbldr_input_txt($fldID, $fldName, $fldValue, $lblID, $hlpID, $size='', $cls='', $style='',  $clsCntr='', $clsLbl='', $lblSuffix = ''){

    $optns = abcfic_inputbldr_input_options( $fldID, $fldName, $lblID, $hlpID, $size, $cls, $style, $clsCntr, $clsLbl, $lblSuffix );
    extract( $optns );
    return  $divs . $lbl . '</div><input id="' . $fldID . '" type="text"' . $cls . $style . 'name="' . $fldName . '" value="' . $fldValue . '" />' . $hlp . '</div>';
}

function abcfic_inputbldr_input_txtarea($fldID, $fldName, $fldValue, $lblID, $hlpID, $size='', $rows='4', $cols = '10', $cls='', $style='', $clsCntr='', $clsLbl='', $lblSuffix = '')
{
    $optns = abcfic_inputbldr_input_options( $fldID, $fldName, $lblID, $hlpID, $size, $cls, $style, $clsCntr, $clsLbl, $lblSuffix );
    extract( $optns );

    return  $divs . $lbl . '</div>' . '<textarea rows="' . $rows . '" cols="' . $cols . '" id="' .$fldID . '"' . $cls . $style . ' name="' . $fldName . '">' . $fldValue . '</textarea>'. $hlp . '</div>';

}

function abcfic_inputbldr_input_hidden( $fldID, $fldName, $fldValue, $renderIfBlank=true ){

    if( abcfic_lib_isblank($fldValue) &&  !$renderIfBlank ) { return ''; }
    $id = '';
    if(!abcfic_lib_isblank($fldID)) { $id = ' id="' . $fldID . '"'; }

    return '<input type="hidden"' . $id . ' name="' . $fldName . '" value="' . $fldValue . '" />';
}

function abcfic_inputbldr_input_button( $fldID, $fldName, $type, $lblID, $cls='', $onClick='' ){

    if(!empty($onClick)) {$onClick = 'onclick="' . $onClick . '"'; }
    $fldID = abcfic_inputbldr_id($fldID);
    $fldName = abcfic_inputbldr_name($fldName);
    $lblID = abcfic_inputbldr_txt($lblID);

    return '<input type="' . $type . '" class="' . $cls . '"' . $fldID . $fldName .' value="' . $lblID . '"' . $onClick . ' />';

}

//====Table Labels & Inputs=======================================================
 function abcfic_inputbldr_tbl_lbl($lbl, $value, $lblSuffix='') {

    if( abcfic_lib_isblank($value) ){ return ''; }
    $lbl = abcfic_inputbldr_lbl_txt( $lbl, $lblSuffix );
    return  '<p><span>' . $lbl . '</span>&nbsp;' . $value . '</p>';
}

 function abcfic_inputbldr_tbl_lbl_substr($lbl, $value, $len=50, $lblSuffix='') {

    if( abcfic_lib_isblank($value) ){ return ''; }
    $lbl = abcfic_inputbldr_lbl_txt( $lbl, $lblSuffix );
    if(strlen($value) > $len){ $value = substr($value, 0, $len) . "..."; }
    return  abcfic_inputbldr_tbl_lbl($lbl, $value);
}

function abcfic_inputbldr_tbl_lbl_lnk($href, $targetID, $len) {

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
//===LABELS=======================================================================
function abcfic_inputbldr_lbl($fldID, $lbl, $lblSuffix) {
    $out = '';
    $lbl = abcfic_inputbldr_lbl_txt($lbl, $lblSuffix);
    if( !abcfic_lib_isblank($fldID)){$fldID = ' for="' . $fldID . '" ';}
    if( !abcfic_lib_isblank($lbl)) { $out = '<label' . $fldID . '>' . $lbl . '</label>';}
    //if($lblID > 0) { $out = '<label for="' . $fldID . '">' . abcfic_inputbldr_lbl_txt($lbl, $lblSuffix) . '</label>';}
    return $out;
}

function abcfic_inputbldr_hlp_top( $hlpID ) {
    $out = '';
    if($hlpID > 0) { $out = '<div class="abcficHlpTop">' . abcfic_inputbldr_txt($hlpID) . '</div>';}
    return $out;
}

function abcfic_inputbldr_hlp_under( $hlpID ) {
    $out = '';
    if($hlpID > 0) { $out = '<span class="abcficFldHlpUnder">' . abcfic_inputbldr_txt($hlpID) . '</span>';}
    return $out;
}

function abcfic_inputbldr_section_header( $hlpID, $noHlp = false ) {
    $out = '';
    $suffix = '';
    if($noHlp) { $suffix = 'NoHlp'; }
    if($hlpID > 0) { $out = '<div class="abcficSecHdr' . $suffix . '">' . abcfic_inputbldr_txt($hlpID) . '</div>';}
    return $out;
}

function abcfic_inputbldr_hlp_data( $hlpID, $data, $fontSize = '11' ) {
    $out = '';
    if($hlpID > 0) { $out = '<span class="abcficFldHlpData' . $fontSize . '">' . abcfic_inputbldr_txt($hlpID) . $data .'</span>';}
    return $out;
}

//===HELPERS=====================================================================
function abcfic_inputbldr_id( $fldID ){

    if(!abcfic_lib_isblank($fldID)){ return ' id="' . $fldID . '"'; }
    return '';
}

function abcfic_inputbldr_name( $fldName ){

    if(!abcfic_lib_isblank($fldName)){ return ' name="' . $fldName . '"'; }
    return '';
}

function abcfic_inputbldr_input_options( $fldID, $fldName, $lblID, $hlpID, $size, $cls, $style, $clsCntr, $clsLbl, $lblSuffix, $values='', $selected='') {

    list($w, $units) = abcfic_inputbldr_input_size($size);
    $w = abcfic_lib_htmlbldr_css_w($w, $units);
    $style = abcfic_lib_htmlbldr_css_style( $w . $style );

    if(empty($fldName)) $fldName = $fldID;
    $cls = abcfic_lib_htmlbldr_css_class($cls);
    $divs = abcfic_inputbldr_fld_cntr_divs($clsCntr, $clsLbl);
    $lbl = abcfic_inputbldr_lbl($fldID, $lblID, $lblSuffix );
    $hlp = abcfic_inputbldr_hlp_under($hlpID);
    $options = abcfic_inputbldr_cbo_get_options($values, $selected);

    $out = array(
        'cls'       => $cls,
        'style'     => $style,
        'divs'      => $divs,
        'lbl'       => $lbl,
        'hlp'       => $hlp,
        'fldName'   => $fldName,
        'options'   => $options
    );
    return $out;
}
function abcfic_inputbldr_lbl_txt( $lbl, $lblSuffix ){

    if(is_int($lbl)){ $lbl = abcfic_inputbldr_txt($lbl, $lblSuffix); }
    return $lbl;
}

function abcfic_inputbldr_input_size( $size ) {

    $defaultW='30';
    $defaultUnits='%';
    if(empty($size)) { return array($defaultW, $defaultUnits); }

    $w = '';
    $units = substr($size, -1, 1);
    if( $units == '%' ) { $w = rtrim($size, '%'); };
    if( $units == 'x' ) {
        $w = rtrim($size, 'px');
        $units = 'px';
     };

    if(empty($w)) {return array($defaultW, $defaultUnits);}
    return array($w, $units);
}

function abcfic_inputbldr_fld_cntr_divs($clsCntr, $clsLbl) {

    $clsCntr = !empty($clsCntr) ? $clsCntr : 'abcficFldCntr';
    $clsLbl = !empty($clsLbl) ? $clsLbl : 'abcficFldLbl';

    return '<div class="' . $clsCntr . '"><div class="' . $clsLbl .'">';
}

function abcfic_inputbldr_cbo_get_options($values, $selected_value) {
    $out = "";
    if(empty($values)){return $out;}
    $selected = "";
    foreach($values as $key => $fldValue){
        //return ('key= ' . $key . ' sw= ' . $selected_value);
        $selected = abcfic_inputbldr_cbo_set_selected($key, $selected_value);
        $out .= "<option $selected value=\"$key\">$fldValue</option>\n";
    }
    return $out;
}

function abcfic_inputbldr_cbo_set_selected($key, $selected_value) {
    $out = "";
    if(!abcfic_lib_isblank($selected_value)) { if($key == $selected_value) {$out = " selected=\"selected\" "; } }
    return $out;
}

