$(document).ready(function(){
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    /** 基础数据，区域或专业岗位 */
    $(".update_area_major").click(function(){
        var id = $(this).data("id");
        var name = $(this).data("name");
        $("input[name=id]").val(id);
        $("input[name=name]").val(name);
        $(".modal-title").text("修改区域");
    });

    /** 同意发票申请 */
    var agree_invoiceFlag = false;
    $(".agree_invoice").click(function(){
        if(agree_invoiceFlag){
            alert("修改中，请勿重复点击");
            return false;
        }
        var id = $(this).data("id");
        var $this = $(this);
        $this.text("修改中");
        agree_invoiceFlag = true;
        $.post("?r=invoice/change-state",{invoiceId:id,state:'agree'},function(){
            $(".invoice_"+id).hide();
        });
    });

    /** 拒绝发票申请 */
    var refuse_invoiceFlag = false;
    $(".refuse_invoice").click(function(){
        if(refuse_invoiceFlag){
            alert("修改中，请勿重复点击");
            return false;
        }
        var id = $(this).data("id");
        var $this = $(this);
        $this.text("修改中");
        refuse_invoiceFlag = true;
        $.post("?r=invoice/change-state",{invoiceId:id,state:'refuse'},function(){
            $(".invoice_"+id).hide();
        });
    });

    /** 填写快递单号 */
    $(".edit_number").click(function(){
        var id = $(this).data("id");
        $("input[name=invoiceId]").val(id);
        $(".invoice_nickname").text($(".invoice_nickname_"+id).text());
        $(".invoice_money").text($(".invoice_money_"+id).text());
        $(".invoice_createDate").text($(".invoice_createDate_"+id).text());
        $(".invoice_state").text($(".invoice_state_"+id).text());
    });

    /** 模拟考试模板相关 */
    /** 修改启用状态 */
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
        $.post("?r=exam-template/change-state",{_csrf: csrfToken,newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("已启用");
            }else if(data == 'close'){
                $(".state_"+id).text("未启用");
            }else{
                alert(data);
            }
        });
    });

    /** 编辑 */
    $(".template-edit").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("试题模板启用中不能修改");
            return false;
        }
    });

    /** 删除*/
    $(".template-delete").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("试题模板启用中不能删除");
            return false;
        }else{
            return confirm("该模板删除后将不能恢复，确定删除？");
        }
    });

    /** 模板组题数量填写 */
    $(".btn_p").change(function(){
        var p = $(this).data("p");
        if(isNaN($(this).val())){
            alert("请填写数字");
            $(this).focus();
            return false;
        }
        var total = 0;
        $("."+p).each(function(){
            var val = parseInt($(this).val());
            if(!isNaN(val)){
                total += val;
            }
        });
        $(".total_"+p).text(total);
        $("input[name=number_"+p+"]").val(total);
    });

    /** 咨询与建议相关 */
    /** 回复 */
    $(".reply_service").click(function(){
        var id = $(this).data("id");
        $("input[name=serviceId]").val(id);
        $(".reply_nickname").text($(".nickname_"+id).text());
        $(".reply_content").text($(".content_"+id).text());
    });

    /** 发布 */
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