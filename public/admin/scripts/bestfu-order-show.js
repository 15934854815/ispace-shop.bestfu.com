jQuery(document).ready(function() {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout

    $('.red-stripe').click(function(){
        var _  = $(this),
            _id = _.parents('tr').find(':input[name="id[]"]').val();
        $('#delete_order').find(':input[name="id"]').val(_id);
        $('#delete_order').css("top", "10%");
        $('#delete_order').modal('show');
    });

    $("form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#do_shipping').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });
});
