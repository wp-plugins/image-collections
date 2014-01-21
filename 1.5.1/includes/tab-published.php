<?php
/* * ************************* LOAD THE BASE CLASS *******************************
 * ******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/* * ************************ CREATE A PACKAGE CLASS *****************************
 * ******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 */
class _ABCFIC_Tbl_Published extends WP_List_Table {

    var $seq = 0;

    function __construct() {

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'abcfictblimgss', //singular name of the listed records
            'plural' => 'abcfictblimgsp', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
                )
        );
    }

    /*************************************************************************
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     * Called when the parent class can't find a method specifically build for a given column.
     * ************************************************************************ */
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'img_id':
            case 'set_no' :
            case 'sort_order':
                return $item[$column_name];
            default:
                return '';
            //return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    /*** ***********************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     * ************************************************************************ */
    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['img_id']);   //Value of the checkbox should be the record id
    }

    function column_rno($item) {
        $this->seq = $this->seq + 1;
        return $this->seq;
    }

    function column_thumb($item) { return abcfic_cntbldr_tbl_thumb($item);  }

    function column_thumb2($item) { return abcfic_cntbldr_tbl_thumb2($item);  }

    function column_filename($item) {
        $box = abcfic_cntbldr_tbl_file_box($item);
        return $box['fileName'] . $box['dt'] . $this->row_actions($box['actions']);
    }

    function column_txt($item) { return abcfic_cntbldr_tbl_txt_box($item); }
// **************************************************************************/
    function get_columns() {  return abcfic_cntbldr_tbl_headers(); }

    function get_sortable_columns() { return abcfic_cntbldr_tbl_sortable_cols(); }

    /*************************************************************************
     * Define bulk actions.
     * @return - an associative array : 'slugs'=>'Visible Titles'
     * ************************************************************************ */
    function get_bulk_actions() {
        $actions = array(
            'delete_images' => abcfic_inputbldr_txt(81)
        );
        return $actions;
    }
    /*************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.?><script type="text/javascript"> onclick="checkSelected()";
     * ?><script type="text/javascript">function doDetail(){$("#dialog-detail").load('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx').dialog({modal: true});};</script><?php
     * @see $this->prepare_items()
     * ************************************************************************ */
    function process_bulk_action() {

        //wp_die('Items deleted (or they would be if we had items to delete)!');

        $currAction = $this->current_action();
        abcfic_cntbldr_bulk_actions_frm($currAction);

    }

    /*************************************************************************
     * Prepare your data for display. At a minimum, we should set $this->items and
     * $this->set_pagination_args()
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     * ************************************************************************ */
    function prepare_items() {

        $params = abcfic_optns_coll_url_params( $_GET );
        $collID = (int)$params['collid'];
        $orderDir = $params['order'];
        $orderBy = $params['orderby'];

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        $data = abcfic_db_tbl_collection( $collID, $orderBy, $orderDir );
        $this->items = $data;
    }

}
//===Class End===========================================================

/* Render page.
 * Although it's possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */
function abcfic_tab_published( $collID ) {

    abcfic_cntbldr_permission_check();
    $collsTbl = new _ABCFIC_Tbl_Published();
    abcfic_cntbldr_coll_tbl( $collID, $collsTbl, 'P' );

}
