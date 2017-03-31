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

    $("#add-navs-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#add_navs').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });

    $('.modify-navs').click(function(){
        var _  = $(this),
            _tr = _.parents('tr'),
            _id = _tr.find(':input[name="id[]"]').val(),
            _name = _tr.children('td').eq(1).text(),
            _url = _tr.children('td').eq(2).text(),
            _sort = _tr.children('td').eq(3).text(),
            _show = _tr.children('td').eq(4).attr("show"),
            _target = _tr.children('td').eq(5).attr("target");
        var __ = $('#edit-navs-form');
        __.find(':input[name="name"]').val(_name);
        __.find(':input[name="url"]').val(_url);
        __.find(':input[name="sort"]').val(_sort);
        __.find(':input[name="id"]').val(_id);
        __.find(':input[name="show"]').each(function(){
            if(this.value == _show){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        __.find(':radio[name="target"]').each(function(){
            if(this.value == _target){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        $('#edit_navs').modal('show');
    });

    $("#edit-navs-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            console.log(resp);
            if(resp.code){
                $('#edit_navs').modal('hide');
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
        $('#delete_navs').find(':input[name="id"]').val(_id);
        $('#delete_navs').css("top", "10%");
        $('#delete_navs').modal('show');
    });

    $("#delete-navs-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#delete_navs').modal('hide');
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
