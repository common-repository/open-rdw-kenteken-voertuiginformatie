(function( $ ) {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		/**
		 * Enables us to toggle the field categories
		 */
		$(document).on('click', '.rdw-expand-fields a', function(e) {
			e.preventDefault();
			$(this).next().slideToggle('fast', 'linear');
		});

		/**
		 * Enables us to select all checkboxes in one go per categorie
		 */
		$(document).on('click', '.rdw-sort-fields input[type=checkbox]', function(e) {
			$(this).parent().find('li input[type=checkbox]').prop('checked', $(this).is(':checked'));
			var sibs = false;

			$(this).closest('ul').children('li').each(function () {
				if($('input[type=checkbox]', this).is(':checked')) sibs=true;
			});

			$(this).parents('ul').prev().prop('checked', sibs);
		});

		/**
		 * Switches to clicked tab in the back-end when clicked.
		 */
		$(document).on('click', '.nav-tabs li', function(e) {
			e.preventDefault();
			var target = this;

			if (!$(this).hasClass('active')) {
				$($('.active').find('a').attr('href')).fadeOut('fast', 'linear', function() {
					$($(target).find('a').attr('href')).fadeIn('fast', 'linear');
					$('.active').removeClass('active');
					$(target).addClass('active');
				});
			}
		});

		$(document).on('click', '#app-container .open-rdw-kenteken-voertuiginformatie .save-changes', function(e) {
			e.preventDefault();

			if ($('#formatters-doc').is(':visible')) {
				return saveFormatters();
			}

			if ($('#dashboard-doc').is(':hidden')) {
				return;
			}

			$("body").css("cursor", "progress");

			var useCPT = false;
			if ($('#app-container #useCPT').is(':checked')) {
				useCPT = true;
			}

			var inputData = {
				license: $('#app-container #rdw_license').val(),
				useCPT: useCPT,
				cptKeyword: $('#app-container #cptKeyword').val(),
				brandKeyword: $('#app-container #brandKeyword').val(),
				modelKeyword: $('#app-container #modelKeyword').val(),
				cfLink: $('#app-container #cfLink').val(),
			};

			var data = {
				action: 'rdw_save_changes',
				options: inputData,
			};

			$.post(ajaxurl, data, function(res) {
				if (res.success == true) {
					$('#app-container .save-changes').css('background-color', '#46b450');
				}
			}).fail(function() {
				$('#app-container .save-changes').css('background-color', '#d54e21');
			}).always(function() {
				$("body").css("cursor", "default");
				setTimeout(function() {
					$('#app-container .save-changes').css('background-color', '#428bca');
				},2000);
			});
		});

		function addFormatterRow(e) {
			e.preventDefault();

			var fieldName = $('select[name=rdw_field_name]').val();
			var type = $('select[name=rdw_formatter_type]').val();

			if (fieldName == '' || type == '') {
				return alert('Ongeldige veldnaam of formatter type');
			}

			// Remove the table header, remove the hidden blueprint.
			var formatterCount = $('.formatter_table tr').length - 2;
			var inputName = 'formatter['+(formatterCount + 1)+']';

			var tableRow = $('.formatter_table_blueprint').clone();
			tableRow.find('.formatter_field').prepend(fieldName);
			tableRow.find('.formatter_field input').val(fieldName)
				.attr('name', inputName+'[name]');

			tableRow.find('.formatter_type').prepend(type);
			tableRow.find('.formatter_type input').val(type)
				.attr('name', inputName+'[type]');

			if (type == 'callback') {
				tableRow.find('.formatter_callback input').attr('name', inputName+'[callback]');
			} else {
				tableRow.find('.formatter_callback').prepend('N.v.t.');
				tableRow.find('.formatter_callback input').remove();
			}

			tableRow.css('display', '')
				.removeClass('formatter_table_blueprint')
				.attr('data-row', (formatterCount + 1));

			$('.formatter_table').append(tableRow);
		}


		function deleteFormatterRow(e) {
			e.preventDefault();

			var tableRow = $(e.currentTarget).parents('tr');
			var rowId = tableRow.length <= 0 ? '' : tableRow.attr('data-row');

			if (rowId == '' || isNaN(Number(rowId)) || Number(rowId) < 0) {
				alert('Verwijderen mislukt: ongeldige regel.');
			}

			tableRow.remove();

			var tableRows = $('.formatter_table tr');
			var customCount = 0;
			for (var i = 0; i < tableRows.length; i++) {
				if ($(tableRows[i]).hasClass('formatter_table_blueprint') || $(tableRows[i]).hasClass('formatter_table_header')) {
					continue;
				}

				$(tableRows[i]).attr('data-row', customCount);
				customCount++;
			}
		}

		function saveFormatters() {
			return $('input[name=rdw_formatter_save]').trigger('click');
		}

		$(document).on('click', '.add-formatter', addFormatterRow);
		$(document).on('click', '.delete-formatter', deleteFormatterRow);

		var pageUrl = decodeURIComponent(window.location.search.substring(1)),
            urlVariables = pageUrl.split('&'), parameterName, i;
        if (pageUrl != '') {
        	for (i = 0; i < urlVariables.length; i++) {
	            parameterName = urlVariables[i].split('=');
	            var urlName = parameterName[0];
	            var urlValue = parameterName[1];
	            if (urlName == 'tsdtab') {
	                $('a[href*="'+urlValue+'"]').trigger('click');
	            }
	        }
        }
	});

})( jQuery );
