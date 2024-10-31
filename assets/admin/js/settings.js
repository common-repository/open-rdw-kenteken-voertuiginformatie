// import { Alert } from './modules/alert.js';

/**
 * Method to be used for displaying alerts
 *
 * @param {string} text
 * @param {string} status [success, error, notice]
 * @param {string} remove [auto, manual]
 * @param {string} timeout
 * @param {boolean} beforeHeaderEnd
 */
function Alert(text, status, remove = 'auto', timeout = 2000, beforeHeaderEnd = true) {
    // Scroll to top so the alert will be in view
    window.scrollTo(0, 0);

    // Create the warning
    let $alert = document.createElement('div');

    // Add default classes to the warning
    $alert.classList.add('alert-' + status, 'alert', 'my-0', 'p-2', 'ps-3', 'shadow-none', 'position-relative', 'tussendoor-notice');

    // Create text node with given content
    let $text = document.createElement('p');
    $text.classList.add('m-0', 'p-0');
    $text.textContent = text;

    if (remove === 'manual') {
        // Create span node with given content
        let $removeButton = document.createElement('i');
        $removeButton.classList.add('js--remove-alert', 'custom--close-alert', 'fas', 'fa-solid', 'fa-xmark', 'p-2', 'position-absolute');

        // Append the button node to the warning
        $alert.appendChild($removeButton);

        // Add extra padding to the text when a close button is rendered
        $text.classList.add('pe-4');
    }

    // Append the text node to the warning
    $alert.appendChild($text);

    // Insert warning after wp-header-end
    let $headerEnd = document.getElementsByClassName('wp-header-end');
    let $element = beforeHeaderEnd ? $headerEnd.nextSibling : $headerEnd[0];

    $headerEnd[0].parentNode.insertBefore($alert, $element);

    if (remove === 'auto') {
        setTimeout(() => {

            $alert.style.transition = '0.2s';
            $alert.style.opacity = 0;

            setTimeout(() => {
                $alert.remove();
            }, 210);

        }, timeout);
    }
}

jQuery(document).ready(function($) {
    const $document = $(document);
    const $form = $('#' + Settings.plugin_tag + '_setting_form');

    $document.on('submit', '#' + Settings.plugin_tag + '_setting_form', saveSettings);
    $document.on('click', '.js--register-plugin', registerPluginAndSaveLicense);
    $document.on('click', '.js--refresh-api-token', refreshApiToken);

    // **
    //  * Save the settings parsed through the form
    //  *
    //  * @param {object} e
    //  */
    function saveSettings(e)
    {
        e.preventDefault();

        let data = $form.serializeObject();

        $('button[type="submit"]', $form).prop('disabled', true);
        $('button[type="submit"] i', $form).removeClass('fa-save').addClass('fa-spinner fa-spin');

        var ordersSaved     = saveOrderSettings((data.orders ?? []));
        var returnsSaved    = saveReturnSettings((data.returns ?? []));

        delete data.orders;
        delete data.returns;

        var defaultsSaved = saveDefaultSettings(data);

        // Handle all the ajax calls
        $.when(returnsSaved, ordersSaved, defaultsSaved).then(ajaxSuccess).fail(ajaxFailure).always(ajaxResetButton);
    }

    /**
     * Function to fire when all ajax functions would result in a success method
     * When the repsonse HTTP status code is not 500
     *
     * @param {jqXHR} returns
     * @param {jqXHR} orders
     * @param {jqXHR} defaults
     */
    function ajaxSuccess(returns, orders, defaults)
    {
        // Catch non-500 errors with a default text
        if (!returns[0].success || !orders[0].success || !defaults[0].success) {
            $('button[type="submit"]', $form).removeClass('btn-primary').addClass('btn-danger');
            return new Alert(Settings.saveFailText, 'danger');
        }

        $('button[type="submit"]', $form).removeClass('btn-primary').addClass('btn-success');

        // Return default success text
        new Alert(Settings.saveText, 'success', 'manual');

        // Reload page
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    }

    /**
     * Function to fire when at least one ajax function would result in a failure method
     * When the repsonse HTTP status code is specifically 500
     *
     * @param {jqXHR} jqXHR
     */
    function ajaxFailure(jqXHR)
    {
        $('button[type="submit"]', $form).removeClass('btn-primary').addClass('btn-danger');

        if (jqXHR.responseJSON.data.message) {
            new Alert(jqXHR.responseJSON.data.message, 'danger', 'manual');
        }

        if (jqXHR.responseJSON.data.debug) {
            new Alert(jqXHR.responseJSON.data.debug, 'info', 'manual');
        }
    }

    /**
     * Reset the button in the settings form
     */
    function ajaxResetButton()
    {
        $('button[type="submit"] i', $form).removeClass('fa-spinner fa-spin').addClass('fa-save');

        setTimeout(function() {
            $('button[type="submit"]', $form).prop('disabled', false);
            $('button[type="submit"]', $form).removeClass('btn-success btn-danger').addClass('btn-primary');
        }, 2000);
    }

    /**
     * Do the ajax call for default settings
     *
     * @param {object} requestData
     * @returns jqXHR
     */
    function saveDefaultSettings(requestData)
    {
        return $.ajax({
            url: Settings.ajaxurl,
            data: {
                action  : Settings.save_action,
                settings: requestData,
            },
            type: 'POST',
            dataType: 'json',
        });
    }

    /**
     * Do the ajax call for orders settings
     *
     * @param {object} requestData
     * @returns jqXHR
     */
    function saveOrderSettings(requestData)
    {
        return $.ajax({
            url: Settings.ajaxurl,
            data: {
                action      : Settings.save_orders,
                settings    : requestData,
            },
            type: 'POST',
            dataType: 'json',
        });
    }

    /**
     * Do the ajax call for return settings
     *
     * @param {object} data
     * @returns jqXHR
     */
    function saveReturnSettings(data)
    {
        return $.ajax({
            url: Settings.ajaxurl,
            data: {
                action          : Settings.save_returns,
                enabled         : (data.enabled ?? 0),
                delete_current  : (data.delete_current ?? 0),
            },
            type: 'POST',
            dataType: 'json',
        });
    }

    /**
     * Method for calling the register functionality
     *
     * @see SettingsController
     *
     * @param {object} e
     */
    function registerPluginAndSaveLicense(e)
    {
        e.preventDefault();

        var $button = $(this);

        $.ajax({
            url: Settings.ajaxurl,
            data: {
                action      : Settings.register_action,
                license     : $button.parents('#register').find('#license_code').val(),
            },
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $button.prop('disabled', true);
                $button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
            },
        })
        .then(function(response) {

            $button.removeClass('btn-primary').addClass('btn-success');
            new Alert(response.data, 'success');

            setTimeout(function() {
                $('#register').modal('toggle');
            }, 1000);

        })
        .fail(function(jqXHR, textStatus) {

            $button.removeClass('btn-primary').addClass('btn-warning');
            new Alert(jqXHR.responseJSON.data, 'danger', 'manual');

        })
        .always(function() {
            $button.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');

            setTimeout(function() {
                $button.prop('disabled', false);
                $button.removeClass('btn-success btn-warning').addClass('btn-primary');
            }, 2000);
        });
    }


    /**
     * Method for calling the register functionality
     *
     * @see Api
     *
     * @param {object} e
     */
    function refreshApiToken(e) {
        e.preventDefault();

        var $button = $(this);

        $.ajax({
            url: Settings.ajaxurl,
            data: {
                action: Settings.refresh_api_token_action,
            },
            type: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $button.prop('disabled', true);
                $button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
            },
        })
        .then(function (response) {

            $button.removeClass('btn-primary').addClass('btn-success');
            new Alert(response.data, 'success');
        })
        .fail(function (jqXHR) {

            $button.removeClass('btn-primary').addClass('btn-warning');
            new Alert(jqXHR.responseJSON.data, 'danger', 'manual');

        })
        .always(function () {
            $button.find('i').removeClass('fa-spinner fa-spin').addClass('fa-save');

            setTimeout(function () {
                $button.prop('disabled', false);
                $button.removeClass('btn-success btn-warning').addClass('btn-primary');
            }, 2000);
        });
    }

});