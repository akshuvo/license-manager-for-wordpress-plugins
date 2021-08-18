(function($) {
	"use strict";
	$(document).ready(function(){
         
		// Add License Field
        $(document).on('click', '.add-license-information', function(){
            var $this = $(this);

            var keyLen = jQuery('.lmfwppt_license_field').length;

            var data = {
                action: 'lmfwppt_single_license_field',
                key: keyLen,
                thiskey: keyLen,
            }

            $.ajax({
              url: ajaxurl,
              type: 'post',
              data: data,
              beforeSend : function ( xhr ) {
                $this.prop('disabled', true);
              },
              success: function( res ) {
                $this.prop('disabled', false);

                // Data push
                $('#license-information-fields').append(res);
              },
              error: function( result ) {
                $this.prop('disabled', false);
                console.error( result );
              }
            });
        });

        // Delete field
        $(document).on('click', 'span.delete_field', function(e){
            e.preventDefault();
            $(this).closest('.lmfwppt_license_field').remove();
            return false;
        });

        // Field toggle
        $(document).on('click', '.lmfwppt-toggle-head', function(e){
        	e.preventDefault();
            $(this).parent().toggleClass('opened').find('.lmfwppt-toggle-wrap').slideToggle('fast');
            return false;
        });

        // Add Section Field 
        $(document).on('click', '.add-section-information', function(){
            var $this = $(this);

            var keyLen = jQuery('.lmfwppt_license_field').length;

            var data = {
                action: 'lmfwppt_single_section_field',
                key: keyLen,
                thiskey: keyLen,
            }

            $.ajax({
              url: ajaxurl,
              type: 'post',
              data: data,
              beforeSend : function ( xhr ) {
                $this.prop('disabled', true);
              },
              success: function( res ) {
                $this.prop('disabled', false);

                // Data push
                $('#section-information-fields').append(res);
              },
              error: function( result ) {
                $this.prop('disabled', false);
                console.error( result );
              }
            });
        });


        // Add File
        var file_frame, pushSelector, getFrameTitle;
        $(document).on('click', '.trigger_media_frame', function(){
            var $this = $(this);

            // Set Selector
            pushSelector = $this.attr('data-push_selector');

            if ( undefined !== file_frame ) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select The Appropriate File',
                //frame:    'post',
                //state:    'insert',
                multiple: false,
                library: {
                    //type: [ 'zip' ]
                },
                button: {text: 'Insert'}
            });

            file_frame.on( 'select', function(e) {

                var attachment = file_frame.state().get('selection').first().toJSON();

                $(pushSelector).val( attachment.url );

            });

            // Now display the actual file_frame
            file_frame.open();

        });

        // Add Product
        $(document).on('submit', '#product-form', function(e) {
            e.preventDefault();
            let $this = $(this);

            let formData = new FormData(this);
            formData.append('action', 'product_add_form');

            // Get Product type
            let productType = jQuery('#product_type').val();

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {
                    $this.find('.spinner').addClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', true);
                    $(document).trigger("lmfwppt_notice", ['', 'remove']);

                },
                complete: function(data) {
                    $this.find('.spinner').removeClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', false);
                },
                success: function(data) {
                    // Success Message and Redirection
                    if ( jQuery('.lmfwppt_edit_id').val() ) {
                        $(document).trigger("lmfwppt_notice", ['Product updated successfully.', 'success']);
                    } else {
                        $(document).trigger("lmfwppt_notice", ['Product added successfully. Redirecting...', 'success']);
                        window.location = '/wp-admin/admin.php?page=license-manager-wppt-'+productType+'s&action=edit&id='+data+'&message=1';
                    }

                },
                error: function(data) {
                    $(document).trigger("lmfwppt_notice", ['Something went wrong. Try again.', 'error']);

                },

            });

        });

        // Add License
        $(document).on('submit', '#license-add-form', function(e) {
            e.preventDefaultlet
            let $this = $(this);

            let formData = new FormData(this);
            formData.append('action', 'license_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {
                    $this.find('.spinner').addClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', true);
                    $(document).trigger("lmfwppt_notice", ['', 'remove']);
                },
                complete: function(data) {
                    $this.find('.spinner').removeClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', false);
                },
                success: function(data) {
                    // Success Message and Redirection
                    if ( jQuery('.lmfwppt_edit_id').val() ) {
                        $(document).trigger("lmfwppt_notice", ['License updated successfully.', 'success']);
                    } else {
                        $(document).trigger("lmfwppt_notice", ['License added successfully. Redirecting...', 'success']);
                        window.location = '/wp-admin/admin.php?page=license-manager-wppt-licenses&action=edit&id='+data+'&message=1';
                    }

                },
                error: function(data) {
                    $(document).trigger("lmfwppt_notice", ['Something went wrong. Try again.', 'error']);

                },

            });

        });

        // Add package
        $(document).on('change', '.products_list', function(e, is_edit){
            
            $(".lmfwppt_license_package").show();
            let id = $(this).val();
            let selected = $('#lmfwppt_package_list').attr('data-pack_value'); 

            $.ajax({
                type:"POST",
                url: ajaxurl,
                data:{
                    action:'package_id',
                    id:id,
                    selected:selected
                },
                cache:false,
                success:function(data){
                     if( data ){
                        $("#lmfwppt_package_list").html( data );

                        // handle edit
                        if ( is_edit ) {
                            $("#lmfwppt_package_list").find( 'option[value="'+selected+'"]' ).prop('selected', 1);
                        }
                     }
                },
                error:function(data){
                    console.log(data);
                }
            });
        });
      
        // Generate License Key
        $(document).on('click', '#generate_key', function(e){
            e.preventDefault();
            let $this = $(this);

            $.ajax({
                type:'POST',
                url:ajaxurl,
                data:{
                    action:'license_key',
                },
                cache:false,
                beforeSend: function(data) {
                    $this.find('.spinner').addClass('is-active').show();  
                    $('#generate_key').prop('disabled', true).find('.generate-key-label').hide();  
                },
                complete: function(data) {
                    $this.find('.spinner').removeClass('is-active').hide();
                    $('#generate_key').prop('disabled', false).find('.generate-key-label').show();
                },
                success:function(data){
                    if(data){
                        $("#license_key").val(data);
                    }
                }
            })
        });

        //space remove dash add
        $(document).on('keyup', '#slug', function(e) {
            e.preventDefault();
            let value = $(this).val().replace(" ", "-");
            $(this).val(value);
            
        });

        $(document).on('change', '#product_type', function(e, is_edit) {
            let thisVal = $(this).val();
            
            $(".theme-opt").hide();
            $(".plugin-opt").hide();

            if ( !is_edit ) {
                jQuery('.products_list').val('');
            }

            if(thisVal == "theme"){
                $(".theme-opt").show();
                $(".plugin-opt").hide();
                 
            } else if(thisVal == "plugin"){
                $(".plugin-opt").show();
                $(".theme-opt").hide();
            }
        });

        jQuery('#product_type').trigger('change',['true']);
        jQuery('.products_list').trigger('change',['true']);
        

        // Add Setting
        $(document).on('submit', '#setting-add-form', function(e) {
            e.preventDefault();
            let $this = $(this);

            let formData = new FormData(this);
            formData.append('action', 'setting_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {
                    $this.find('.spinner').addClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', true);
                    $(document).trigger("lmfwppt_notice", ['', 'remove']);
                },
                complete: function(data) {
                    $this.find('.spinner').removeClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', false);
                },
                success: function(data) {
                    $(document).trigger("lmfwppt_notice", ['Setting updated', 'success']);
                },
                error: function(data) {
                    $(document).trigger("lmfwppt_notice", ['Something went wrong. Try again.', 'error']);

                },

            });

        });

        // Add SDK Generator
        $(document).on('submit', '#sdk-generator-add-form', function(e) {
            e.preventDefault();
            let product_type = $('.product_type').val();
            let select_product = $('.select_product').val();
            if( (product_type == '') || (select_product == '') ){
                return;
            }
            var $this = $(this);
            var formData = new FormData(this);
            formData.append('action', 'sdk_generator_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {
                    $this.find('.spinner').addClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', true);
                },
                complete: function(data) {
                    $this.find('.spinner').removeClass('is-active');
                    $this.find('[type="submit"]').prop('disabled', false);
                },
                success: function(data) {
                    console.log(data);

                },
                error: function(data) {
                    console.log(data);

                },

            });

        });

        // Parent slug input field hide show
        $(document).on('change', '#lmfwppt_menu_select', function(e) {
            
            let menu = $(this).val(); 
            if(menu == "sub_menu"){
                $(this).closest('.lmfwppt-form-section').find('.parent-slug-menu').show();
            } else{
                $(this).closest('.lmfwppt-form-section').find('.parent-slug-menu').hide();
            }
        });

        // Value set
        $(document).on('change', '.products_list', function(e) {

            let product_name = $(this).find("option:selected").text();

            if( !$(this).val() ){
                $(".lmfwppt_page_title").val('');
                $(".lmfwppt_menu_title").val('');
                return;
            }
            if(product_name){
                $(".lmfwppt_page_title").val(product_name+' License Activation');
                $(".lmfwppt_menu_title").val(product_name+' License');
            }
            
            console.log(product_name);
        });

        // Notice Messages show script
        $(document).on("lmfwppt_notice", function(event, notice, type) {
            
            if(type == "remove"){
                $('.lmfwppt-notices').html('');
                    return;
            }
            let notice_html = '<div class="notice notice-alt is-dismissible notice-'+type+'"><p>'+notice+'</p></div>';
            $('.lmfwppt-notices').html(notice_html);
            jQuery(document).trigger('wp-updates-notice-added');
        
        });

	});
})(jQuery);

