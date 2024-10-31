import { Alert } from './modules/alert.js';

jQuery(document).ready(function($) {
    const $document = $(document);
    const $wrapper = $('#' + Log.view_wrapper);

    let deleteSpecificLogClass = '.js--delete-specific-log';
    let $deleteSpecificLogButton = $(deleteSpecificLogClass, $wrapper);
    
    $document.on('change', '#log_filter', changeLogBook);
    $document.on('click', deleteSpecificLogClass, deleteSpecificLog);

    $document.on('click', '.js--delete-all-logs', deleteAlleLogs);

    /**
     * When a user selects a different log from the dropdown, this function will fire that ajax request
     * Will get the selected log from our assets folder
     */
    function changeLogBook()
    {
        // Add selected log to the delete button for deleting specific logs. 
        // This way we can delete the newly selected log with the button
        $deleteSpecificLogButton.attr('data-log', $(this).val());

        $.ajax({
            url: Log.ajaxurl,
            data: {
                logbook : $(this).val(),
                action  : Log.select_action
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $('.js--log-viewer', $wrapper).contents().filter(function() {
                    return this.nodeType === 3;
                }).replaceWith(function() {
                    return this.nodeValue = response.data.log;
                });
            }
        });

    }

    /**
     * When a user wants to delete a specific log, this function will fire the ajax request
     * Will delete selected log from assets folder
     * 
     * @param {object} e 
     */
    function deleteSpecificLog(e)
    {
        e.preventDefault();

        $.ajax({
            url: Log.ajaxurl,
            data: {
                logbook : $(this).data('log'),
                action  : Log.delete_specific_action
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                var status = response.success ? 'success' : 'warning';
                return new Alert(response.data, status);
            }
        }).always(function() {
            setTimeout(function() {
                location.replace(location.href);
            }, 1000);
        });

    }

    /**
     * When a user wants to delete all the log files, this function will fire the ajax request
     * Will delete all the logfiles from assets folder
     * 
     * @param {object} e 
     */
    function deleteAlleLogs(e)
    {
        e.preventDefault();

        $.ajax({
            url: Log.ajaxurl,
            data: {
                action  : Log.delete_all_action
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                var status = response.success ? 'success' : 'warning';
                return new Alert(response.data, status);
            }
        }).always(function() {
            setTimeout(function() {
                location.replace(location.href);
            }, 1000);
        });

    }

});