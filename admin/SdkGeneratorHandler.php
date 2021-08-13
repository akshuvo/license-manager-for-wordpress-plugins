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
        	$product_type =  $_POST['product_type'];
        	$select_product_theme = $_POST['select_product_theme'];
        	$select_product_plugin = $_POST['select_product_plugin'];
        	echo $product_type;
        	echo $select_product_theme;
        	echo $select_product_plugin;
        }
      die();
   }

}
new LMFWPPT_SdkGeneratorHandler();