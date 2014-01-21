<?php
/*
 * DONE: 1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists('ABCFIC_Collection_Manager') ) {
class ABCFIC_Collection_Manager {

    public $mode = '';
    public $tab = '';
    public $collID = 0;
    public $img_id = false;
    public $base_pg = '';

    function __construct() {

        $get = abcfic_optns_tab_get_params( $_GET );
        $this->base_pg = 'admin.php?page=' . $get['page'];
        $this->mode = $get['mode'];
        $this->collID  = (int) $get['collid'];
        $this->img_id  = (int) $get['img_id'];
        $this->tab = $get['tab'];

        //Bulk actions. Called from modal forms.
        $post = abcfic_optns_bulk_params( $_POST );
        if ( $post['page'] == 'bulk-process-imgs' ) { $this->post_bulk_processor(); }
    }

    function render_collection_page() {
        switch($this->mode) {
            case 'tabscoll':
                include_once (ABCFIC_PLUGIN_DIR . 'includes/tabs-collection.php');
                abcfic_tabs_collection($this->mode, $this->base_pg);
                break;
            case 'pg-collections':
            default:
                include_once (ABCFIC_PLUGIN_DIR . 'includes/class-collection.php');
                include_once (ABCFIC_PLUGIN_DIR . 'includes/pg-collections.php');
                abcfic_pg_collections();
                break;
        }
    }

    function post_bulk_processor() {

        $post = abcfic_optns_bulk_params( $_POST );

        if (!empty($post['bulkActionType']))  {
            check_admin_referer('abcfic_modal_form');
            $collID = $post['collID'];
            switch ($post['bulkActionType']) {
                case 'delete_images' :
                    if (isset ($_POST['lstDeleteImgs'])) {
                        $imgIDs  = explode(',', $_POST['lstDeleteImgs']);
                        if($collID > 0){
                            $ib = new ABCFIC_Image_Bulk();
                            $ib->delete_files($imgIDs, $collID);
                        }
                        else{ abcfic_msgs_error(67); }
                    }
                    break;
                default:
                     break;
            }
        }
    }
}
}