jQuery(document).ready(function($) {
    const $document = $(document);
    
    $document.on('change', '#admin_filter', filterResults);

    function filterResults()
    {
        const $form = $(this);
        const $wrapper = $('#' + Filter.view_wrapper);

        const NoResultsAlert = ({text}) => `
            <div class="no_results alert alert-warning d-inline-block m-0">
                ${text}
            </div>
        `;

        let data = $form.serialize() + '&action=' + Filter.filter_action;

        $.ajax({
            url: Filter.ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                $('#filter_items #item_wrapper', $wrapper).remove();
                $('#filter_items .no_results', $wrapper).remove();
            },
            success: function(response) {

                if (response.data.items && response.data.items.length > 0) {
                    $.each(response.data.items, function(key, item){
                        $(item).appendTo('#filter_items', $wrapper);
                    });
                } else {
                    $('#filter_items', $wrapper).append([
                        {text : Filter.empty}
                    ].map(NoResultsAlert).join(''));
                }
                
            }
        });
    }


});