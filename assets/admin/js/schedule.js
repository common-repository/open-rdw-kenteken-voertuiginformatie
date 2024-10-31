import { Alert } from './modules/alert.js';

jQuery(document).ready(function($) {
    const $document = $(document);

    $document.on('click', '.js--start-job', startScheduledJob);

    /**
     * Start a scheduled job with Ajax
     * 
     * @param {object} e 
     */
    function startScheduledJob(e)
    {
        e.preventDefault();
        
        let $button         = $(this);
        let action_id       = $button.data('action-id');
        let action_object   = $button.data('action-object');

        return $.ajax({
            url: Schedule.ajaxurl,
            data: {
                action        : Schedule.start_action,
                action_id     : action_id,
                action_object : action_object,
            },
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $button.prop('disabled', true);
                $button.find('i').removeClass('fa-cloud-arrow-down').addClass('fa-spinner fa-spin');
            },
        })
        .then(function(response) {
            new Alert(response.data, 'success');
            $button.removeClass('btn-primary').addClass('btn-success');

            setTimeout(function() {
                location.replace(location.href);
            }, 2100);
        })
        .fail(function(jqXHR, textStatus) {
            new Alert(jqXHR.responseJSON.data, 'danger', 'manual');
            $button.removeClass('btn-primary').addClass('btn-danger');
        })
        .always(function() {
            $button.find('i').removeClass('fa-spinner fa-spin').addClass('fa-cloud-arrow-down');

            setTimeout(function() {
                $button.prop('disabled', false);
                $button.removeClass('btn-success btn-danger').addClass('btn-primary');
            }, 2000);
        });
    }

});