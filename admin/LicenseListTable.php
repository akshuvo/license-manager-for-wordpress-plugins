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
			'license_key' => __('License Key','lmfwppt'),
			'license_details' => __('License Info','lmfwppt'),
			'order_id' => __('Order Id','lmfwppt'),
			'end_date' => __('Expires','lmfwppt'),
			'dated' => __('Date','lmfwppt'),
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

		$actions['id']   = sprintf( '<span class="id">ID: %s </span>', $item->id );

		$actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=license-manager-wppt-licenses&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'license-manager-wppt' ), __( 'Edit', 'license-manager-wppt' ) );

        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=lmfwppt-delete-license&id=' . $item->id ), 'lmfwppt-delete-license' ), $item->id, __( 'Delete', 'license-manager-wppt' ), __( 'Delete', 'license-manager-wppt' ) );

		return sprintf(
			'<input class="w-100" type="text" value="%1$s" readonly/> %2$s', $item->license_key, $this->row_actions($actions)
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

	protected function column_end_date($item){
		return date('j F Y',strtotime($item->end_date));
	}

	public function column_license_details($item){
		$package_id = LMFWPPT_ProductsHandler::get_product_details_by_package_id($item->package_id);
	
		$package_details = '<a href="admin.php?page=license-manager-wppt-licenses&action=edit&id='.$package_id['product_id'].'">'.$package_id['name'].' ('.$package_id['label'].')'.'</a>';
		 
		$package_details .= '<ul class="package_details">
					<li>Domain Limit: '.$package_id['domain_limit'].'</li>
					<li>Product Type: '.$package_id['product_type'].'</li>
				</ul';
		return $package_details; 
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
