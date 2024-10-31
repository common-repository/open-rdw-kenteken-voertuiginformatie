import { Alert } from './modules/alert.js';

jQuery(document).ready(function($) {

    const fetchHistoryForm = $('#' + History.plugin_tag + '_fetch_history_form');

    fetchHistoryForm.on('submit', function(e) {
        startFetchingHistory(e, this);
    });

    /**
     * Fetch the type history
     * 
     * @param {object} e
     * @param {object} form
     * @returns {void}
     */
    function startFetchingHistory(e, form)
    {
        e.preventDefault();

        let formData = new FormData(form);

        $('button[type="submit"]', form).prop('disabled', true);
        $('button[type="submit"] i', form).removeClass('fa-cloud-arrow-down').addClass('fa-spinner fa-spin');

        let historyFetched = fetchHistory(formData);

        $.when(historyFetched).then(ajaxSuccess(form)).fail(ajaxFailure(form)).always(ajaxResetButton(form));
    }

    /**
     * Execute fetching history by starting an ajax call
     * 
     * @param {FormData} formData 
     * @returns {jqXHR}
     */
    function fetchHistory(formData)
    {
        formData.append('action', History.fetch_history_action);

        return $.ajax({
            url: History.ajaxurl,
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
        });
    }

    /**
     * Function to fire when all ajax functions would result in a success method
     * When the repsonse HTTP status code is not 500
     * 
     * @param {object} form
     * @return {function}
     */
    function ajaxSuccess(form)
    {
        /**
         * Historyfetched is passed from the ajax call
         * @param {object} historyFetched
         */
        return function(historyFetched) {
            // Catch non-500 errors with a default text
            if (!historyFetched.success) {
                $('button[type="submit"]', form).removeClass('btn-primary').addClass('btn-danger');
                return new Alert(historyFetched.data, 'danger');
            }

            $('button[type="submit"]', form).removeClass('btn-primary').addClass('btn-success');

            // Return default success text
            new Alert(historyFetched.data, 'success', 'manual');

            // Reload page
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }
    }

    /**
     * Function to fire when at least one ajax function would result in a failure method
     * When the repsonse HTTP status code is specifically 500
     * 
     * @param {object} form
     * @return {function}
     */
    function ajaxFailure(form)
    {
        /**
         * jqXHR is passed from the ajax call
         * @param {jqXHR} jqXHR
         */
        return function(jqXHR) {
            $('button[type="submit"]', form).removeClass('btn-primary').addClass('btn-danger');
            
            if (jqXHR.responseJSON.data.message) {
                new Alert(jqXHR.responseJSON.data.message, 'danger', 'manual');
            }
            
            if (jqXHR.responseJSON.data.debug) {
                new Alert(jqXHR.responseJSON.data.debug, 'info', 'manual');
            }
        }
    }

    /**
     * Reset the button in the settings form
     * 
     * @param {object} form
     * @return {function}
     */
    function ajaxResetButton(form)
    {
        return function() {
            $('button[type="submit"] i', form).removeClass('fa-spinner fa-spin').addClass('fa-cloud-arrow-down');

            setTimeout(function() {
                $('button[type="submit"]', form).prop('disabled', false);
                $('button[type="submit"]', form).removeClass('btn-success btn-danger').addClass('btn-primary');
            }, 2000);
        }
    }

});