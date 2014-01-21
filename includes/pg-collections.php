<?php

//*************************** LOAD THE BASE CLASS *******************************
if(!class_exists('WP_List_Table')){ require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); }

class ABCFIC_Collections_List_Table extends WP_List_Table {

    var $seq = 0;

    function __construct(){
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'abcfictblcollss',     //singular name of the listed records
            'plural'    => 'abcfictblcollsp',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
            )
        );
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'coll_id':
            case 'img_wh':
            case 'thumb_wh':
            case 'imgs_qty':
            case 'added_dt':
            case 'updated_dt':
                return $item[$column_name];
            default:
                return '';
                //return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

       function column_cb($item){
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['coll_id']);   //Value of the checkbox should be the record id
    }

    function column_coll_name($item){

        $abcfic = ABCFIC_Main();
        //global $abcfic;
        $collName = $item['coll_name'];
        $collID = $item['coll_id'];
        $href = wp_nonce_url( $abcfic->coll_manager->base_pg . '&amp;mode=tabscoll&amp;tab=options&amp;collid=' . $collID, 'abcfic_editcoll');
        $delete = '<a class="submitdelete" href="' . wp_nonce_url("admin.php?page=abcfic-collection-manger&amp;mode=delcoll&amp;collid=" . $collID, 'abcfic_delcollection'). '" class="delete column-delete" onclick="javascript:check=confirm( \'' . esc_attr(sprintf('Delete "%s" ?', $collName)). '\');if(check==false) return false;">' . 'Delete' . '</a>';

        //Build row actions
        $actions = array(
            'view'      => abcfic_lib_htmlbldr_html_a_tag($href, 'Open'),
            'delete'    => $delete
        );

        return sprintf('%s %s', abcfic_lib_htmlbldr_html_a_tag($href, $collName), $this->row_actions($actions) );
    }

    function column_rno($item){
        $this->seq = $this->seq + 1;
        return $this->seq;
    }

    function column_added_dt($item){
    //return date( 'Y-m-d H:i:s', $item['added_dt'] );
    return $item['added_dt'];
    }
// ***Define the columns that are going to be used in the table**'coll_no' => 'Collection No',*'coll_folder' => 'Folder',*********/
    function get_columns(){
        $columns = array(
            'rno'                   => '#',
            'coll_id'               => 'ID',
            'coll_name'             => 'Name',
            'img_wh'                => 'Imgs',
            'thumb_wh'              => 'Thumbs',
            'imgs_qty'              => 'Qty',
            'added_dt'              => 'Added',
            'updated_dt'            => 'Updated'
        );
        return $columns;
    }
// ***Define sortable the ************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'coll_id'   => array('coll_id',true),
            'coll_name' => array('coll_name',true),
            'added_dt' => array('added_dt',true),
            'updated_dt' => array('updated_dt',true)
        );
        return $sortable_columns;
    }

    //Remove space above the table
    function display_tablenav( $bottom ){
    }
     /** ************************************************************************
     * Prepare your data for display. At a minimum, we should set $this->items and
     * $this->set_pagination_args()
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     ****************************************************c.coll_no,*c.coll_folder,*********************/
    function prepare_items() {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $orderBy = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'coll_name';
        $orderDir = ( isset ( $_GET['order'] ) && $_GET['order'] == 'desc' ) ? 'DESC' : 'ASC';

        $data = abcfic_db_tbl_collections( $orderBy, $orderDir );
        $this->items = $data;
    }
}
//======Class End================================================================

//***************************** RENDER PAGE *************************************
function abcfic_pg_collections(){

    abcfic_cntbldr_permission_check();

    $mode = 'pg-collections';
    $collID = false;
    if( isset($_GET['mode']) ) {$mode = trim ($_GET['mode']);}
    if( isset($_GET['collid']) ){ $collID  = (int) $_GET['collid'];}

    if ($mode == 'delcoll' && $collID > 0) {
        abcfic_delete_collection($collID);
    }

    $collsTbl = new ABCFIC_Collections_List_Table();
    $collsTbl->prepare_items();

?>
    <div class="wrap">
    <div class="abcicIcon32"><br></div>
    <h2><?php echo abcfic_inputbldr_txt(86);?>&nbsp;<a class="add-new-h2" <?php echo('href="admin.php?page=' . ABCFIC_PLUGIN_FOLDER . '"'); ?>><?php echo abcfic_inputbldr_txt(87);?></a></h2>
    <?php $collsTbl->display() ?>
    </div>
<?php
 }

  function abcfic_delete_collection($collID) {

     $objColl = new ABCFIC_Collection();
     $objColl->delete_collection($collID);
 }
