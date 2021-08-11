<?php
 
$product_defaults_args = array (
    'license_key' => '',
    'product_type' => '',
    'product_list' => '',
    'order_id' => '',
    'package_id' => '',
    'end_date' => '',
);


$get_product = array();
$get_packages = null;

if ( isset( $_GET['action'] ) && $_GET['action'] == "edit" && isset( $_GET['id'] ) ) {
    $license_id = intval( $_GET['id'] );

    // Get Product date 
    $get_product = LMFWPPT_LicenseHandler::get_license( $license_id );

    // Get packages data
    $get_packages = LMFWPPT_ProductsHandler::get_packages( $license_id );

}

// Parse incoming $args into an array and merge it with $defaults
$get_product = wp_parse_args( $get_product, $product_defaults_args );
// Let's extract the array to variable
extract( $get_product );

if ( $end_date ) {
    $end_date = date('Y-m-d', strtotime($end_date));
}

?>
<div class="wrap">

    <div class="lmwppt-wrap">

        <div class="lmwppt-inner-card card-shameless">
            <?php if( isset( $_GET['id'] ) ) : ?>
                <h1><?php _e( 'Edit License', 'lmfwppt' ); ?></h1>
            <?php else : ?>
                <h1><?php _e( 'Add New License', 'lmfwppt' ); ?></h1>
            <?php endif; ?>
        </div>

        <form action="" method="post" id="license-add-form">
            
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-section" id="product-information">
                    <h2><?php esc_html_e( 'Product Information', 'lmfwppt' ); ?></h2>

                    <div class="lmfwppt-form-field">
                        <label for="download_link"><?php esc_html_e( 'License Key', 'lmfwppt' ); ?></label>
                        <div class="lmfwppt-file-field">
                            <input type="text" name="lmfwppt[license_key]" id="license_key" class="regular-text" placeholder="<?php esc_attr_e( 'License Key', 'lmfwppt' ); ?>" value="<?php echo esc_attr( $license_key ); ?>" readonly>
                            <button class="button" type="button" id="generate_key"><?php esc_html_e( 'Generate Key', 'lmfwppt' ); ?></button>
                        </div>
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="order_id"><?php esc_html_e( 'Order ID', 'lmfwppt' ); ?></label>
                         
                        <input type="number" name="lmfwppt[order_id]" id="order_id" class="regular-text" placeholder="Order ID" value="<?php echo esc_attr( $order_id ); ?>">
                    </div>
                    <div class="lmfwppt-form-field">
                        <label for="product_type"><?php esc_html_e( 'Product Type', 'lmfwppt' ); ?></label>
                        <select name="lmfwppt[product_type]" id="product_type">

                            <option value=" " selected>Select Product Type</option>

                            <option value="Theme" <?php selected( $product_type, 'Theme' ); ?> ><?php esc_html_e( 'Theme', 'lmfwppt' ); ?></option>

                            <option value="Plugin" <?php selected( $product_type, 'Plugin' ); ?> ><?php esc_html_e( 'Plugin', 'lmfwppt' ); ?></option>
                        </select>
                    </div>
                    
                    <!-- Theme Product List -->
                    <div class="lmfwppt-form-field" id="lmfwppt_theme_products">
                        <label for="product_theme_list"><?php esc_html_e( 'Theme Product List', 'lmfwppt' ); ?></label>
                        <select name="lmfwppt[product_theme_list]" class="products_list" id="product_theme_list">
                            <option value=" " selected>Select Product</option>
                            <?php
                                 $items = lmfwppt_get_product_list("theme");
                                 foreach ($items as $products_list):?>
                                    
                            <option value="<?php echo $products_list->id; ?>"><?php echo $products_list->name; ?></option>
                        <?php endforeach; ?>

                        </select>
                    </div>
                    

                    <!-- Plugin Product List -->
                    <div class="lmfwppt-form-field" id="lmfwppt_plugin_products">
                        <label for="product_plugin_list"><?php esc_html_e( 'Plugin Product List', 'lmfwppt' ); ?></label>
                        <select name="lmfwppt[product_plugin_list]" class="products_list" id="product_plugin_list">
                            <option value=" " selected>Select Product</option>
                            <?php
                                $items = lmfwppt_get_product_list("plugin");
                                foreach ($items as $products_list):?>
                                    
                            <option value="<?php echo $products_list->id; ?>"><?php echo $products_list->name; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                     
                     <!--  License Package -->
                    <div class="lmfwppt-form-field" id="lmfwppt_license_package">
                        <label for="lmfwppt_theme_package">Select Package</label>
                        <select name="lmfwppt[package_id]" id="lmfwppt_package_list">
                             
                        </select>
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="end_date"><?php esc_html_e( 'License End Date', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[end_date]" id="end_date" class="regular-text product_name_input" placeholder="License End Date" value="<?php echo esc_attr( $end_date ); ?>" required>
                    </div>
                </div>
            </div>
          
            <div class="lmwppt-inner-card lmfwppt-buttons card-shameless">
                <input type="hidden" name="lmaction" value="license_add_form">
                <input type="hidden" name="lmfwppt[created_by]" value="<?php _e( get_current_user_id() ); ?>">
                
                <?php if( isset( $license_id ) ) : ?>
                    <input type="hidden" name="lmfwppt[license_id]" value="<?php _e( $license_id ); ?>">
                <?php endif; ?>
                
                <?php wp_nonce_field( 'lmfwppt-add-product-nonce' ); ?>
                <?php submit_button( __( 'Add License', 'lmfwppt' ), 'primary', 'add_license' ); ?>
            </div>
        </form>

    </div>
 
</div>

 