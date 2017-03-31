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

    $("#add-class-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#add_class').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });

    $('.modify-classes').click(function(){
        var _  = $(this),
            _tr = _.parents('tr'),
            _id = _tr.find(':input[name="id[]"]').val(),
            _name = _tr.children('td').eq(1).text(),
            _mobile = _tr.children('td').eq(2).text(),
            _hot = _tr.children('td').eq(3).attr("hot"),
            _show = _tr.children('td').eq(4).attr("show"),
            _sort = _tr.children('td').eq(5).text();
        var __ = $('#edit-class-form');
        __.find(':input[name="name"]').val(_name);
        __.find(':input[name="mobile_name"]').val(_mobile);
        __.find(':input[name="sort"]').val(_sort);
        __.find(':input[name="id"]').val(_id);
        __.find(':radio[name="hot"]').each(function(){
            if(this.value == _hot){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        __.find(':radio[name="show"]').each(function(){
            if(this.value == _show){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        $('#edit_class').modal('show');
    });

    $("#edit-class-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#edit_member').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });

    $('.red-stripe').click(function(){
        var _  = $(this),
            _id = _.parents('tr').find(':input[name="id[]"]').val();
        $('#delete_class').find(':input[name="id"]').val(_id);
        $('#delete_class').css("top", "10%");
        $('#delete_class').modal('show');
    });

    $("#delete-class-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#delete_class').modal('hide');
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
