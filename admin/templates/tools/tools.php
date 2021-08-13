 

<div class="wrap">
   <h1><?php _e( 'SDK Generator', 'lmfwppt' ); ?></h1>

      <form action="" method="post" id="sdk-generator-add-form">
         
         <table class="form-table" role="presentation">
            <tbody>
               <tr>
                  <th><label for="product_type"><?php esc_html_e( 'Select Product Type', 'lmfwppt' ); ?></label></th>
                  <td>
                     <select name="product_type" class="product_type" id="product_type" required>
                      <option value=" " selected>Select Product Type</option>
                      <option value="Theme"><?php esc_html_e( 'Theme', 'lmfwppt' ); ?></option>
                      <option value="Plugin"><?php esc_html_e( 'Plugin', 'lmfwppt' ); ?></option>
                  </select>
                  </td>
               </tr>
               <tr class="lmfwppt_theme_products">
                  <th><label for="product_theme_list"><?php esc_html_e( 'Select Product', 'lmfwppt' ); ?></label></th>
                  <td>
                   <select name="select_product" class="select_product" required>
                        <option value="" selected>Select Product</option>
                        <?php
                           $items = lmfwppt_get_product_list("theme");
                           foreach ($items as $products_list):?>
                                 
                        <option value="<?php echo $products_list->id; ?>"><?php echo $products_list->name; ?></option>
                     <?php endforeach; ?>

                     </select>
                  </td>
               </tr>
               <tr class="lmfwppt_plugin_products">
                  <th><label for="product_plugin_list"><?php esc_html_e( 'Select Product', 'lmfwppt' ); ?></label></th>
                  <td>
                     <select name="select_product" class="select_product" required>
                            <option value="" selected>Select Product</option>
                            <?php
                                $items = lmfwppt_get_product_list("plugin");
                                foreach ($items as $products_list):?>
                                    
                            <option value="<?php echo $products_list->id; ?>"><?php echo $products_list->name; ?></option>
                        <?php endforeach; ?>
                     </select>
                  </td>
               </tr>
              
            </tbody>
         </table>
         <input type="hidden" name="lmaction" value="sdk_generator_add_form">
         <?php submit_button( __( 'Generate', 'lmfwppt' ), 'primary' ); ?> 
      </form>

</div>

