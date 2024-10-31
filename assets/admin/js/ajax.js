jQuery(document).ready(function($) {
    $(document).on('submit', '#tsd_bol_admin', function(e) {
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);

        $.ajax({
            url: ajaxurl,
            data: formdata,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            beforeSend: function() {
                $('button[type="submit"]', form).prop('disabled', true);
                $('button[type="submit"] i', form).removeClass('fa-save').addClass('fa-spinner fa-spin');
            },
        }).done(function(data) {
            $('button[type="submit"]', form).removeClass('btn-primary').addClass('btn-success');
        }).fail(function(jqXHR, textStatus) {
            $('button[type="submit"]', form).removeClass('btn-primary').addClass('btn-danger');
        }).always(function() {
            $('button[type="submit"] i', form).removeClass('fa-spinner fa-spin').addClass('fa-save');

            setTimeout(function() {
                $('button[type="submit"]', form).prop('disabled', false);
                $('button[type="submit"]', form).removeClass('btn-success btn-danger').addClass('btn-primary');
            }, 2000);
        });
    });


    /**
     * Makes an ajax call to the backend, letting it know the open-rdw-notice
     * is dismissed and should be saved as dismissed.
     */
    $(document).on('click', ".open-rdw-notice-dismiss-action", function () {
        $.ajax({
            url: ajaxurl,
            cache: false,
            type: "GET",
            data: {
                action: "open-rdw-notice-dismiss",
            },
        });
    });

    $(document).on('click', '.js--empty_cache', function(e) {
        e.preventDefault();
        var formdata = new FormData();

        var button = $(this);
        var action = $('#tsd_bol_admin input[name="action"]').val();

        formdata.append('action', action);
        formdata.append('empty_cache', true);

        $.ajax({
            url: ajaxurl,
            data: formdata,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            beforeSend: function() {
                button.prop('disabled', true).addClass('text-muted');
                $('i', button).removeClass('fa-trash-alt').addClass('fa-spinner fa-spin');
                $('span', button).text($('span', button).data('loading'));
            },
        }).done(function(data) {
            button.removeClass('text-muted').addClass('text-success');
            $('span', button).text($('span', button).data('done'));
        }).fail(function(jqXHR, textStatus) {
            button.removeClass('text-muted').addClass('text-danger');
            $('span', button).text($('span', button).data('fail'));
        }).always(function() {
            $('i', button).removeClass('fa-spinner fa-spin').addClass('fa-trash-alt');

            setTimeout(function() {
                button.prop('disabled', false);
                button.removeClass('text-muted text-success text-danger');
                $('span', button).text($('span', button).data('always'));
            }, 2000);
        });
    });
});
