 
<div class="wrap">
      <div class="lmwppt-wrap">
         <div class="lmwppt-inner-card card-shameless">
            <h1><?php _e( 'SDK Generator', 'lmfwppt' ); ?></h1>
         </div>
         <form action="" method="post" id="sdk-generator-add-form">
            <div class="lmwppt-inner-card">
               <div class="lmfwppt-form-section">

                  <div class="lmfwppt-form-field">
                     <label for="product_type"><?php esc_html_e( 'Product Type', 'lmfwppt' ); ?></label>
                     <select name="product_type" class="product_type" id="product_type" required>
                        <option value="" selected><?php esc_html_e( 'Select Product Type', 'lmfwppt' ); ?></option>
                        <option value="theme"><?php esc_html_e( 'Theme', 'lmfwppt' ); ?></option>
                        <option value="plugin"><?php esc_html_e( 'Plugin', 'lmfwppt' ); ?></option>
                     </select>
                  </div>

                  <div class="lmfwppt-form-field">
                     <label for="select_product"><?php esc_html_e( 'Select Product', 'lmfwppt' ); ?></label>
                     <select id="select_product" name="select_product" class="select_product products_list" required>
                        <option value="" class="blank">Select Product</option>
                        <?php
                        $items = lmfwppt_get_product_list("theme");
                        foreach ($items as $products_list): ?>
                                 
                           <option value="<?php echo $products_list->id; ?>" class="theme-opt"><?php echo $products_list->name; ?></option>
                        <?php endforeach; ?>
                            
                        <?php
                        $items = lmfwppt_get_product_list("plugin");
                        foreach ($items as $products_list): ?>
                           <option value="<?php echo $products_list->id; ?>" class="plugin-opt"><?php echo $products_list->name; ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div class="lmfwppt-form-field">
                     <label for="lmfwppt_menu_select"><?php esc_html_e( 'Menu Type', 'lmfwppt' ); ?></label>
                      <select id="lmfwppt_menu_select" name="menu_type" class="menu_select" required>
                        <option value="menu">Menu</option>
                        <option value="sub_menu">Sub Menu</option>
                     </select>
                  </div>

                  <div class="lmfwppt-form-field parent-slug-menu hidden">
                     <label for="lmfwppt_parent_menu_slug"><?php esc_html_e( 'Parent Menu Slug', 'lmfwppt' ); ?></label>
                     <input type="text" list="parent_slug_list" name="parent_slug" id="lmfwppt_parent_menu_slug" class="regular-text" placeholder="Parent Menu Slug">
                     <datalist id="parent_slug_list">
                       <option value="index.php">
                       <option value="edit.php">
                       <option value="upload.php">
                       <option value="edit.php?post_type=page">
                       <option value="edit-comments.php">
                       <option value="themes.php">
                       <option value="plugins.php">
                       <option value="users.php">
                       <option value="tools.php">
                       <option value="options-general.php">
                     </datalist>
                  </div>

                  <div class="lmfwppt-form-field">
                     <label for="lmfwppt_page_title"><?php esc_html_e( 'Page Title', 'lmfwppt' ); ?></label>
                     <input type="text" name="page_title" id="lmfwppt_page_title" class="regular-text" placeholder="Page Title">
                  </div>

                  <div class="lmfwppt-form-field">
                     <label for="lmfwppt_menu_title"><?php esc_html_e( 'Menu Title', 'lmfwppt' ); ?></label>
                     <input type="text" name="menu_title" id="lmfwppt_menu_title" class="regular-text" placeholder="Menu Title">
                  </div>

               </div>
            </div>
            <div class="lmwppt-inner-card lmfwppt-buttons card-shameless">
               
               <div class="submit_btn_area"> 
                  <input type="hidden" name="lmaction" value="sdk_generator_add_form">
                  <?php wp_nonce_field( 'lmfwppt_nonce' ); ?>
                  <?php submit_button( __( 'Generate', 'lmfwppt' ), 'primary' ); ?> 
                  <span class="spinner"></span>
               </div>
               <div class="lmfwppt-notices"></div>  
            </div>
         </form>
      </div>
</div>