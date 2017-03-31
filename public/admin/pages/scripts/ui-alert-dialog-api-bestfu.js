var UIAlertDialogApi = function () {
    var handleAlerts = function(container, type, msg, icon) {
        Metronic.alert({
            container: container, // alerts parent container(by default placed after the page breadcrumbs)
            place: "prepent", // append or prepent in container
            type: type,  // alert's type
            message: msg,  // alert's message
            close: true, // make alert closable
            reset: true, // close all previouse alerts first
            focus: false, // auto scroll to the alert after shown
            closeInSeconds: 3, // auto close after defined seconds
            icon: icon // put icon before the message
        });
    }

    return {
        //main function to initiate the module
        /*init: function (msg) {
            handleAlerts("danger", msg);
        },*/
        success: function (msg, container) {
            container = container ? container : "#bootstrap_alerts_demo";
            handleAlerts(container, "success", msg, 'check');
        },
        error: function (msg, container) {
            container = container ? container : "#bootstrap_alerts_demo";
            handleAlerts(container, "danger", msg, 'warning');
        },
        warning: function (msg, container) {
            container = container ? container : "#bootstrap_alerts_demo";
            handleAlerts(container, "warning", msg, 'warning');
        }
    };
}();