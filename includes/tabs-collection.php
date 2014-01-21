<?php
/*
 * Collection Tabs & active tab page.
 * TODO:
 */
function abcfic_tabs_collection( $mode, $basePg ) {

    $tabs = array(
        'options' => 'Options',
        'uploader' => 'Image Uploader',
        'published' => 'Images',
        'sort' => 'Sort Order'
        );
    $links = array();

    $get = abcfic_optns_coll_url_params( $_GET );
    $collID = (int)$get['collid'];
    $currentTab = $get['tab'];

    //Resize images & create thumbs. Called by abcfic-plupload.js after all images have been uploaded.
    if ( isset($_POST['plupload_callback']) ){
        if ($_POST['plupload_callback'] == 'OK' ){
            if ($_POST['collid'] == '0' ){
                abcfic_msgs_error(58);
            }
            else{
            //All images are uploaded and saved in collection folder. Now resize and create thumbs
            $upload = new ABCFIC_Upload($_POST['collid']);
            $upload->process_saved_images();
            }
        }
        else {
            abcfic_msgs_error(57);
        }
    }

   //Tab links
   foreach( $tabs as $tab => $name ) {

        $href =  $basePg . '&amp;mode=' . $mode . '&amp;collid=' . $collID . '&amp;tab=' . $tab;
        if ( $tab == $currentTab ) { $links[] = abcfic_lib_htmlbldr_html_a_tag($href, $name, '', 'nav-tab abcficNavTab nav-tab-active abcficNavTabActive', ''); }
        else { $links[] = abcfic_lib_htmlbldr_html_a_tag($href, $name, '', 'nav-tab abcficNavTab', ''); }
    }
?>
    <div class="wrap"><h2 class="nav-tab-wrapper"><?php
        foreach ( $links as $link ){ echo $link; }?>
        </h2></div><?php
    switch ( $currentTab ) {
        case 'options' :
            include_once (ABCFIC_PLUGIN_DIR . 'includes/class-collection.php');
            include_once (ABCFIC_PLUGIN_DIR . 'includes/tab-options.php');
            abcfic_tab_options($collID);
            break;
        case 'uploader' :
            include_once (ABCFIC_PLUGIN_DIR . 'includes/class-collection.php');
            include_once (ABCFIC_PLUGIN_DIR . 'includes/tab-uploader.php');
            abcfic_tab_uploader($collID);
            break;
        case 'published' :
            include_once (ABCFIC_PLUGIN_DIR . 'includes/tab-published.php');
            abcfic_tab_published($collID);
            break;
        case 'sort' :
            include_once (ABCFIC_PLUGIN_DIR . 'includes/tab-sort.php');
            abcfic_tab_sort($collID);
            break;
        default:
            break;
   }
}