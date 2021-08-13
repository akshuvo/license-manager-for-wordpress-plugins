 

<div class="wrap">
   <h1><?php _e( 'SDK Generator', 'lmfwppt' ); ?></h1>

      <form action="" method="post" id="sdk-generator-add-form">
         
         <table class="form-table" role="presentation">
            <tbody>
               <tr>
                  <th><label for="product_type"><?php esc_html_e( 'Select Product Type', 'lmfwppt' ); ?></label></th>
                  <td>
                     <select name="product_type" id="product_type" required>
                        <option value="" selected>Select Product Type</option>
                        <option value="Theme"><?php esc_html_e( 'Theme', 'lmfwppt' ); ?></option>
                        <option value="Plugin"><?php esc_html_e( 'Plugin', 'lmfwppt' ); ?></option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label for="product_type"><?php esc_html_e( 'Select Product', 'lmfwppt' ); ?></label></th>
                  <td>
                     <select name="select_product" id="select_product" required>
                        <option value="" selected>Select Product</option>
                        <option value="product_1"><?php esc_html_e( 'Product-1', 'lmfwppt' ); ?></option>
                        <option value="product_2"><?php esc_html_e( 'Product-2', 'lmfwppt' ); ?></option>
                     </select>
                  </td>
               </tr>
                
              
            </tbody>
         </table>
         <input type="hidden" name="lmaction" value="sdk_generator_add_form">
         <?php submit_button( __( 'Generate', 'lmfwppt' ), 'primary' ); ?> 
      </form>

</div>