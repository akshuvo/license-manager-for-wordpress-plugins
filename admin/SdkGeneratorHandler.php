<?php 

/**
 * The SDk Generator handler class
 */

class LMFWPPT_SdkGeneratorHandler{
	
	/**
     * Initialize the class
     */

	function __construct(){
		// Ajax Add data options table 
		add_action( 'wp_ajax_sdk_generator_add_form', [ $this, 'sdkgenerator_add' ] );
	}

	// Setting add form action
   function sdkgenerator_add(){

      if ( isset( $_POST['lmaction'] ) && $_POST['lmaction'] == "sdk_generator_add_form" ) {

      	$product_type =  isset( $_POST['product_type'] ) ? sanitize_text_field( $_POST['product_type'] ) : '';
        $select_product = isset( $_POST['select_product'] ) ? sanitize_text_field( $_POST['select_product'] ) : '';
        $menu_type = isset( $_POST['menu_type'] ) ? sanitize_text_field( $_POST['menu_type'] ) : '';
        $page_title = isset( $_POST['page_title'] ) ? sanitize_text_field( $_POST['page_title'] ) : '';
        $menu_title = isset( $_POST['menu_title'] ) ? sanitize_text_field( $_POST['menu_title'] ) : '';
        $parent_menu_slug = isset( $_POST['parent_slug'] ) ? sanitize_text_field( $_POST['parent_slug'] ) : '';

        ob_start(); ?>
        <div class="lmwppt-inner-card">
            <pre>
/**
 * License Management
 */
add_action( 'init', 'your_plugin_slug_updates' );
function your_plugin_slug_updates( ){
    include_once( dirname( __FILE__ ) . '/updates/LmfwpptAutoUpdatePlugin.php' );
    $plugin = plugin_basename( __FILE__ );
    $plugin_slug = (dirname(plugin_basename(__FILE__)));
    $current_version = '1.0.0';
    $remote_url = '<?php echo home_url('/'); ?>';

    new LmfwpptAutoUpdatePlugin( $current_version, $plugin, $plugin_slug, $remote_url );
}
            </pre>
        </div>

        <div class="lmwppt-inner-card">
            <pre>
// Another Code block
            </pre>
        </div>


        <?php $output = ob_get_clean();

        echo $output;

        }
      die();
   }

}
new LMFWPPT_SdkGeneratorHandler();