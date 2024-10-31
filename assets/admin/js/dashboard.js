jQuery(document).ready(function ($) {
    const $document = $(document);

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    $document.on('click', 'input[type=checkbox]', toggleCheckboxValue);
    $document.on('click', '.nav-item a.tab-link', handleTabChangeInHistory);
    $document.on('click', '.js--toggle-extra-content-checkbox', toggleExtraContentCeckbox);
    $document.on('click', '.js--remove-alert', removeAlert);
    $document.on('change', '.js--toggle-extra-content-select', toggleExtraContentSelect);

    function handleTabChangeInHistory() {

        let url = window.location.href;
        let baseUrl = url.split('?')[0]

        let target = $(this).attr('id');
        let searchParams = new URLSearchParams(window.location.search)

        searchParams.set('tab', target);

        let newUrl = baseUrl + '?' + searchParams.toString();
        history.pushState({}, null, newUrl);
    }

    function toggleCheckboxValue(e) {
        if ($(this).is(':checked')) {
            $(this).attr('value', '1');
        } else {
            $(this).attr('value', '0');
        }
    }

    function toggleExtraContentCeckbox(event) {
        let target = $(event.target).data('target');
        let $content = $('.js--extra-content-target[data-for=' + target + ']');

        $content.each(function () {

            var checkboxIsChecked = $(event.target).is(':checked');
            var enableReversed = (!checkboxIsChecked && $(this).hasClass('reversed'));
            var enableDefault = (checkboxIsChecked && !$(this).hasClass('reversed'));

            if (enableReversed || enableDefault) {
                if ($(this).hasClass('extra-content-is-flexed')) {
                    $(this).css('display', 'flex');
                } else {
                    $(this).show();
                }
                enableFieldsInContent($(this));
            } else {
                $(this).hide();
                disableFieldsInContent($(this));
            }
        });

    }

    function toggleExtraContentSelect() {
        let toggleValue = $(this).data('toggle-value');
        let value = $(this).val();
        let target = $(this).data('target');
        let $content = $('.js--extra-content-target[data-for=' + target + ']');

        // Create array if multiple values are possible
        if (toggleValue.includes(',')) {
            toggleValue = toggleValue.split(',');
        }

        $content.each(function () {
            // if toggleValue is an array and value is not in the array then hide and disable the content
            // if toggleValue is a string and not equal to value then hide and disable the content
            if ((Array.isArray(toggleValue) && !toggleValue.includes(value)) || (typeof toggleValue === 'string' && toggleValue !== value)) {
                hideAndDisableContent($(this));
            } else {
                // if this entry in $content does NOT have a data-value attribute then show and enable the content
                // if this entry in $content DOES have a data-value attribute then check if the value of the select is equal to the value of the data-value attribute
                // if so then show and enable the content
                if (!$(this).data('value') || $(this).data('value') === value) {
                    showAndEnableContent($(this));
                } else {
                    hideAndDisableContent($(this));
                }
            }
        });
    }

    function showAndEnableContent($content) {
        $content.slideDown();
        enableFieldsInContent($content);
    }

    function hideAndDisableContent($content) {
        $content.slideUp();
        disableFieldsInContent($content);
    }

    function enableFieldsInContent($content) {
        $('input', $content).prop('disabled', false).removeClass('disabled');
        $('select', $content).prop('disabled', false).removeClass('disabled');
        $('textarea', $content).prop('disabled', false).removeClass('disabled');
    }

    function disableFieldsInContent($content) {
        $('input', $content).prop('disabled', true).addClass('disabled');
        $('select', $content).prop('disabled', true).addClass('disabled');
        $('textarea', $content).prop('disabled', true).addClass('disabled');
    }

    /**
     * When adding custom alerts with a manual removal we can remove the alert via this method
     *
     * @see alert.js
     *
     * @param {object} e
     */
    function removeAlert(e) {
        e.target.parentNode.remove();
    }
});
