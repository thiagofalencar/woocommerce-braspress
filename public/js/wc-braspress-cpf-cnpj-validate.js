(function( $ ) {
	'use strict';

	var elem_cnpj	= '.cpf_cnpj>input',
    	options 	= {
        onKeyPress: function (cpf_cnpj, e, field, options) {
	            var masks = ['000.000.000-000', '00.000.000/0000-00'],
	                mask = (cpf_cnpj.length > 14) ? masks[1] : masks[0];

	 			$(elem_cnpj).mask(mask, options);
        	}
    	},
    	initial_mask = '000.000.000-000000';

    $('.cart-collaterals').on('click', 
        function () {
            var is_braspress    = false,
                selectd_method  = $(this).find('.shipping_method[checked=checked]').attr('id');

            $.each( 
                [
                    'braspress-rodoviario',
                    'braspress-rodoviario_fob',
                    'braspress-aeropress',
                    'braspress-aeropress_fob'
                ],
                function( index, value ) {
                  is_braspress = ( selectd_method.indexOf(value) > 0 ) ? true : is_braspress;
                }
            );

            if ( !$('#calc_shipping_cpf').length && is_braspress ) {
                $('form.woocommerce-shipping-calculator p:last').before(
                    '<p class="form-row form-row-wide cpf_cnpj" id="calc_shipping_cpf_field">' +
                    '   <input type="text" class="input-text" value="" placeholder="CPF / CNPJ" name="calc_shipping_cpf" id="calc_shipping_cpf">' +
                    '</p>'
                );
            };

            if ( $(elem_cnpj).length && $(elem_cnpj).val() == '' ) {
            	$(elem_cnpj).mask(initial_mask, options);
            }
        }
    );
    
    $(elem_cnpj).mask(initial_mask, options);

})( jQuery );