function GetQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return null;
}

function addcookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getcookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return arr[1];
	}
	return "";
}

function delCookie(name){//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getcookie(name);
	if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function AlertMsg(c ,t){
	$("#alertMsgTitle").text(t);
	$("#alertMsgtxt").text(c);
	$('#alertMsg').modal({});
}

var regsiter_vy_times = null;  	//定义时间
var is_lock_send_vys = false;	//解除锁定
var left_rg_times = 0;			//开始时间
function left_time_to_send_regvys(){
	clearTimeout(regsiter_vy_times);
	if(left_rg_times > 0){
		regsiter_vy_times = setTimeout(left_time_to_send_regvys,1000);
		$("#getVerify").val(left_rg_times+"秒后重新获取验证码");
		$("#getVerify").addClass("btn_disable");
		left_rg_times -- ;
	}
	else{
		is_lock_send_vys = false;
		$("#getVerify").removeClass("btn_disable");
		$("#getVerify").val("重新获取验证码");
		left_rg_times = 0;
	}
}
/**
* obj input file 对象
* nbind 要存放图片的对象. 
* type = 0,评论上传图片;type=1,会员头像;type=2,商品图片;type=3,店铺头像;
*/
function UpImagesfile(obj,nbind) {
		var oFile = obj[0].files[0];
		//console.log(oFile);
		if(!oFile){
			AlertMsg('没有找到图片');
			return false;
		}
		if (oFile.size > 2000 * 1024) {
			AlertMsg('请上传小于2M的图片');
			return false;
		}

		// prepare HTML5 FileReader
		var oReader = new FileReader();
		oReader.onload = function(e) {
			nbind.attr('src',e.target.result);
			nbind.attr('filename',oFile.name);
		};

		oReader.onprogress = function(e) {
			//console.log('传输中....');	
		};

		oReader.readAsDataURL(oFile);
		
		return oFile.size;
}	

$(document).ready(function(){
	$('img').css({"cursor":"pointer"});
	$('img').click(function(){
		var src = $(this).attr('src');
		if(src != ''){
			$("#imgmodal").find('img').attr('src',src);
			$("#imgmodal").find('img').css({'width':width*0.9+"px","height":height*0.8+"px","cursor":"pointer"});
			$("#imgmodal").modal({width:width*0.94,height:height*0.86});
		}
	
	});
	
	$("a[id='del']").click(function(){
		if(!confirm("你确定要删除这条数据吗?")){
			return false;
		}
	})
	

	
});