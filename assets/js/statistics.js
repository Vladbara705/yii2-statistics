$(document).ready(function() {
    var instance = $(document),
        removeHandler = '.js_remove';
        submitFormHanlder = '.js_show_statistics';

    instance.on('click', removeHandler, function (e) {
        e.preventDefault();
        remove($(this));
    });

    instance.on('submit', submitFormHanlder, function (e) {
        e.preventDefault();
        showStatstics($(this));
        return false;
    });

    $('.js_datepicker').datepicker({
        language: 'ru',
        format: 'yyyy-mm-dd',
        autoClose: true,
    });
});


/**
 * FUNCTION remove
 */
function remove($element) {
    var url = 'statistics/remove';
    sendAjax(url, {
        data: {
            type: $element.data('type')
        },
        successCallback: function (response) {
            $element.addClass('disabled');
            $element.closest('.block-diagramm').addClass('disabled');
        },
        errorCallback: function (response) {
            alert(response.alert)
        },
    });
}

/**
 * FUNCTION showStatstics
 */
function showStatstics($form) {
    var statisticContainer = '.js_statistic_container';
    sendFormAjax($form, {
        method: 'GET',
        successCallback: function (response) {
            $form.closest(statisticContainer).replaceWith(response.data)
            
            $('.js_datepicker').datepicker({
                language: 'ru',
                format: 'yyyy-mm-dd',
                autoClose: true,
            });
        },
        errorCallback: function (response) {
            alert(response.alert);
        },
    });
}
