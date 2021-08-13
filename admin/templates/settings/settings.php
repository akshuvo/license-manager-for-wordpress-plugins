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
            </tbody>
         </table>
         <input type="hidden" name="lmaction" value="setting_add_form">
         <?php submit_button( __( 'Save', 'lmfwppt' ), 'primary' ); ?>
      </form>

</div>
