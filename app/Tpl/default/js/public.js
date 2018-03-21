var isSend = false;

function doLottery() {
    if (isSend == true){
        return false;
    }
    isSend = true;
    //roll().init();
    app.ajax("/lottery/doWork",{token:app.session.get("token")},function (e) {
        msg = e.info;
        if (e.code == 1){
            prizeId = e.data.prizeId;
            roll().start();
        }else {
            alert(msg)
			if(msg=="请先填写用户信息,再进行抽奖!"){
				doGoToRegister();
			}
        }
        isSend = false;
    },null,true).post(null,false)
}

function doGoToLottery() {
    $("html,body").animate({scrollTop:$(".win-screen").offset().top},{duration:500,easing:"swing"})
}

function doGoToRegister() {
    $("html,body").animate({scrollTop:$(".reg-explain").offset().top},{duration:500,easing:"swing"})
}
var index = 1;
var msg = null;
var prizeId = 1;
var angle = 0;
var initInterval = null;
var roll = function () {
    return {
        status:0,
        init:function () {
            initInterval = setInterval(function () {
                angle += 180;
                $('#spin-board').rotate(angle);
            }, 50);
        },
        start: function () {
            if(initInterval != null){
                clearInterval(initInterval);
            }
            index++;
            $("#spin-board").rotate({
                duration:3000 * index, //转动时间
                angle: 0,
                animateTo:1800 + prizeId, //转动角度
                easing: $.easing.easeOutSine,
                callback: function(){
                    if (msg != null ){
                        alert(msg);
                    }
                }
            });
        },
        stop: function () {
            this.status = 0;
            this.msg = null;
        }
    }
}

$(document).ready(function() {

    $("#queryForm").on("submit",function () {
        $("#queryForm").find("input[name='password']").val(app.md5($("#queryForm").find("input[name='password']").val()));
    });

    $("#submit-form").on("click",function () {
        app.ajax($("#regForm").attr("action"),$("#regForm").serialize(),function (e) {
            alert(e.info);
            if (e.code == 1){
                app.session.set("token",e.data);
                doGoToLottery();
            }
        }).post()
        return false;
    });

    $("#lottery").on("click",function () {
        doLottery();
    });

    $(".lottery").on("click",function () {
        doLottery();
    });
    $(".lotteryBtn").on("click",function () {
        doGoToLottery();
    });

    $(".registerBtn").on("click",function () {
        doGoToRegister();
    });
    $("#second2").on("click",function () {
        $("html,body").animate({scrollTop:$(".second").offset().top},{duration:500,easing:"swing"})
    });
    $("#fourth2").on("click",function () {
        $("html,body").animate({scrollTop:$(".fourth").offset().top},{duration:500,easing:"swing"})
    });

    $(".dateTimeFull").datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        autoclose: true,
        todayHighlight: true
    });

})

function dialog(str){
	$("#modal-body").text(str);
	$('#bs-example-modal-sm').modal({keyboard: false,show:true});
	
}

$.fn.liScroll = function(options){
    var defaults = {
        speed:90,
        rowHeight:20
    };

    var opts = $.extend({}, defaults, options),intId = [];

    function marquee(obj, step){

        obj.find("ul").animate({
            marginTop: '-=1'
        },0,function(){
            var s = Math.abs(parseInt($(this).css("margin-top")));
            if(s >= step){
                $(this).find("li").slice(0, 1).appendTo($(this));
                $(this).css("margin-top", 0);
            }
        });
    }

    this.each(function(i){
        var sh = opts["rowHeight"],speed = opts["speed"],_this = $(this);
        intId[i] = setInterval(function(){
            if(_this.find("ul").height()<=_this.height()){
                clearInterval(intId[i]);
            }else{
                marquee(_this, sh);
            }
        }, speed);


        _this.hover(
            function(){clearInterval(intId[i]);},
            function(){
                intId[i] = setInterval(function(){
                    if(_this.find("ul").height()<=_this.height()){
                        clearInterval(intId[i]);
                    }else{
                        marquee(_this, sh);
                    }
                }, speed);
            }).trigger("mouseleave");

    });
};