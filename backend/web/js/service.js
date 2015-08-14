$(document).ready(function(){
    $(".reply_service").click(function(){
        var id = $(this).data("id");
        $("input[name=serviceId]").val(id);
        $(".reply_nickname").text($(".nickname_"+id).text());
        $(".reply_content").text($(".content_"+id).text());
    });

    var publishFlag = false;
    $(".publish").click(function(){
        if(publishFlag){
            alert("修改中，请勿重复点击");
            return false;
        }
        var id = $(this).data("id");
        var $this = $(this);
        $this.text("修改中");
        publishFlag = true;
        $.post("?r=service/publish",{serviceId:id},function(data){
            if(data == 'publish'){
                $(".state_"+id).text("已发布");
                $this.text("取消发布");
                publishFlag = false;
            }else if(data == 'replied'){
                $(".state_"+id).text("已回复");
                $this.text("立即发布");
                publishFlag = false;
            }else{
                alert(data);
            }
        });
    });
});