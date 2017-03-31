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

    $("#add-address-form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                $('#add_link').modal('hide');
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    window.location.reload();
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });

    $('.modify-address').click(function(){
        var _  = $(this),
            _tr = _.parents('tr'),
            _id = _tr.find(':input[name="id[]"]').val(),
            _consignee = _tr.children('td').eq(1).text(),
            _mobile = _tr.children('td').eq(2).text(),
            _zipcode = _tr.children('td').eq(3).text(),
            _province = _tr.children('td').eq(4).attr("province"),
            _city = _tr.children('td').eq(4).attr("city"),
            _district = _tr.children('td').eq(4).attr("district"),
            _twon = _tr.children('td').eq(4).attr("twon"),
            _address = _tr.children('td').eq(4).attr("address"),
            _default = _tr.children('td').eq(5).attr("is_default"),
            _tag = _tr.children('td').eq(6).text(),
            _action = _tr.children('td').eq(4).attr("action");
        var __ = $('#edit-address-form');
        __.find(':input[name="consignee"]').val(_consignee);
        __.find(':input[name="mobile"]').val(_mobile);
        __.find(':input[name="zipcode"]').val(_zipcode);
        __.find('textarea[name="address"]').val(_address);
        __.find(':input[name="tag"]').val(_tag);
        __.find(':input[name="id"]').val(_id);
        __.find(':radio[name="is_default"]').each(function(){
            if(this.value == _default){
                this.checked = true;
            }else{
                this.checked = false;
            }
            $(this).show();
            $(this).uniform();
        });
        $.post(_action, {id: _id}, function(resp){
            var _provinces = resp.province,
                _citys = resp.city,
                _districts = resp.district,
                _twons = resp.twon;
            var _province_html = "<option value=''>-选择省-</option>",
                _city_html = "<option value=''>-选择市-</option>",
                _district_html = "<option value=''>-选择区/县-</option>",
                _twon_html = "<option value=''>-选择街道-</option>";
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
            $.each(_twons, function(_key, _val) {
                if(_key == _twon){
                    _twon_html += '<option value="'+_key+'" selected>'+_val+'</option>';
                }else{
                    _twon_html += '<option value="'+_key+'">'+_val+'</option>';
                }
            });
            __.find('select[name="province"]').append(_province_html);
            __.find('select[name="city"]').append(_city_html);
            __.find('select[name="district"]').append(_district_html);
            __.find('select[name="twon"]').append(_twon_html);
        }, 'json');
        $('#edit_address').modal('show');
    });

    $("#edit-address-form").submit(function(){
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
        $('#delete_address').find(':input[name="id"]').val(_id);
        $('#delete_address').css("top", "10%");
        $('#delete_address').modal('show');
    });

    $("#delete-address-form").submit(function(){
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
                    _twon = $("select[name='twon']"),
                    _html = '<option value="">-选择市-</option>',
                    _district_html = '<option value="">-选择区/县-</option>',
                    _twon_html = '<option value="">-选择街道-</option>';
                $.each(_data, function (_key, _val) {
                    _html += '<option value="' + _key + '">' + _val + '</option>';
                });
                _city.empty();
                _city.append(_html);
                _district.empty();
                _district.append(_district_html);
                _twon.empty();
                _twon.append(_twon_html);
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
                    _twon = $("select[name='twon']"),
                    _html = '<option value="">-选择区/县-</option>',
                    _twon_html = '<option value="">-选择街道-</option>';
                $.each(_data, function(_key, _val) {
                    _html += '<option value="'+_key+'">'+_val+'</option>';
                });
                _district.empty();
                _district.append(_html);
                _twon.empty();
                _twon.append(_twon_html);
            }
        }, 'json');
    });

    $("select[name='district']").change(function(){
        var _ = $(this),
            _id = _.val(),
            _url = _.attr("action");
        $.post(_url, {id:_id}, function(resp){
            //console.log(resp);
            if(resp.code == 1){
                var _data = resp.msg,
                    _twon = $("select[name='twon']"),
                    _html = '<option value="">-选择街道-</option>';
                $.each(_data, function(_key, _val) {
                    _html += '<option value="'+_key+'">'+_val+'</option>';
                });
                _twon.empty();
                _twon.append(_html);
            }
        }, 'json');
    });
});
