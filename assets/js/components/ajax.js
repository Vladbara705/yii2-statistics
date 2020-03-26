function sendAjax(url, options) {
    options = options || {};
    options.data = options.data || {};
    options.method = options.method || 'POST';
    options.successCallback = options.successCallback || function (response) {
    };
    $.ajax({
        url: url,
        type: options.method,
        data: options.data,
        dataType: 'json',
        success: function (response) {
            response = response || {};
            response.success = response.success || false;
            response.data = response.data || {};
            if (response.success) {
                options.successCallback(response);
            } else {
                options.errorCallback(response);
            }
        }
    });
};

function sendFormAjax($form, options) {
    options = options || {};
    options.action = options.action || $form.attr('action');
    options.method = options.method || $form.attr('method') || 'POST';
    options.method = options.method.toUpperCase();
    options.successCallback = options.successCallback || function (response) {};
    options.errorCallback = options.errorCallback || function (response) {};
    formData = $form.serialize();
    processData = true;
    contentType = "application/x-www-form-urlencoded; charset=UTF-8";

    $.ajax({
        url: options.action,
        type: options.method,
        data: formData,
        enctype: 'multipart/form-data',
        processData: processData,
        contentType: contentType,
        dataType: 'json',
        success: function (response) {
            response = response || {};
            response.success = response.success || false;
            response.data = response.data || {};
            if (response.success) {
                options.successCallback(response);
            } else {
                options.errorCallback(response);
            }
        }
    });
}
