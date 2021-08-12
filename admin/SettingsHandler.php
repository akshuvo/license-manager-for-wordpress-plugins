<?php 

/**
 * The Settings handler class
 */

class LMFWPPT_SettingsHandler{
	
	/**
     * Initialize the class
     */

	function __construct(){
		// Ajax Add data options table 
		add_action( 'wp_ajax_setting_add_form', [ $this, 'setting_add' ] );
	}

	// Setting add form action
    function setting_add(){
        if ( isset( $_POST['lmaction'] ) && $_POST['lmaction'] == "setting_add_form" ) {
           $lmfwppt_settings = isset( $_POST['lmfwppt_settings'] ) ? $_POST['lmfwppt_settings'] : array();
           update_option( 'lmfwppt_settings', $lmfwppt_settings );
        }
        die();
    }

    // Get option data 
	function lmfwppt_get_option( $name = null ){
	    
	    if ( !$name ) {
	        return;
	    }

	    $settings = get_option( 'lmfwppt_settings' );
	    return isset( $settings[$name] ) ? $settings[$name] : "";
	}

}
new LMFWPPT_SettingsHandler();