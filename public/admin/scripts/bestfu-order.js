jQuery(document).ready(function() {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout

    $('.group-checkable').change(function() {
        var set = $('tbody > tr > td:nth-child(1) input[type="checkbox"]');
        var checked = $(this).is(":checked");
        $(set).each(function() {
            $(this).attr("checked", checked);
            if(checked){
                $(this).parent().addClass("checked");
            }else{
                $(this).parent().removeClass("checked");
            }
        });
    });

    $('.bestfu_checkable').change(function() {
        var checked = $(this).is(":checked");
        $(this).attr("checked", checked);
        if(checked){
            $(this).parent().addClass("checked");
        }else{
            $(this).parent().removeClass("checked");
        }
    });

    $("#order-search-form").submit(function(){
        var _form = $(this),
            _o = new Base64(),
            _act = _form.attr("action"),
            _query = _o.encode(_form.serialize());
        var _uri = _act + "?query=" + _query;
        window.location.href = _uri;
        return false;
    });

    $('.red-stripe').click(function(){
        var _  = $(this),
            _id = _.parents('tr').find(':input[name="id[]"]').val();
        $('#delete_order').find(':input[name="id"]').val(_id);
        $('#delete_order').css("top", "10%");
        $('#delete_order').modal('show');
    });

    $("#delete-order-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#delete_order').modal('hide');
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
