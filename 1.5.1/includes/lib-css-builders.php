<?php
/**
 * CSS builders
 *
*/
//====WxH============================================
function abcfic_lib_css_wh($w, $h){ return abcfic_lib_css_w($w) . abcfic_lib_css_h($h); }

function abcfic_lib_css_w($in){
    if(abcfic_lib_isblank($in)) { return''; }
    return 'width:'.$in.'px;';
}

function abcfic_lib_css_h($in){
    if(abcfic_lib_isblank($in)) { return ''; }
    return 'height:'.$in.'px;';
}

//=======MARGINS============================================
function abcfic_lib_css_mtl($t, $l){ return abcfic_lib_css_mt($t) . abcfic_lib_css_ml($l); }

function abcfic_lib_css_ml($in){
    if(abcfic_lib_isblank($in)) { return''; }
    return 'margin-left:'. $in . abcfic_lib_css_px($in) . ';';;
}

function abcfic_lib_css_mt($in){
    if(abcfic_lib_isblank($in)) { return''; }
    return 'margin-top:'. $in . abcfic_lib_css_px($in) . ';';
}
//=======PADDING============================================
function abcfic_lib_css_ptl($t, $l){ return abcfic_lib_css_pt($t) . abcfic_lib_css_pl($l); }

function abcfic_lib_css_pl($in){
    if(abcfic_lib_isblank($in)) { return''; }
    $s = 'padding-left:';
    if(substr($in,0,1) == '-'){ $s = 'margin-left:'; }

    return $s . $in . abcfic_lib_css_px($in) . ';';
}

function abcfic_lib_css_pt($in){
    if(abcfic_lib_isblank($in)) { return''; }
    $s = 'padding-top:';
    if(substr($in,0,1) == '-'){ $s = 'margin-top:'; }

    return $s . $in . abcfic_lib_css_px($in) . ';';
}
//===STYLE================================================
function abcfic_lib_css_style_wh($w, $h) { return abcfic_lib_css_style_tag(abcfic_lib_css_wh($w, $h));}

function abcfic_lib_cssbldr_style_margin_tl($t, $l) { return abcfic_lib_css_style_tag(abcfic_lib_css_mtl($t, $l));}

//===HELPERS================================================
function abcfic_lib_css_class_tag( $cls ){
    if(abcfic_lib_isblank($cls)) {return '';}
    return ' class="' . $cls . '"';
}

 function abcfic_lib_css_style_tag($style) {
    if(abcfic_lib_isblank($style)) {return '';}
    return ' style="' . $style . '" ';
}

function abcfic_lib_css_px($in){
    $px = 'px';
    if($in == '0'){ $px = '';}
    return $px;
}
