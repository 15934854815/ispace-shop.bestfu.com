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

    $('.modify-member').click(function(){
        var _  = $(this),
            _tr = _.parents('tr'),
            _userid = _tr.find(':input[name="id[]"]').val(),
            _username = _tr.children('td').eq(1).text(),
            _nickname = _tr.children('td').eq(2).text(),
            _email = _tr.children('td').eq(3).text(),
            _mobile = _tr.children('td').eq(4).text(),
            _sex = _tr.children('td').eq(5).attr("sex"),
            _province =_tr.children('td').eq(6).attr("province"),
            _city =_tr.children('td').eq(6).attr("city"),
            _district =_tr.children('td').eq(6).attr("district"),
            _action =_tr.children('td').eq(6).attr("action"),
            _lock = _tr.children('td').eq(7).attr("lock");
        var __ = $('#edit-member-form');
        __.find(':input[name="username"]').val(_username);
        __.find(':input[name="nickname"]').val(_nickname);
        __.find(':input[name="email"]').val(_email);
        __.find(':input[name="mobile"]').val(_mobile);
        __.find(':input[name="user_id"]').val(_userid);
        __.find(':input[name="sex"]').each(function(){
            if(this.value == _sex){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        __.find(':radio[name="lock"]').each(function(){
            if(this.value == _lock){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        $.post(_action, {user_id: _userid}, function(resp){
            var _provinces = resp.province,
                _citys = resp.city,
                _districts = resp.district;
            var _province_html = "<option value=''>-选择省-</option>",
                _city_html = "<option value=''>-选择市-</option>",
                _district_html = "<option value=''>-选择区/县-</option>";
            $.each(_provinces, function(_key, _val) {
                if(_key == _province){
                    _province_html += '<option value="'+_key+'" selected>'+_val+'</option>';
                }else{
                    _province_html += '<option value="'+_key+'">'+_val+'</option>';
                }
            });
            $.each(_citys, function(_key, _val) {
                if(_key == _city){
                    _city_html += '<option value="'+_key+'" selected>'+_val+'</option>';
                }else{
                    _city_html += '<option value="'+_key+'">'+_val+'</option>';
                }
            });
            $.each(_districts, function(_key, _val) {
                if(_key == _district){
                    _district_html += '<option value="'+_key+'" selected>'+_val+'</option>';
                }else{
                    _district_html += '<option value="'+_key+'">'+_val+'</option>';
                }
            });
            __.find('select[name="province"]').append(_province_html);
            __.find('select[name="city"]').append(_city_html);
            __.find('select[name="district"]').append(_district_html);
        }, 'json');
        $('#edit_member').modal('show');
    });

    $("#edit-member-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            console.log(resp);
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
        $('#delete_member').find(':input[name="user_id"]').val(_id);
        $('#delete_member').css("top", "10%");
        $('#delete_member').modal('show');
    });

    $("#delete-member-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#delete_member').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });

    $("select[name='province']").change(function(){
        var _ = $(this),
            _id = _.val(),
            _url = _.attr("action");
        $.post(_url, {id:_id}, function(resp){
            //console.log(resp);
            if(resp.code == 1) {
                var _data = resp.msg,
                    _city = $("select[name='city']"),
                    _district = $("select[name='district']"),
                    _html = '<option value="">-选择市-</option>',
                    _district_html = '<option value="">-选择区/县-</option>';
                $.each(_data, function (_key, _val) {
                    _html += '<option value="' + _key + '">' + _val + '</option>';
                });
                _city.empty();
                _city.append(_html);
                _district.empty();
                _district.append(_district_html);
            }
        }, 'json');
    });

    $("select[name='city']").change(function(){
        var _ = $(this),
            _id = _.val(),
            _url = _.attr("action");
        $.post(_url, {id:_id}, function(resp){
            //console.log(resp);
            if(resp.code == 1){
                var _data = resp.msg,
                    _district = $("select[name='district']"),
                    _html = '<option value="">-选择区/县-</option>';
                $.each(_data, function(_key, _val) {
                    _html += '<option value="'+_key+'">'+_val+'</option>';
                });
                _district.empty();
                _district.append(_html);
            }
        }, 'json');
    });
});
