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
        var file_frame;
        $(document).on('click', '#download_link_button', function(){
            var $this = $(this);

            if ( undefined !== file_frame ) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select Theme/Plugin File',
                //frame:    'post',
                //state:    'insert',
                multiple: false,
                library: {
                    //type: [ 'zip' ]
                },
                button: {text: 'Insert'}
            });

            file_frame.on( 'select', function() {

                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#download_link').val( attachment.url );

            });

            // Now display the actual file_frame
            file_frame.open();

        });

        // Add Product
        $(document).on('submit', '#product-form', function(e) {
            e.preventDefault();
            var $this = $(this);

            var formData = new FormData(this);
            formData.append('action', 'product_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {


                },
                complete: function(data) {

                },
                success: function(data) {


                    //var response = JSON.parse(data);


                    console.log(data);

                },
                error: function(data) {
                    console.log(data);

                },

            });

        });

        // Add License
        $(document).on('submit', '#license-add-form', function(e) {
            e.preventDefault();
            var $this = $(this);

            var formData = new FormData(this);
            formData.append('action', 'license_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {

                },
                complete: function(data) {

                },
                success: function(data) {
                    console.log(data);

                },
                error: function(data) {
                    console.log(data);

                },

            });

        });

        // Add package
        $(document).on('change', '.products_list', function(e){
            $("#lmfwppt_license_package").show();
            var id = $(this).val();

            $.ajax({
                type:"POST",
                url: ajaxurl,
                data:{
                    action:'package_id',
                    id:id
                },
                cache:false,
                success:function(data){
                     if( data ){
                        $("#lmfwppt_package_list").html( data )
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

            $.ajax({
                type:'POST',
                url:ajaxurl,
                data:{
                    action:'license_key',
                },
                cache:false,
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
            var value = $(this).val().replace(" ", "-");
            $(this).val(value);
            
        });

        function product_type() {
            var singleValues = $( "#product_type" ).val();
            if(singleValues == "Theme"){
                $(".lmfwppt_theme_products").show();
                $(".lmfwppt_plugin_products").hide();
                 
            } 
            else if(singleValues == "Plugin"){
                $(".lmfwppt_plugin_products").show();
                $(".lmfwppt_theme_products").hide();
                 
            }
        }
        $( "select" ).change( product_type );
        product_type();

        // Add Setting
        $(document).on('submit', '#setting-add-form', function(e) {
            e.preventDefault();
            var $this = $(this);

            var formData = new FormData(this);
            formData.append('action', 'setting_add_form');

            $.ajax({
                type: 'post',
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(data) {

                },
                complete: function(data) {

                },
                success: function(data) {
                    console.log(data);

                },
                error: function(data) {
                    console.log(data);

                },

            });

        });

        // Add SDK Generator
        $(document).on('submit', '#sdk-generator-add-form', function(e) {
            e.preventDefault();
            var product_type = $('.product_type').val();
            var select_product = $('.select_product').val();
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

                },
                complete: function(data) {

                },
                success: function(data) {
                    console.log(data);

                },
                error: function(data) {
                    console.log(data);

                },

            });

        });

	});
})(jQuery);

