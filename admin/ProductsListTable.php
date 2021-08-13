<?php 
if( !class_exists('WP_List_Table') ){
	require_once ABSPATH .'wp-admin/includes/class-wp-list-table.php';
}

/**
 *  
 * Product List Table Class
 * 
*/
class LMFWPPT_ProductsListTable extends \WP_List_Table{

	function __construct(){
		parent::__construct([
			'singular' => "product",
			'plural' => "products",
			'ajax' => false
		]);
	}

	// number of column
	public function get_columns(){
		return [
			'cb' => "<input type='checkbox'/>",
			'name' => __('Product Name','lmfwppt'),
			'slug' => __('Product Slug','lmfwppt'),
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
            'name' => [ 'name', true ],
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
	public function column_name($item){
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : "";

		$actions = [];
		$actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page='.$page.'&action=edit&id=' . $item->id ), $item->id, __( 'Edit', 'license-manager-wppt' ), __( 'Edit', 'license-manager-wppt' ) );

        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=lmfwppt-delete-product&redirect_url='.$page.'&id=' . $item->id ), 'lmfwppt-delete-product' ), $item->id, __( 'Delete', 'license-manager-wppt' ), __( 'Delete', 'license-manager-wppt' ) );

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url('admin.php?page=license-manager-wppt&action=edit&id=' . $item->id ), $item->name, $this->row_actions($actions)
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

	public function prepare_items( $product_type = null ){

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
            'product_type' => $product_type 

        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $this->items = $this->get_product_list($args);

        // pagination and sortable
		$this->set_pagination_args([
			'total_items' =>$this->product_count($product_type),
            'per_page'    =>$per_page,
		]);
	}

	// Function 
	/**
	 * Get the Product
	 *
	 * @return Array
	 */
	function get_product_list( $args = [] ) {
	    global $wpdb;

	    $defaults = [
	        'number' => 20,
	        'offset' => 0,
	        'orderby' => 'id',
	        'order' => 'DESC',
	        'product_type' => 'plugin'
	    ];

	    $args = wp_parse_args( $args, $defaults );

	    $product_list = $wpdb->prepare(
	            "SELECT * FROM {$wpdb->prefix}lmfwppt_products
	            WHERE product_type = %s ORDER BY {$args['orderby']} {$args['order']}
	            LIMIT %d, %d",
	            $args['product_type'], $args['offset'], $args['number'] 
	    );

	    $items = $wpdb->get_results( $product_list );

	    return $items;
	}

	/**
	 * Get the Product Item Count
	 *
	 * @return Int
	 */
	function product_count( $product_type ){

	  	global $wpdb;

        $defaults = [
            'number' => 20,
            'product_type' => $product_type
        ];

        $args = wp_parse_args( $defaults );
        $product_list = $wpdb->prepare("SELECT count(id) FROM {$wpdb->prefix}lmfwppt_products WHERE product_type = %s 
            LIMIT %d",
            $args['product_type'], 
            $args['number'] );
        return (int) $wpdb->get_var( $product_list );

	}

}
