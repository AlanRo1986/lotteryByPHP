
function bindKdedior(DsMode,FtMode,RdMode){
	K = KindEditor;
    var editor = K.create('textarea.ketext', {
        allowFileManager : true,
        emoticonsPath:APP_ROOT+"public/emoticons",
		designMode:DsMode == "undefined" ? true : DsMode,
		filterMode:FtMode == "undefined" ? true : FtMode,
		readonlyMode:RdMode == "undefined" ? true : RdMode,
        afterBlur: function(){this.sync();}, //兼容jq的提交，失去焦点时同步表单值
        minHeight:300,
        items : [
			'source','fsource', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
			'plainpaste', 'wordpaste', 'justifyleft', 'justifycenter', 'justifyright',
			'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			'superscript', 'selectall','/',
			'title', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
			'italic', 'underline', 'strikethrough', 'removeformat', 'image','multiimage',
			'flash', 'media', 'table', 'hr', 'emoticons', 'link', 'unlink'
		]
    });   
	
}

function bindKdupload(){

	if(K==null){
		K = KindEditor;
	}
	var ieditor = K.editor({
       allowFileManager : true,
       imageSizeLimit:"3MB"               
    });
	K('.keimg').unbind("click");
    K('.keimg').click(function() {
        var node = K(this);
        var dom =$(node).parent().parent().parent().parent();
        ieditor.loadPlugin('image', function() {
               ieditor.plugin.imageDialog({
               // imageUrl : K("#keimg_h_"+$(this).attr("rel")).val(),
                imageUrl:dom.find("#keimg_h_"+node.attr("rel")+"_i").val(),
                clickFn : function(url, title, width, height, border, align) {       
                    dom.find("#keimg_a_"+node.attr("rel")).attr("href",url),
                    dom.find("#keimg_m_"+node.attr("rel")).attr("src",url),
                    dom.find("#keimg_h_"+node.attr("rel")+"_i").val(url),
					dom.find(".keimg_d[rel='"+node.attr("rel")+"']").show(),
                    ieditor.hideDialog();
                }
            });
        });
    });
	
	/**
	 * 删除单图
	 */
	K('.keimg_d').unbind("click");
    K('.keimg_d').click(function() {
        var node = K(this);
		K(this).hide();
        var dom =$(node).parent().parent().parent().parent();
        dom.find("#keimg_a_"+node.attr("rel")).attr("href","");
        //dom.find("#keimg_m_"+node.attr("rel")).attr("src",APP_ROOT + "/admin/Tpl/default/images/no_pic.gif");
        dom.find("#keimg_h_"+node.attr("rel")+"_i").val("");
    });
}

$(document).ready(function(){

	
	bindKdedior();
	bindKdupload();
	
	$("#updateimg").click(function(){
		var rel = $(this).attr('rel');
		rel = rel.substring(0,rel.length-1)

		var data = $(this).attr('data');
		
		$(this).attr('rel',rel+data);
		data = parseInt(data)+1
		if(data> 3){
			data = 1;
		}
		$(this).attr('data',data);
		
	})
	
});
