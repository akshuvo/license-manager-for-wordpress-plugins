<?php
$product_defaults_args = array (
    'name' => '',
    'slug' => '',
    'product_type' => '',
    'version' => '',
    'tested' => '',
    'requires' => '',
    'requires_php' => '',
    'download_link' => '',
    'banners' => '',
    'sections' => '',
    'author' => '',
    'created_by' => '',
    'dated' => '',
);


$get_product = array();
$get_packages = null;

$submit_button_label = __( 'Add Product', 'lmfwppt' );

if ( isset( $_GET['action'] ) && $_GET['action'] == "edit" && isset( $_GET['id'] ) ) {
    $product_id = intval( $_GET['id'] );

    // Get Product date 
    $get_product = LMFWPPT_ProductsHandler::get_product( $product_id );

    // Get packages data
    $get_packages = LMFWPPT_ProductsHandler::get_packages( $product_id );

    $submit_button_label = __( 'Edit Product', 'lmfwppt' );

}

// Parse incoming $args into an array and merge it with $defaults
$get_product = wp_parse_args( $get_product, $product_defaults_args );
// Let's extract the array to variable
extract( $get_product );

// banners unserialize
$banner = unserialize( $banners );
$low = isset ( $banner['low'] ) ? $banner['low'] : '';
$high = isset ( $banner['high'] ) ? $banner['high'] : '';

// sections unserialize
$sections_arr = unserialize($sections);

?>
<div class="wrap">
    <div class="lmwppt-wrap">

        <div class="lmwppt-inner-card card-shameless">
            <?php if( isset( $_GET['id'] ) ) : ?>
                <h1><?php _e( 'Edit Product', 'lmfwppt' ); ?></h1>
            <?php else : ?>
                <h1><?php _e( 'Add New Product', 'lmfwppt' ); ?></h1>
            <?php endif; ?>
        </div>

        <form action="" method="post" id="product-form">
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-section" id="product-information">
                    <h2><?php esc_html_e( 'Product Information', 'lmfwppt' ); ?></h2>

                    <div class="lmfwppt-form-field">
                        <label for="name"><?php esc_html_e( 'Product Name', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[name]" id="name" class="regular-text product_name_input" placeholder="Your Theme or Plugin Name" value="<?php echo esc_attr( $name ); ?>" required>
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="slug"><?php esc_html_e( 'Product Slug', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[slug]" id="slug" class="regular-text product_slug_input" placeholder="your-theme-or-plugin-name" value="<?php echo esc_attr( $slug ); ?>" required>
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="product_type"><?php esc_html_e( 'Product Type', 'lmfwppt' ); ?></label>
                        <select name="lmfwppt[product_type]" id="product_type">
                            <option value="plugin" <?php selected( $product_type, 'plugin' ); ?> ><?php esc_html_e( 'Plugin', 'lmfwppt' ); ?></option>
                            <option value="theme" <?php selected( $product_type, 'theme' ); ?> ><?php esc_html_e( 'Theme', 'lmfwppt' ); ?></option>
                        </select>
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="product_version"><?php esc_html_e( 'Product Version', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[version]" id="product_version" class="regular-text" placeholder="1.0" value="<?php echo esc_attr( $version ); ?>">
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="product_tested"><?php esc_html_e( 'Tested up to', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[tested]" id="product_tested" class="regular-text" placeholder="<?php esc_attr_e( '5.7', 'lmfwppt' ); ?>" value="<?php echo esc_attr( $tested ); ?>">
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="requires"><?php esc_html_e( 'Requires WordPress Version', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[requires]" id="requires" class="regular-text" placeholder="<?php esc_attr_e( '4.7', 'lmfwppt' ); ?>" value="<?php echo esc_attr( $requires ); ?>">
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="requires_php"><?php esc_html_e( 'Requires PHP Version', 'lmfwppt' ); ?></label>
                        <input type="text" name="lmfwppt[requires_php]" id="requires_php" class="regular-text" placeholder="<?php esc_attr_e( '7.4', 'lmfwppt' ); ?>" value="<?php echo esc_attr( $requires_php ); ?>">
                    </div>

                    <div class="lmfwppt-form-field">
                        <label for="download_link"><?php esc_html_e( 'File URL', 'lmfwppt' ); ?></label>
                        <div class="lmfwppt-file-field">
                            <input type="text" name="lmfwppt[download_link]" id="download_link" class="regular-text" placeholder="<?php esc_attr_e( 'URL of the Theme/Plugin file', 'lmfwppt' ); ?>" value="<?php echo esc_attr( $download_link ); ?>">
                            <button class="button" type="button" id="download_link_button"><?php esc_html_e( 'Select File', 'lmfwppt' ); ?></button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-section" id="license-information">
                    <h2><?php esc_html_e( 'License Packages', 'lmfwppt' ); ?></h2>
                    <div id="license-information-fields">
                        <?php LMFWPPT_ProductsHandler::get_packages_html( $get_packages ); ?>
                    </div>
                    <button class="button add-license-information" type="button"><?php esc_html_e( 'Add License Package', 'lmfwppt' ); ?></button>
                </div>
            </div>
            <!-- banner -->
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-section" id="product-information">
                    <h2><?php esc_html_e( 'Banners', 'lmfwppt' ); ?></h2>

                    <div class="lmfwppt-form-field">
                        <div class="lmfwppt-file-field">
                            <input type="text" name="lmfwppt[banners][low]" id="banners_low" class="regular-text" placeholder="<?php esc_attr_e( 'Low', 'lmfwppt' ); ?>" value="<?php echo $low; ?>">
                            <button class="button" type="button" id="banners_low_link_button"><?php esc_html_e( 'Select File', 'lmfwppt' ); ?></button>
                        </div>
                    </div> 
                    <div class="lmfwppt-form-field">
                        <div class="lmfwppt-file-field">
                            <input type="text" name="lmfwppt[banners][high]" id="banners_high" class="regular-text" placeholder="<?php esc_attr_e( 'High', 'lmfwppt' ); ?>" value="<?php echo $high; ?>">
                            <button class="button" type="button" id="banners_high_link_button"><?php esc_html_e( 'Select File', 'lmfwppt' ); ?></button>
                        </div>
                    </div> 

                </div>
            </div>
            <!-- sections -->
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-section" id="license-information">
                    <h2><?php esc_html_e( 'Sections', 'lmfwppt' ); ?></h2>
                    <div id="section-information-fields">
                        <?php LMFWPPT_ProductsHandler::get_section_html( $sections_arr ); ?>
                    </div>
                    <button class="button add-section-information" type="button"><?php esc_html_e( 'Add Section Package', 'lmfwppt' ); ?></button>
                </div>
            </div>
            <div class="lmwppt-inner-card">
                <div class="lmfwppt-form-field">
                    <label for="author"><?php esc_html_e( 'Author', 'lmfwppt' ); ?></label>
                    <input type="text" name="lmfwppt[author]" id="author" class="regular-text product_name_input" placeholder="Author Name" value="<?php echo esc_attr( $author ); ?>">
                </div>
            </div>

            <div class="lmfwppt-buttons lmwppt-inner-card card-shameless">
                <input type="hidden" name="lmaction" value="product_add_form">
                <input type="hidden" name="lmfwppt[created_by]" value="<?php _e( get_current_user_id() ); ?>">
                
                <?php if( isset( $product_id ) ) : ?>
                    <input type="hidden" name="lmfwppt[product_id]" value="<?php _e( $product_id ); ?>">
                <?php endif; ?>
                
                <?php wp_nonce_field( 'lmfwppt-add-product-nonce' ); ?>
                <div class="submit_btn_area"> 
                    <?php submit_button( $submit_button_label, 'primary', 'submit_product_license' ); ?> 
                    <span class="spinner"></span>
                </div>    
            </div>
        </form>

    </div>
</div>

 