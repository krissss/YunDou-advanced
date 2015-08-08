$(document).ready(function(){
    $(".checkbox-state").change(function(){
        var state = "";
        var id = $(this).data("id");
        if($(this).attr("checked") == "checked"){   //关闭操作
            $(this).removeAttr("checked");
            state = "close";
        }else{  //开启操作
            if(parseInt($(".question_count_"+id).text()) == 0){
                $(this).removeAttr("checked");
                alert("总题数为0，不能启用该模板");
                return false;
            }else{
                $(this).attr("checked","checked");
                state = "open";
            }
        }
        $.post("?r=exam-template/change-state",{newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("已启用");
            }else if(data == 'close'){
                $(".state_"+id).text("未启用");
            }else{
                alert(data);
            }
        });
    });

    $(".template-edit").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("试题模板启用中不能修改");
            return false;
        }
    });

    $(".template-delete").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("试题模板启用中不能删除");
            return false;
        }else{
            return confirm("该模板删除后将不能恢复，确定删除？");
        }
    });
});
