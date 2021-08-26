<?php

/**
 * The Menu handler class
 */
class LMFWPPT_LicenseHandler {

    /**
     * Initialize the class
     */
    function __construct() {
        
        add_action( 'wp_ajax_license_add_form', [ $this, 'license_add' ] );
        add_action( 'wp_ajax_get_packages_option', [ $this, 'product_package' ] ); 
        add_action( 'wp_ajax_license_key', [ $this, 'ajax_generate_license_key' ] );
        add_action( 'admin_init', [ $this, 'delete_license' ] );
        // Add Domain field ajax
        add_action( 'wp_ajax_lmfwppt_domain_active_field_action', [ $this, 'domain_ajax_add_action' ] );

        // Get Product info
        if ( isset( $_GET['lmfwppt-info'] ) && $_GET['lmfwppt-info'] == "true" ) {
            $this->get_wp_license_details( $_GET );
            exit;
        }

        // Generate Download
        if ( isset( $_GET['product_slug'] ) && isset( $_GET['license_key'] ) && isset( $_GET['action'] ) && $_GET['action'] == "download" ) {
            $this->download_product( $_GET );
        }
        
    }

    // Section information Field add
    function domain_ajax_add_action(){

        $key = sanitize_text_field( $_POST['key'] );

        ob_start();

        echo self::domain_sections_field( array(
            //'key' => $key,
            'thiskey' => $key,
        ) );

        $output = ob_get_clean();

        echo $output;

        die();
    }

    // Single Section field
    public static function domain_sections_field( $args ){

        $defaults = array (
            'key' => '',
            'url' => '',
            'deactivate' => '',
        );

        // Parse incoming $args into an array and merge it with $defaults
        $args = wp_parse_args( $args, $defaults );

        // Let's extract the array to variable
        extract( $args );

        // Array key
        //$key =  isset( $args['key'] ) ? $args['key'] : "";
        $key =  !empty( $key ) ? $key : wp_generate_password( 3, false );
   
        $field_name = "lmfwppt[domains][$key]";

        ob_start();
        do_action( 'lmfwppt_license_field_before_wrap', $args );
        ?>

         <div id="postimagediv" class="postbox lmfwppt_license_field"> <!-- Wrapper Start -->
            <span id="poststuff">
                <h2 class="hndle">
                     
                    <input id="<?php esc_attr_e( $field_name ); ?>-lmfwppt_domain" class="regular-text" type="url" name="<?php esc_attr_e( $field_name ); ?>[url]" value="<?php echo esc_attr( $url ); ?>" placeholder="<?php echo esc_attr( 'Url', 'lmfwppt' ); ?>" required />
                    <label class="lmfwppt_label_space">
                        <input name="<?php esc_attr_e( $field_name ); ?>[deactivate]" type="checkbox" id="<?php esc_attr_e( $field_name ); ?>-lmfwppt_deactivate" <?php checked($deactivate, "on"); ?>><?php esc_html_e( 'Deactivate', 'lmfwppt' ); ?>
                    </label>
                    <span class="delete_field">&times;</span>
                </h2>
            </span>
        </div>
        <?php
        $output = ob_get_clean();

        return do_action( 'lmfwppt_license_field_after_wrap', $output, $args );
    }

     // Generate html from Domain array
    public static function get_domains_html( $get_domains = null ){
        if( !$get_domains ){
            return;
        }

        foreach ($get_domains as $domains) {
            self::domain_sections_field( array(
                'key' => sanitize_title($domains['url']),
                'url' => $domains['url'],
                'deactivate' => $domains['deactivate']
            ) );
        }

    }

    // Get Product details by id al
    public static function get_license( $id = null ){

        if( !$id ){
            return;
        }

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_licenses WHERE id = %d", $id);
        return $wpdb->get_row( $query, ARRAY_A );
    }


    // License Package Field add
    function get_license_details( $license_key = null ){
        $response = array();

        if ( !$license_key ) {
            return false;
        }

        global $wpdb;

        $get_license = $wpdb->get_row( $wpdb->prepare("SELECT package_id, dated FROM {$wpdb->prefix}lmfwppt_licenses WHERE license_key = %s", $license_key), ARRAY_A );

        $package_id = isset( $get_license['package_id'] ) ? $get_license['package_id'] : null;
        $license_date = isset( $get_license['dated'] ) ? $get_license['dated'] : null;

        if ( !$package_id ) {
            return false;
        }

        $get_product = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_license_packages as lp INNER JOIN {$wpdb->prefix}lmfwppt_products as p ON p.id = lp.product_id WHERE lp.package_id = %s", $package_id), ARRAY_A );

        // change download url
        $get_product['license_key'] = $license_key;
        $get_product['license_date'] = $license_date;

        return $get_product;
    }

    // Get Product details by product slug
    function get_product_details_by_slug( $slug = null ){

        if ( !$slug ) {
            return false;
        }

        global $wpdb;

        $get_product = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_products WHERE slug = %s", $slug), ARRAY_A );

        return $get_product;
    }


    // License Package Field add
    function get_wp_license_details( $posted_args = array() ){
        $response = array();

        //ppr( $args );

        if ( !is_array( $posted_args ) ) {
            return false;
        }

        $args = wp_parse_args( $posted_args, array(
            'product_slug' => '',
            'license_key' => '',
        ) );

        $download_link = add_query_arg( array(
            'product_slug' => $args['product_slug'],
            'license_key' => $args['license_key'],
            'action' => 'download',
        ), lmfwppt_api_url() );

       $get_product = $this->get_product_details_by_slug( $args['product_slug'] );

        // change download url
        if ( isset( $get_product ) && is_array( $get_product ) ) {
           $get_product['download_link'] = $download_link;
        }

        // change date to last_updated
        if ( isset( $get_product['dated'] ) ) {
           $get_product['last_updated'] = $get_product['dated'];
           unset( $get_product['dated'] );
        }
        
        // Remove ID
        if ( isset( $get_product['id'] ) ) {
            unset( $get_product['id'] );
        }

        // Remove created_by
        if ( isset( $get_product['created_by'] ) ) {
            unset( $get_product['created_by'] );
        }
        
        // Remove product_type
        if ( isset( $get_product['product_type'] ) ) {
            unset( $get_product['product_type'] );
        }

        //Remove serialize banners
        if ( isset( $get_product['banners'] ) ) {
            $get_product['banners'] = unserialize( $get_product['banners'] );
        }

        //Remove serialize sections
        if ( isset( $get_product['sections'] ) ) {
            $get_product['sections'] = unserialize( $get_product['sections'] );
        }

        echo json_encode($get_product, true);

        exit;
    }

    // Product add form action
    function license_add(){
   
        if ( isset( $_POST['lmaction'] ) && $_POST['lmaction'] == "license_add_form" ) {

            $license_id = $this->create_license( $_POST['lmfwppt'] );

            echo $license_id;

        }

        die();
    }

    // Create License function
    function create_license( $post_data = array() ){
        global $wpdb;
        $table = $wpdb->prefix.'lmfwppt_licenses';

        $data = array(
            'license_key' => isset($post_data['license_key']) ? sanitize_text_field( $post_data['license_key'] ) : "",
            'package_id' => isset($post_data['package_id']) ? sanitize_text_field( $post_data['package_id'] ) : "",
            'order_id' => isset($post_data['order_id']) ? sanitize_text_field( $post_data['order_id'] ) : "",
            'end_date' => isset($post_data['end_date']) ? date("Y-m-d", strtotime( $post_data['end_date'] ) ) : "",
            'domains' => isset($post_data['domains']) ? serialize( $post_data['domains'] ): "",
        );

        if ( isset( $post_data['license_id'] ) ) {
            $insert_id = intval( $post_data['license_id'] );
            $wpdb->update( $table, $data, array( 'id'=> $insert_id ) );
        } else {
            $wpdb->insert( $table, $data);
            $insert_id = $wpdb->insert_id;
        }
        
        return $insert_id ? $insert_id : null;

    }

    // Select Package 
    function product_package() {

        ?>
        <option value="" class="blank"><?php esc_html_e( 'Select Package', 'lmfwppt' ); ?></option>
        <?php
        if( isset($_POST['id']) ) {
            $package_list = LMFWPPT_ProductsHandler::get_packages($_POST['id']);
            $selected = isset( $_POST['selected'] ) ? sanitize_text_field( $_POST['selected'] ) : '';
            
            if( $package_list ) {
                foreach( $package_list as $result ):
                    $package_id = $result['package_id'];
                    $label = $result['label'];
                    ?>
                    <option value="<?php echo $package_id; ?>"<?php selected( $selected, $package_id );?>><?php echo $label; ?></option> 
                    <?php 
                endforeach;
            }
        }
        die();
    }

    // License Key Genarate Ajax Hook
    function ajax_generate_license_key(){
        echo self::generate_license_key();
        die();
    }

    // License Key Genarate function
    public static function generate_license_key() {

        $prefix = LMFWPPT_SettingsHandler::lmfwppt_get_option('license_code_prefix');
        $limit = LMFWPPT_SettingsHandler::lmfwppt_get_option('license_code_character_limit');
        $key = wp_generate_password( $limit, false, false );
        return $prefix.$key;
    }

    // Delete License Id
    function lmfwppt_delete_license( $id ) {
        global $wpdb;

        return $wpdb->delete(
            $wpdb->prefix . 'lmfwppt_licenses',
            [ 'id' => $id ],
            [ '%d' ]
        );
    }

    // Get The Action
    function delete_license() {

        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "lmfwppt-delete-license" ){
            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'lmfwppt-delete-license' ) ) {
                wp_die( 'Are you cheating?' );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( 'Are you cheating?' );
            }

            $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0; 

            if ( $this->lmfwppt_delete_license( $id ) ) {
                $redirected_to = admin_url( 'admin.php?page=license-manager-wppt-licenses&deleted=true' );
            } else {
                $redirected_to = admin_url( 'admin.php?page=license-manager-wppt-licenses&deleted=false' );
            }

            wp_redirect( $redirected_to );
            exit;

        }    
    } 

    public static function get_licenses_by_order_ids( $order_ids = array() ){

        $order_ids = wp_parse_args( $order_ids, array() );

        if( count( $order_ids ) < 1 ) {
            return;
        }

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_licenses WHERE 1=%d AND order_id IN ( " . implode(',', $order_ids) . " ) ORDER BY dated DESC", 1 );
        return $wpdb->get_results( $query, ARRAY_A );
    }

    function download_product( $posted_args = array(), $admin_check = true ){
        $response = array();

        if ( $admin_check && !is_user_logged_in() ) {
            die('Access Denied.');
        }

        if ( !is_array( $posted_args ) ) {
            return false;
        }

        $args = wp_parse_args( $posted_args, array(
            'product_slug' => '',
            'license_key' => '',
        ) );

        global $wpdb;

        // Check if expired
        $isExpired = $wpdb->get_var( $wpdb->prepare("SELECT IF(lck.end_date < NOW(),TRUE,FALSE) as isExpired FROM {$wpdb->prefix}lmfwppt_licenses as lck WHERE license_key = %s ", $args['license_key']) );
        if( $isExpired ) {
            die('Access Denied.');
        }

        
        $get_package = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_licenses WHERE license_key = %s ", $args['license_key']) );

        $download_link = $wpdb->get_var( $wpdb->prepare("SELECT p.download_link FROM {$wpdb->prefix}lmfwppt_license_packages as lp INNER JOIN {$wpdb->prefix}lmfwppt_products as p ON p.id = lp.product_id WHERE lp.package_id = %s AND slug = %s", $get_package->package_id, $args['product_slug']) );

        $downloadedFileName = basename($download_link);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $download_link);
        $downloadedFile = fopen($downloadedFileName, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $downloadedFile);
        curl_exec ($ch);

        curl_close ($ch);
        fclose($downloadedFile);
        unlink($downloadedFile);

    

        // Download 
        if( $download_link ) {
            //readfile($download_link);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($download_link));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($download_link));
            readfile($downloadedFileName);
            
        }


        exit;

    }

}

new LMFWPPT_LicenseHandler();
