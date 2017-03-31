var editor_content;
KindEditor.ready(function(K) {
    editor_content = K.create('textarea[name="content"]', {
        allowFileManager : false,
        themesPath: K.basePath,
        width: '100%',
        height: '325px',
        resizeType: 1,
        pasteType : 2,
        urlType : 'absolute',
        fileManagerJson : "{:url('fileManagerJson')}",
        uploadJson : "{:url('file/keditor_upload_picture')}",
        afterBlur: function () { this.sync(); }
    });
});

jQuery(document).ready(function() {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout

    $('textarea[name="content"]').closest('form').submit(function(){
        editor_content.sync();
    });
    //ajax提交之前同步
    $('button[type="submit"],#submit,.ajax-post,#autoSave').click(function(){
        editor_content.sync();
    });

    $("form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(resp){
            //console.log(resp);
            if(resp.code){
                UIToastr.success('', resp.msg);
                window.setTimeout(function(){
                    //window.location.reload();
                    window.location.href = resp.url;
                }, 500);
            } else {
                UIToastr.error('', resp.msg);
            }
        }
    });
});