<?php 
if( !class_exists('WP_List_Table') ){
	require_once ABSPATH .'wp-admin/includes/class-wp-list-table.php';
}

/**
 *  
 * Product List Table Class
 * 
*/
class LMFWPPT_LicenseListTable extends \WP_List_Table{

	function __construct(){
		parent::__construct([
			'singular' => "license",
			'plural' => "license",
			'ajax' => false
		]);
	}

	// number of column
	public function get_columns(){
		return [
			'cb' => "<input type='checkbox'/>",
			'id' => __('ID', 'lmfwppt'),
			'license_key' => __('License Key','lmfwppt'),
			'package_id' => __('Package Id','lmfwppt'),
			'order_id' => __('Order Id','lmfwppt'),
			'dated' => __('Date','lmfwppt')
		];
	}

	/**
     * Get sortable columns
     *
     * @return array
     */

	// pagination and sortable use this code
    function get_sortable_columns() {
        $sortable_columns = [
            'id' => [ 'id', true ],
            'dated' => [ 'dated', true ],
        ];

        return $sortable_columns;
    }
    // pagination and sortable use this code

	protected function column_default($item, $column_name){
		switch ($column_name) {
			case 'value':
				# code...
				break;
			
			default:
				return isset($item->$column_name) ? $item->$column_name : '';
		}
	}

	// Default column Customize
	public function column_license_key($item){
		$actions = [];
		$actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=license-manager-wppt-licenses&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'license-manager-wppt' ), __( 'Edit', 'license-manager-wppt' ) );

        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=lmfwppt-delete-license&id=' . $item->id ), 'lmfwppt-delete-license' ), $item->id, __( 'Delete', 'license-manager-wppt' ), __( 'Delete', 'license-manager-wppt' ) );

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=license-manager-wppt-licenses&action=edit&id=' . $item->id ), $item->license_key, $this->row_actions($actions)
		);

	}

	protected function column_cb($item){
		return sprintf(
			"<input name='product_id[]' type='checkbox' value='%d'/>", $item->id
		);
	}

	protected function column_dated($item){
		return date('j F Y',strtotime($item->dated));
	}

	public function prepare_items( ){

		$column = $this->get_columns();
		$hidden = [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [$column, $hidden, $sortable];

		//  pagination and sortable
		 $per_page     = 20;
         $current_page = $this->get_pagenum();
         $offset = ( $current_page - 1 ) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $this->items = $this->get_license_list($args);

        // pagination and sortable
		$this->set_pagination_args([
			'total_items' => $this->license_count(),
            'per_page'    => $per_page,
		]);
	}

	// Function 
	/**
	 * Get the License
	 *
	 * @return Array
	 */
	function get_license_list( $args = [] ) {
	    global $wpdb;

	    $defaults = [
	        'number' => 20,
	        'offset' => 0,
	        'orderby' => 'id',
	        'order' => 'DESC',
	    ];

	    $args = wp_parse_args( $args, $defaults );

	    $product_list = $wpdb->prepare(
	            "SELECT * FROM {$wpdb->prefix}lmfwppt_licenses
	            ORDER BY {$args['orderby']} {$args['order']}
	            LIMIT %d, %d",
	            $args['offset'], $args['number'] 
	    );

	    $items = $wpdb->get_results( $product_list );

	    return $items;
	}

	/**
	 * Get the License Count
	 *
	 * @return Int
	 */
	function license_count(){
	  global $wpdb;
	  return (int) $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}lmfwppt_licenses");
	}
}
