<?php
 
/**
 * The Menu handler class
 */
class LMFWPPT_ProductsHandler {

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'lmfwppt_license_field_after_wrap', [ $this, 'license_content' ], 10, 2 );
        
        // Add license field ajax
        add_action( 'wp_ajax_lmfwppt_single_license_field', [ $this, 'license_package_ajax_add_action' ] );

        // Add Section field ajax
        add_action( 'wp_ajax_lmfwppt_single_section_field', [ $this, 'section_package_ajax_add_action' ] );

        // Product add action
        add_action( 'wp_ajax_product_add_form', [ $this, 'product_add' ] );
        //add_action( 'init', [ $this, 'product_add' ] );

        // Product Delete
        add_action( 'admin_init', [ $this, 'delete_product' ] );

    }

    // License Package Field add
    function license_package_ajax_add_action(){

        $key = sanitize_text_field( $_POST['key'] );

        ob_start();

        echo self::license_package_field( array(
            'key' => $key,
            'thiskey' => $key,
        ) );

        $output = ob_get_clean();

        echo $output;

        die();
    }

    // Single license field
    public static function license_package_field( $args ){

        $defaults = array (
            'key' => '',
            'package_id' => '',
            'label' => '',
            'product_id' => '',
            'update_period' => '',
            'domain_limit' => ''
        );

        // Parse incoming $args into an array and merge it with $defaults
        $args = wp_parse_args( $args, $defaults );

        // Let's extract the array to variable
        extract( $args );

        // Array key
        //$key =  isset( $args['key'] ) ? $args['key'] : "";
        $key =  !empty( $package_id ) ? $package_id : wp_generate_password( 3, false );;
   
        $field_name = "lmfwppt[license_package][$key]";

        ob_start();
        do_action( 'lmfwppt_license_field_before_wrap', $args );
        ?>

        <div id="postimagediv" class="postbox lmfwppt_license_field"> <!-- Wrapper Start -->
            <a class="header lmfwppt-toggle-head" data-toggle="collapse">
                <span id="poststuff">
                    <h2 class="hndle">
                        <input type="text" class="regular-text" name="<?php esc_attr_e( $field_name ); ?>[label]" placeholder="<?php esc_attr_e( 'License Title: 1yr unlimited domain.', 'lmfwppt' ); ?>" value="<?php esc_attr_e( $label ); ?>" title="<?php esc_attr_e( 'Change title to anything you like. Make sure they are unique.', 'lmfwppt' ); ?>" required />
                        <span class="dashicons indicator_field"></span>
                        <span class="delete_field">&times;</span>
                    </h2>
                </span>
            </a>
            <div class="collapse lmfwppt-toggle-wrap">
                <div class="inside">
                    <table class="form-table">

                        <tr valign="top">
                            <th scope="row">
                                <div class="tf-label">
                                    <label for="<?php esc_attr_e( $field_name ); ?>-package_id"><?php esc_html_e( 'Package ID', 'lmfwppt' ); ?></label>
                                </div>
                            </th>
                            <td>
                                <input id="<?php esc_attr_e( $field_name ); ?>-package_id" class="regular-text" type="text" name="<?php esc_attr_e( $field_name ); ?>[package_id]" value="<?php echo esc_attr( $package_id ); ?>" placeholder="<?php echo esc_attr( 'enter-package-id', 'lmfwppt' ); ?>" required />
                                <p><?php esc_html_e( 'Enter a unique url friendly text. No special characters allowed.', 'lmfwppt' ); ?></p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <div class="tf-label">
                                    <label for="<?php esc_attr_e( $field_name ); ?>-update_period"><?php esc_html_e( 'Update Period', 'lmfwppt' ); ?></label>
                                </div>
                            </th>
                            <td>
                                <input id="<?php esc_attr_e( $field_name ); ?>-update_period" class="regular-text" type="number" min="1" name="<?php esc_attr_e( $field_name ); ?>[update_period]" value="<?php echo esc_attr( $update_period ); ?>" placeholder="<?php echo esc_attr( 'Enter in Days', 'lmfwppt' ); ?>" required/>
                                <p><?php esc_html_e( 'Leave empty for lifetime updates.', 'lmfwppt' ); ?></p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <div class="tf-label">
                                    <label for="<?php esc_attr_e( $field_name ); ?>-domain_limit"><?php esc_html_e( 'Domain Limit', 'lmfwppt' ); ?></label>
                                </div>
                            </th>
                            <td>
                                <input id="<?php esc_attr_e( $field_name ); ?>-domain_limit" class="regular-text" type="number" min="1" name="<?php esc_attr_e( $field_name ); ?>[domain_limit]" value="<?php echo esc_attr( $domain_limit ); ?>" placeholder="<?php echo esc_attr( 'How many domains allowed to get updates?', 'lmfwppt' ); ?>" required />
                                <p><?php esc_html_e( 'Leave empty for unlimited domain.', 'lmfwppt' ); ?></p>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        <!-- Wrapper end below -->
        </div>
        <?php
        $output = ob_get_clean();

        return do_action( 'lmfwppt_license_field_after_wrap', $output, $args );
    }

    // Section information Field add
    function section_package_ajax_add_action(){

        $key = sanitize_text_field( $_POST['key'] );

        ob_start();

        echo self::section_package_field( array(
            'key' => $key,
            'thiskey' => $key,
        ) );

        $output = ob_get_clean();

        echo $output;

        die();
    }

    // Single Section field
    public static function section_package_field( $args ){

        $defaults = array (
            'key' => '',
            'package_id' => '',
            'label' => '',
            'product_id' => '',
            'name' => '',
            'content' => '',
        );

        // Parse incoming $args into an array and merge it with $defaults
        $args = wp_parse_args( $args, $defaults );

        // Let's extract the array to variable
        extract( $args );

        // Array key
        //$key =  isset( $args['key'] ) ? $args['key'] : "";
        $key =  !empty( $package_id ) ? $package_id : wp_generate_password( 3, false );;
   
        $field_name = "lmfwppt[license_package][$key]";

        ob_start();
        do_action( 'lmfwppt_license_field_before_wrap', $args );
        ?>

        <div id="postimagediv" class="postbox lmfwppt_license_field"> <!-- Wrapper Start -->
            <a class="header lmfwppt-toggle-head" data-toggle="collapse">
                <span id="poststuff">
                    <h2 class="hndle">
                        <input id="<?php esc_attr_e( $field_name ); ?>-section_name" class="regular-text" type="text" name="<?php esc_attr_e( $field_name ); ?>[name]" value="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( 'Section Title', 'lmfwppt' ); ?>" required />
                        <span class="dashicons indicator_field"></span>
                        <span class="delete_field">&times;</span>
                    </h2>
                </span>
            </a>
            <div class="collapse lmfwppt-toggle-wrap">
                <div class="inside">
                    <table class="form-table">

                        <tr valign="top">
                             
                            <div class="section-content">
                                <label for="<?php esc_attr_e( $field_name ); ?>-section_content"><?php esc_html_e( 'Section Content', 'lmfwppt' ); ?></label>
                            </div>
                             
                            <td style="padding: 0; width: 100%;">
                                <textarea style="width: 100%;" id="<?php esc_attr_e( $field_name ); ?>-section_content" name="<?php esc_attr_e( $field_name ); ?>[content]" rows="6" cols="100" placeholder="<?php echo esc_attr( 'Section Content', 'lmfwppt' ); ?>"></textarea>  
                            </td>
                             
                        </tr>
                    </table>
                </div>
            </div>
        <!-- Wrapper end below -->
        </div>
        <?php
        $output = ob_get_clean();

        return do_action( 'lmfwppt_license_field_after_wrap', $output, $args );
    }
    
    // License Packages Content Render
    function license_content( $output, $args ){
        echo $output;
    }

    // Product add form action
    function product_add(){
        $response = array();
        $response['success'] = false;

        if ( isset( $_POST['lmaction'] ) && $_POST['lmaction'] == "product_add_form" ) {

            $product_id = isset( $_POST['lmfwppt'] ) ? $this->create_product( $_POST['lmfwppt'] ) : null;

            // Create Packages
            if ( isset( $_POST['lmfwppt']['license_package'] ) && count( $_POST['lmfwppt']['license_package'] ) > 0 ) {
                // Delete old data
                global $wpdb;
                $wpdb->delete( $wpdb->prefix.'lmfwppt_license_packages', array( 'product_id' => $product_id ) );
                foreach ( $_POST['lmfwppt']['license_package'] as $package ) {
                    $this->create_package( $package, $product_id );
                }
            }
        }

        die();
    }

    // Create product function
    function create_product( $post_data = array() ){
        global $wpdb;
        $table = $wpdb->prefix.'lmfwppt_products';
        $data = array(
            'name' => isset($post_data['name']) ? sanitize_text_field( $post_data['name'] ) : "",
            'slug' => isset($post_data['slug']) ? sanitize_text_field( $post_data['slug'] ) : "",
            'product_type' => isset($post_data['product_type']) ? sanitize_text_field( $post_data['product_type'] ) : "",
            'version' => isset($post_data['version']) ? sanitize_text_field( $post_data['version'] ) : "",
            'tested' => isset($post_data['tested']) ? sanitize_text_field( $post_data['tested'] ) : "",
            'requires' => isset($post_data['requires']) ? sanitize_text_field( $post_data['requires'] ) : "",
            'requires_php' => isset($post_data['requires_php']) ? sanitize_text_field( $post_data['requires_php'] ) : "",
            'download_link' => isset($post_data['download_link']) ? sanitize_text_field( $post_data['download_link'] ) : "",
            'banner' => isset($post_data['banners']) ? sanitize_text_field( $post_data['banners'] ) : "",
            'created_by' => isset($post_data['created_by']) ? intval( $post_data['created_by'] ) : "",
            'dated' => date('Y-m-d H:i:s'),
        );

        if ( isset( $post_data['product_id'] ) ) {
            $insert_id = intval( $post_data['product_id'] );
            $wpdb->update( $table, $data, array( 'id'=> $insert_id ) );
        } else {
            $wpdb->insert( $table, $data);
            $insert_id = $wpdb->insert_id;
        }
        
        return $insert_id ? $insert_id : null;

    }

    // Create package function
    function create_package( $post_data = array(), $product_id = null ){
        global $wpdb;
        $table = $wpdb->prefix.'lmfwppt_license_packages';

        $data = array(
            'product_id' => isset($product_id) ? intval( $product_id ) : null,
            'label' => isset($post_data['label']) ? sanitize_text_field( $post_data['label'] ) : "",
            'package_id' => isset($post_data['package_id']) ? sanitize_text_field( $post_data['package_id'] ) : "",
            'update_period' => isset($post_data['update_period']) ? intval( $post_data['update_period'] ) : "",
            'domain_limit' => isset($post_data['domain_limit']) ? intval( $post_data['domain_limit'] ) : "",
        );
        
        $wpdb->insert( $table, $data);
        $insert_id = $wpdb->insert_id;

        return $insert_id ? $insert_id : null;
    }

    // Get Product details by id
    public static function get_product( $id = null ){

        if( !$id ){
            return;
        }

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_products WHERE id = %d", $id);
        return $wpdb->get_row( $query, ARRAY_A );
    }

    // Get Product package details by product_id
    public static function get_packages( $product_id = null ){ 

        if( !$product_id ){
            return;
        }

        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}lmfwppt_license_packages WHERE product_id = %d", $product_id);
        return $wpdb->get_results( $query, ARRAY_A );
    }

    // Generate html from packages array
    public static function get_packages_html( $get_packages = null ){
        if( !$get_packages ){
            return;
        }

        foreach ($get_packages as $package) {
            self::license_package_field( array(
                'key' => $package['package_id'],
                'package_id' => $package['package_id'],
                'label' => $package['label'],
                'product_id' => $package['product_id'],
                'update_period' => $package['update_period'],
                'domain_limit' => $package['domain_limit']
            ) );
        }

    }

    // Generate html from Section array
    public static function get_section_html( $get_packages = null ){
        if( !$get_packages ){
            return;
        }

        foreach ($get_packages as $package) {
            self::section_package_field( array(
                'key' => $package['package_id'],
                'package_id' => $package['package_id'],
                'label' => $package['label'],
                'product_id' => $package['product_id'],
                'name' => $package['name'],
                'content' => $package['content']
            ) );
        }

    }

    // Get Id Product Delete
    function lmfwppt_delete_product_id( $id ) {
        global $wpdb;

        $deleted_package = $wpdb->delete(
            $wpdb->prefix . 'lmfwppt_license_packages',
            [ 'product_id' => $id ],
            [ '%d' ]
        );

        $deleted_product = $wpdb->delete(
            $wpdb->prefix . 'lmfwppt_products',
            [ 'id' => $id ],
            [ '%d' ]
        );

        if( $deleted_package && $deleted_product ){
            return true;
        } else {
            return false;
        }
        
    }

    // Get The Action
    function delete_product() {
        
        if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "lmfwppt-delete-product" ){
            if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'lmfwppt-delete-product' ) ) {
                wp_die( 'Are you cheating?' );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_die( 'Are you cheating?' );
            }

            $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0; 

            $active_page = $_REQUEST['redirect_url'];

            if ( $this->lmfwppt_delete_product_id( $id ) ) {
                $redirected_to = admin_url( 'admin.php?page='.$active_page.'&deleted=true' );
            } else {
                $redirected_to = admin_url( 'admin.php?page='.$active_page.'&deleted=false' );
            }

            wp_redirect( $redirected_to );
            exit;

            }    
        } 

}

new LMFWPPT_ProductsHandler();