var UIToastr = function () {

    var handleToastr = function (type, title, msg) {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "1500",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr[type](msg, title);
    }
    
    return {
        //main function to initiate the module
        success : function (title, msg) {
            title = title ? title : "";
            handleToastr("success", title, msg);
        },
        warning : function (title, msg) {
            title = title ? title : "";
            handleToastr("warning", title, msg);
        },
        error : function (title, msg) {
            title = title ? title : "";
            handleToastr("error", title, msg);
        }
    };

}();