$(document).ready(function(){
    $(".reply_service").click(function(){
        var id = $(this).data("id");
        $(".serviceId").val(id);
        $(".reply_nickname").text($(".nickname_"+id).text());
        $(".reply_content").text($(".content_"+id).text());
    });
});