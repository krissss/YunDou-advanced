$(document).ready(function(){
    var pay_modal = $("#pay_modal");

    var redirect_url = "";

    $(".show_modal").click(function(){
        if(pay_modal.length==0){
            window.location.href=$(this).data("href");
        }else{
            redirect_url = $(this).data("href");
            pay_modal.modal('show');
        }
    });

    $(".show_modal_restart").click(function(){
        var r = confirm("重新开始将重置顺序练习进度");
        if(r == true){
            if(pay_modal.length==0){
                window.location.href=$(this).data("href");
            }else{
                redirect_url = $(this).data("href");
                pay_modal.modal('show');
            }
        }
        return false;
    });

    var pay_click_flag = false;
    $(".pay_click").click(function(){
        if(pay_click_flag){
            alert("正在支付，请勿频繁点击");
            return false;
        }
        pay_click_flag = true;
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.post("?r=account/pay", {_csrf: csrfToken},function(data){
            if(data!=true){
                alert(data);
            }else{
                alert("支付成功");
                if(redirect_url){
                    window.location.href=redirect_url;
                }
            }
            pay_click_flag = false;
        });
    });

    $(".pay_redirect").click(function(){
        window.location.href=$(this).data("href");
    });
});