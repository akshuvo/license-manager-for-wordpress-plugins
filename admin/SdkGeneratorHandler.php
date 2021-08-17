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

      	echo $product_type." ";
      	echo $select_product." " ;
      	echo $menu_type." ";
      	echo $page_title." ";
      	echo $menu_title." ";
      	echo $parent_menu_slug;

        }
      die();
   }

}
new LMFWPPT_SdkGeneratorHandler();