<?php 
   $lmfwppt_settings = get_option( 'lmfwppt_settings' );
   $code_prefix = isset( $lmfwppt_settings['license_code_prefix'] ) ? $lmfwppt_settings['license_code_prefix'] : null;
   $character_limit = isset( $lmfwppt_settings['license_code_character_limit'] ) ? $lmfwppt_settings['license_code_character_limit'] : null;

?>


<div class="wrap">
   <h1 class="wp-heading-inline"><?php _e( 'Settings Page', 'lmfwppt' ); ?></h1>
</div>



<div class="wrap">
   <h1><?php _e( 'Add Setting', 'lmfwppt' ); ?></h1>

      <form action="" method="post" id="setting-add-form">
         
         <table class="form-table" role="presentation">
            <tbody>
               <tr>
                  <th scope="row"><label for="license_code_prefix">License Code Prefix</label></th>
                  <td><input type="text" name="lmfwppt_settings[license_code_prefix]" id="license_code_prefix" class="regular-text" placeholder="<?php esc_attr_e( 'License Code Prefix', 'lmfwppt' ); ?>" value="<?php echo $code_prefix  ?>"></td>
               </tr>
               <tr>
                  <th scope="row"><label for="license_code_character_limit">License Code Character Limit</label></th>
                  <td><input type="text" name="lmfwppt_settings[license_code_character_limit]" id="license_code_character_limit" class="regular-text" placeholder="<?php esc_attr_e( 'License Code Character Limit', 'lmfwppt' ); ?>" value="<?php echo $character_limit; ?>"></td>
               </tr>
               <tr>
                  <th>
                     <div class="lmwppt-inner-card lmfwppt-buttons card-shameless">
                        <input type="hidden" name="lmaction" value="setting_add_form">
                        <input type="hidden" name="lmfwppt[created_by]" value="<?php _e( get_current_user_id() ); ?>">
                         
                        <?php if( isset( $license_id ) ) : ?>
                             <input type="hidden" name="lmfwppt[setting_id]" value="<?php _e( $license_id ); ?>">
                        <?php endif; ?>
                         
                        <?php wp_nonce_field( 'lmfwppt-add-product-nonce' ); ?>
                        <?php submit_button( __( 'Add Setting', 'lmfwppt' ), 'primary', 'add_license' ); ?>
                     </div>
                  </th>
               </tr>
            </tbody>
         </table>      
      </form>

</div>
