(function( $ ) {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		/**
		 * On th click we toggle the visibility.
		 */
		$(document).on('click', '.open-rdw-head th', function(e) {
			e.preventDefault();
			$(this).closest('tr').nextUntil('.open-rdw-head').toggle();
		});

		/**
		 * This code is to support NinjaForms
		 */
		$(document).on('change', '.ninja-open-data-rdw', function() {
			var formID = $(this).closest('form').attr('id');
			if (formID == undefined) {
				var inputID = $(this).attr('id');
				$(this).closest('form').attr('id', inputID);
				$(this).attr('id', '');
				formID = inputID;
			}

			$('#' + formID + ' #open_rdw-loading').show();
			$('#' + formID + ' #open_rdw-error').hide();
			$('#' + formID + ' #open_rdw-accepted').hide();

			var kenteken = normalizeLicense($(this).val());
			var data = { action: 'get_open_rdw_data', kenteken: kenteken };

			$.post(ajax.ajax_url, data, function (res) {

				$('#open_rdw-loading').hide();
				if (res.errors) {
					$('#' + formID + ' #open_rdw-error').show();
				} else {
					$('#' + formID + ' #open_rdw-accepted').show();
				}

				$.each(res.result, function (name, value) {
					if (name !== 'kenteken') {
						$('input[placeholder="' + name + '"]').val(value).trigger('change');
					}
				});

				$(document).trigger('openrdw:loaded_data', res);
			});
		});

		/**
		 * This code is responsible for contact form 7 support and makes an ajax call to our plugin.
		 */
		$(document).on('change', '.open-data-rdw-hook', function () {
			var formID = $(this).closest('form').attr('id');

			if (formID == undefined) {
				var inputID = $(this).attr('id');
				$(this).closest('form').attr('id', inputID);
				$(this).attr('id', '');
				formID = inputID;
			}

			$('#' + formID + ' #open_rdw-loading').show();
			$('#' + formID + ' #open_rdw-error').hide();
			$('#' + formID + ' #open_rdw-accepted').hide();

			var kenteken = normalizeLicense($(this).val());
			var data = { action: 'get_open_rdw_data', kenteken: kenteken };

			$(document).trigger('openrdw:before_data_load', kenteken);

			$.post(ajax.ajax_url, data, function (res) {

				$('#open_rdw-loading').hide();
				if (res.errors) {
					$('#' + formID + ' #open_rdw-error').show();
				} else {
					$('#' + formID + ' #open_rdw-accepted').show();
				}

				$.each(res.result, function (name, value) {
					if (name !== 'kenteken') {
						$('#' + formID + ' input[name="' + name + '"]').val(value);
					}
				});

				$(document).trigger('openrdw:loaded_data', res);
			});
		});

		/**
		 * This code is responsible for Gravity Forms support.
		 */
		$(document).on('change', '.gf-open-data-rdw input', function() {
			var formID = $(this).closest('form').attr('id');

			$('#'+formID+' #open_rdw-loading').show();
			$('#'+formID+' #open_rdw-error').hide();
			$('#'+formID+' #open_rdw-accepted').hide();

			var kenteken = normalizeLicense($(this).val());
			var data = {action: 'get_open_rdw_data', kenteken: kenteken};

			$(document).trigger('openrdw:before_data_load', kenteken);

			$.post(ajax.ajax_url, data, function(res) {

				$('#open_rdw-loading').hide();
				if (res.errors) {
					$('#'+formID+' #open_rdw-error').show();
				} else {
					$('#'+formID+' #open_rdw-accepted').show();
				}

				$.each(res.result, function(name, value) {
					if (name !== 'kenteken') {
						$('#'+formID+' .rdw-field-'+name+' input').val(value).trigger('change');
					}
				});

				$(document).trigger('openrdw:loaded_data', res);
			});
		});

        /**
		 * This code is responsible for Quform support.
		 */
        $(document).on('change', '.quform-open-data-rdw', function() {

            var el = $(this).closest('form');
            var kenteken = normalizeLicense($(this).val());
            var data = {action: 'get_open_rdw_data', kenteken: kenteken};

    		$(document).trigger('openrdw:before_data_load', kenteken);

            $.post(ajax.ajax_url, data, function(res) {
                $.each(res.result, function(name, value) {
    				if (name !== 'kenteken') {
    					$(el).find('input.' + name).val(value).trigger('change');
    				}
    			});

    			$(document).trigger('openrdw:loaded_data', res);
            });
        });

        function normalizeLicense(license) {
            return license ? license.replace(/[^a-z0-9]/gi, '').toLowerCase() : '';
        }
	});
})( jQuery );
