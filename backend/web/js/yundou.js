$(document).ready(function(){
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var body = $("body");

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
        $.post("?r=invoice/change-state",{_csrf: csrfToken,invoiceId:id,state:'agree'},function(){
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
        $.post("?r=invoice/change-state",{_csrf: csrfToken,invoiceId:id,state:'refuse'},function(){
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
    $(".template-checkbox").change(function(){
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
        $.post("?r=service/publish",{_csrf: csrfToken,serviceId:id},function(data){
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

    /** 充值方案相关 */
    /** 添加方案 */
    $(".add_recharge").click(function(){
        $.post("?r=recharge/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_recharge_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_recharge").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能编辑");
            return false;
        }
        $.post("?r=recharge/add-update",{_csrf: csrfToken,schemeId:id},function(data){
            body.append(data);
            $(".add_recharge_modal").last().modal('show');
        });
    });
    /** 修改启用状态 */
    $(".recharge-checkbox").change(function(){
        var state = "";
        var id = $(this).data("id");
        var $this = $(this);
        if($this.attr("checked") == "checked"){   //关闭操作
            $this.removeAttr("checked");
            state = "close";
        }else{  //开启操作
            $this.attr("checked","checked");
            state = "open";
        }
        $.post("?r=recharge/change-state",{_csrf: csrfToken,newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("已启用");
                $this.attr("checked","checked");
            }else if(data == 'close'){
                $(".state_"+id).text("未启用");
                $this.removeAttr("checked");
            }else{
                alert(data);
                $this.removeAttr("checked");
            }
        });
    });
    /** 删除 */
    $(".recharge-delete").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能删除");
            return false;
        }else{
            return confirm("该方案删除后将不能恢复，确定删除？");
        }
    });

    /** 在线练习方案相关 */
    /** 添加方案 */
    $(".add_practice").click(function(){
        $.post("?r=practice-price/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_practice_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_practice").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能编辑");
            return false;
        }
        $.post("?r=practice-price/add-update",{_csrf: csrfToken,schemeId:id},function(data){
            body.append(data);
            $(".add_practice_modal").last().modal('show');
        });
    });
    /** 修改启用状态 */
    $(".practice-checkbox").change(function(){
        var state = "";
        var id = $(this).data("id");
        var $this = $(this);
        if($this.attr("checked") == "checked"){   //关闭操作
            $this.removeAttr("checked");
            state = "close";
        }else{  //开启操作
            $this.attr("checked","checked");
            state = "open";
        }
        $.post("?r=practice-price/change-state",{_csrf: csrfToken,newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("已启用");
                $this.attr("checked","checked");
            }else if(data == 'close'){
                $(".state_"+id).text("未启用");
                $this.removeAttr("checked");
            }else{
                alert(data);
                $this.removeAttr("checked");
            }
        });
    });
    /** 删除 */
    $(".practice-delete").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能删除");
            return false;
        }else{
            return confirm("该方案删除后将不能恢复，确定删除？");
        }
    });

    /** 充值返点方案相关 */
    /** 添加方案 */
    $(".add_rebate").click(function(){
        $.post("?r=rebate/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_rebate_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_rebate").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能编辑");
            return false;
        }
        $.post("?r=rebate/add-update",{_csrf: csrfToken,schemeId:id},function(data){
            body.append(data);
            $(".add_rebate_modal").last().modal('show');
        });
    });
    /** 修改启用状态 */
    $(".rebate-checkbox").change(function(){
        var state = "";
        var id = $(this).data("id");
        var $this = $(this);
        if($this.attr("checked") == "checked"){   //关闭操作
            $this.removeAttr("checked");
            state = "close";
        }else{  //开启操作
            $this.attr("checked","checked");
            state = "open";
        }
        $.post("?r=rebate/change-state",{_csrf: csrfToken,newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("已启用");
                $this.attr("checked","checked");
            }else if(data == 'close'){
                $(".state_"+id).text("未启用");
                $this.removeAttr("checked");
            }else{
                alert(data);
                $this.removeAttr("checked");
            }
        });
    });
    /** 删除 */
    $(".rebate-delete").click(function(){
        var id = $(this).data("id");
        if($(".checked_"+id).attr("checked") == "checked"){
            alert("方案启用中，不能删除");
            return false;
        }else{
            return confirm("该方案删除后将不能恢复，确定删除？");
        }
    });

    /** 题库相关 */
    /** 修改题目 */
    $(".update_testLibrary").click(function(){
        var id = $(this).data("id");
        $.post("?r=test-library/update",{_csrf: csrfToken,testLibraryId:id},function(data){
            body.append(data);
            $(".update_testLibrary_modal").last().modal('show');
        });
    });

    /** 报名相关 */
    /** 查看报名信息 */
    $(".view_info").click(function(){
        var id = $(this).data("id");
        $.post("?r=sign-up/view",{_csrf: csrfToken,infoId:id},function(data){
            body.append(data);
            $(".info_modal").last().modal('show');
        });
    });
    /** 填报完成 */
    body.on("click",".sign-up-ok",function(){
        var id = $(this).data("id");
        $.post("?r=sign-up/change-state",{_csrf: csrfToken,infoId:id,state:'ok'},function(data){
            if(data == 'ok'){
                $(".state_"+id).text("已填报");
                $(".btn_"+id).addClass("hidden");
                $(".info_modal").last().modal('hide');
            }else{
                alert(data);
            }
        });
    });
    /** 填报信息有问题 */
    body.on("click",".sign-up-error",function(){
        var id = $(this).data("id");
        var replayContentObj = $(".reply_content_"+id);
        var replayContent = replayContentObj.val();
        if(!replayContent){
            alert("填报有问题需填写问题说明");
            replayContentObj.focus();
            return false;
        }
        $.post("?r=sign-up/change-state",{_csrf: csrfToken,infoId:id,state:'error',replyContent:replayContent},function(data){
            if(data == 'ok'){
                $(".state_"+id).text("填报失败");
                $(".btn_"+id).addClass("hidden");
                $(".info_modal").last().modal('hide');
            }else{
                alert(data);
            }
        });
    });

    /** 2A伙伴相关 */
    /** 添加 */
    $(".add_user_aa").click(function(){
        $.post("?r=user-aa/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_user_aa_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_user_aa").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-aa/add-update",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".add_user_aa_modal").last().modal('show');
        });
    });
    /** 充值 */
    $(".recharge_user_aa").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-aa/recharge",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".recharge_user_aa_modal").last().modal('show');
        });
    });
    /** 修改状态 */
    var stateAAFlag = false;
    $(".state_aa").click(function(){
        if(stateAAFlag){
            alert("状态修改中，请勿重复点击");
            return false;
        }
        stateAAFlag = true;
        var state = $(this).data("state");
        var id = $(this).data("id");
        $.post("?r=user-aa/change-state",{_csrf: csrfToken,newState:state,id:id},function(){
            window.location.href = "?r=user-aa/previous";
        });
    });

    /** 3A伙伴相关 */
    /** 添加 */
    $(".add_user_aaa").click(function(){
        $.post("?r=user-aaa/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_user_aaa_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_user_aaa").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-aaa/add-update",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".add_user_aaa_modal").last().modal('show');
        });
    });
    /** 充值 */
    $(".recharge_user_aaa").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-aaa/recharge",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".recharge_user_aaa_modal").last().modal('show');
        });
    });
    /** 修改状态 */
    var stateAAAFlag = false;
    $(".state_aaa").click(function(){
        if(stateAAAFlag){
            alert("状态修改中，请勿重复点击");
            return false;
        }
        stateAAAFlag = true;
        var state = $(this).data("state");
        var id = $(this).data("id");
        $.post("?r=user-aaa/change-state",{_csrf: csrfToken,newState:state,id:id},function(){
            window.location.href = "?r=user-aaa/previous";
        });
    });

    /** 大客户相关 */
    /** 添加 */
    $(".add_user_big").click(function(){
        $.post("?r=user-big/add-update",{_csrf: csrfToken},function(data){
            body.append(data);
            $(".add_user_big_modal").last().modal('show');
        });
    });
    /** 编辑方案 */
    $(".update_user_big").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-big/add-update",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".add_user_big_modal").last().modal('show');
        });
    });
    /** 充值 */
    $(".recharge_user_big").click(function(){
        var id = $(this).data("id");
        $.post("?r=user-big/recharge",{_csrf: csrfToken,userId:id},function(data){
            body.append(data);
            $(".recharge_user_big_modal").last().modal('show');
        });
    });
    /** 修改状态 */
    var stateBigFlag = false;
    $(".state_big").click(function(){
        if(stateBigFlag){
            alert("状态修改中，请勿重复点击");
            return false;
        }
        stateBigFlag = true;
        var state = $(this).data("state");
        var id = $(this).data("id");
        $.post("?r=user-big/change-state",{_csrf: csrfToken,newState:state,id:id},function(){
            window.location.href = "?r=user-big/previous";
        });
    });

    /** 2A3A公用相关 */
    /** 提现管理 */
    $(".withdraw_btn").click(function(){
        var id = $(this).data("id");
        $.post("?r=withdraw/init",{_csrf: csrfToken,withdrawId:id},function(data){
            body.append(data);
            $(".withdraw_btn_modal").last().modal('show');
        });
    });


    /** 大客户后台相关 */
    /** 分配云豆 */
    $(".distribute_bitcoin").click(function(){
        var id = $(this).data("id");
        $("input[name=distribute_bitcoin_userId]").val(id);
        $(".accept_nickname").text($(".nickname_"+id).text());
    });
    /** 修改关联用户状态 */
    $(".user-big-checkbox").change(function(){
        var state = "";
        var id = $(this).data("id");
        if($(this).attr("checked") == "checked"){   //关闭操作
            $(this).removeAttr("checked");
            state = "close";
        }else{  //开启操作
            $(this).attr("checked","checked");
            state = "open";
        }
        $.post("?r=customer/user/change-state",{_csrf: csrfToken,newState:state,id:id},function(data){
            if(data == 'open'){
                $(".state_"+id).text("正常");
                $(".distribute_bitcoin_"+id).show(100);
            }else if(data == 'close'){
                $(".state_"+id).text("已去除");
                $(".distribute_bitcoin_"+id).hide(100);
            }else{
                alert(data);
            }
        });
    });
    /** 自主充值 */


});