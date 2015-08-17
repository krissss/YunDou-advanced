$(document).ready(function(){
    var yzmFlag = false;    //是否正在获取验证码的标志
    $(".get_yzm").click(function(){
        if(yzmFlag){    //如过正在获取，则不允许再点击
            alert("60秒内请勿重复点击");
            return false;
        }
        yzmFlag = true;
        var mobile = $(".mobile").val();
        var leftTime = 60;
        $this = $(this);
        $this.removeClass("btn-primary").addClass("btn-default");
        $this.text(leftTime + "秒后可重新获取");
        var setIntervalResult = setInterval(function(){ //60秒内最多发一条
            if(leftTime == 0){
                window.clearInterval(setIntervalResult);
                $this.text("获取验证码");
                $this.removeClass("btn-default").addClass("btn-primary");
                yzmFlag = false;
            }else{
                $this.text(--leftTime + "秒后可重新获取");
            }
        },1000);
        $.post("?r=account/get-yzm",{mobile:mobile},function(data){
            if(data == true){
                alert("发送成功");
            }else{
                alert(data);    //调试用，上线后不应该弹出
            }
        });
    });
});