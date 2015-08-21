$(document).ready(function(){
    var body = $("body");
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    /**  account/register */
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
            if(leftTime <= 0){
                window.clearInterval(setIntervalResult);
                $this.text("获取验证码");
                $this.removeClass("btn-default").addClass("btn-primary");
                yzmFlag = false;
            }else{
                $this.text(--leftTime + "秒后可重新获取");
            }
        },1000);
        $.post("?r=account/get-yzm",{_csrf: csrfToken,mobile:mobile},function(data){
            if(data == true){
                alert("发送成功");
            }else{
                alert(data);    //调试用，上线后不应该弹出
            }
        });
    });

    /** practice/index */
    var pay_modal = $("#pay_modal");

    var redirect_url = "";

    $(".show_modal").click(function(){
        if(pay_modal.length==0){
            redirect($(this).data("href"));
        }else{
            redirect_url = $(this).data("href");
            pay_modal.modal('show');
        }
    });

    $(".show_modal_restart").click(function(){
        var r = confirm("重新开始将重置顺序练习进度");
        if(r == true){
            if(pay_modal.length==0){
                redirect($(this).data("href"));
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
        $.post("?r=account/pay", {_csrf: csrfToken},function(data){
            if(data!=true){
                alert(data);
            }else{
                alert("支付成功");
                pay_modal.modal('hide');
                if(redirect_url){
                    redirect(redirect_url);
                }
            }
            pay_click_flag = false;
        });
    });

    $(".pay_redirect").click(function(){
        redirect($(this).data("href"));
    });

    function redirect(url){
        $(".loading").show();
        window.location.href=url;
    }


    /** practice/test */
    $(".loading").hide();

    var questionNumber; //题号，获取当前题号，用于显示上下一题
    var minNumber = parseInt($("input[name=minNumber]").val()); //当前显示的最小题号
    var maxNumber = parseInt($("input[name=maxNumber]").val()); //当前显示的最大题号

    //var testLibraries = $(".test_library");

    var examFlag = $("input[name=examFlag]").val(); //模拟考试的标志

    if(examFlag){   //如果是模拟考试
        questionNumber = 0;
        //模拟考试倒计时
        var TOTAL_TIME = 150;    //总时间
        var leftTime = 150;    //剩余时间
        var setIntervalResult = setInterval(function(){ //每分钟减一
            if(leftTime == 0){
                window.clearInterval(setIntervalResult);
                over();
            }
            $(".time").text(leftTime--);
        },60000);
    }else{
        questionNumber= $("input[name=currentNumber]").val();
        var currentTestLibraryId = $("input[name=currentTestLibraryId]").val(); //当前题目的testLibraryId，用于判断是否要记录当前做到哪一题
    }
    //显示第一题
    $(".test_library_"+questionNumber).show();
    var defaultOnceNumber = 10;  //需要与params-local.php中保持一致
    //显示上一题
    var minNumberFlag = false;
    body.on("click",".previous_text_library",function(){
        if(minNumber == questionNumber){
            alert("题目还没加载出来，请稍后点击");
        }else{
            $(".test_library_"+questionNumber).hide();
            $(".test_library_"+(--questionNumber)).show();
        }
        if(examFlag != 'examFlag') {   //非模拟考试
            if ((minNumber + 5) >= questionNumber) {    //当前题号达到一定值时需要去ajax获取数据
                if (minNumberFlag) {  //避免点击后还没请求完再次请求
                    return false;
                } else {
                    minNumberFlag = true;
                    $.post("?r=practice/get-data", {_csrf: csrfToken,minNumber: minNumber}, function (data) {
                        body.append(data);
                        minNumber = (minNumber - defaultOnceNumber < 0) ? 0 : minNumber - defaultOnceNumber;
                        minNumberFlag = false;
                    });
                }
            }
        }
    });
    //显示下一题
    var maxNumberFlag = false;
    var totalNumber = parseInt($("input[name=totalNumber]").val());
    body.on("click",".next_test_library",function(){
        if(maxNumber == questionNumber){
            alert("题目还没加载出来，请稍后点击");
        }else{
            $(".test_library_"+questionNumber).hide();
            $(".test_library_"+(++questionNumber)).show();
        }
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            if((maxNumber-5) <= questionNumber){    //当前题号达到一定值时需要去ajax获取数据
                if(maxNumberFlag){  //避免点击后还没请求完再次请求
                    return false;
                }else{
                    maxNumberFlag = true;
                    $.post("?r=practice/get-data",{_csrf: csrfToken,maxNumber:maxNumber},function(data){
                        body.append(data);
                        maxNumber = (maxNumber+defaultOnceNumber > totalNumber) ? totalNumber : maxNumber+defaultOnceNumber;
                        maxNumberFlag = false;
                    });
                }
            }
            var id = $(this).data("id");
            if(id > currentTestLibraryId){  //当前点击的题目的id号大于当前题目id号才提交记录
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                var answerType = $("input[name=answer_type_" + id + "]").val();
                $.post("?r=practice/next", {_csrf: csrfToken, type: answerType, testLibraryId: id});
            }
        }
    });

    //最后一题点击后
    $(".over_test_library").click(function(){
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            var id = $(this).data("id");
            if(id > currentTestLibraryId){  //当前点击的题目的id号大于当前题目id号才提交记录

                var answerType = $("input[name=answer_type_" + id + "]").val();
                $.post("?r=practice/next", {_csrf: csrfToken, type: answerType, testLibraryId: id});
            }
        }
        over();
    });

    var result = [];    //存放结果，是一个json数组
    $("input[type=radio]").click(function(){
        var id = $(this).data("id");
        var testType = $(this).data("testtype");
        var preType = $(this).data("pretype");
        var value = $(this).val();
        var trueAnswer;
        var answerType = 0; //0错误，1正确
        if(testType == 4 ){ //案例题
            var testLibraryId = id.split("_")[0];
            var number = id.split("_")[1];
            trueAnswer = $(".true_answer_"+testLibraryId).text();
            var trueAnswers = trueAnswer.split(" ");
            if(value == trueAnswers[number]){
                answerType = 1;
            }else{
                answerType = 0;
            }
            $(".user_answer_"+id).text(value);
        }else{
            trueAnswer = $(".true_answer_"+id).text();
            if(value == trueAnswer){
                answerType = 1;
                $(".answer_type_" + id).text("答案正确");
            }else{
                answerType = 0;
                $(".answer_type_" + id).text("答案错误");
            }
            $(".user_answer_"+id).text(value);
        }
        result.push({testLibraryId:id,answerType:answerType,testType:testType,preType:preType});
    });

    $("input[type=checkbox]").click(function(){
        var id = $(this).data("id");
        var testType = $(this).data("testtype");
        var preType = $(this).data("pretype");
        var value = "";
        $("input[name=input_question_"+id+"]:checked").each(function(){
            value += $(this).val();
        });
        if(testType == 4 ){ //案例题
            var testLibraryId = id.split("_")[0];
            var number = id.split("_")[1];
            trueAnswer = $(".true_answer_"+testLibraryId).text();
            var trueAnswers = trueAnswer.split(" ");
            if(value == trueAnswers[number]){
                answerType = 1;
            }else{
                answerType = 0;
            }
            $(".user_answer_"+id).text(value);
        }else{
            var trueAnswer = $(".true_answer_"+id).text();
            var answerType = 0;
            if(value == trueAnswer){
                answerType = 1; //0错误，1正确
                $(".answer_type_" + id).text("答案正确");
            }else{
                answerType = 0; //0错误，1正确
                $(".answer_type_" + id).text("答案错误");
            }
            $(".user_answer_"+id).text(value);
        }

        result.push({testLibraryId:id,answerType:answerType,testType:testType,preType:preType});

    });

    $(".btn_over").click(function(){
        over();
    });

    $(".show_answer").click(function(){
        var id = $(this).data('id');
        $(".answer_show_" + id).show(200);
    });

    $(".add_collection").click(function(){
        var id = $(this).data('id');
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var $this = $(this);
        if($this.hasClass("btn-danger")){
            $this.removeClass("btn-danger").addClass("btn-primary");
        }else{
            $this.removeClass("btn-primary").addClass("btn-danger");
        }
        $.post("?r=practice/collection", {_csrf: csrfToken, testLibraryId: id},function(data){
            if(data == 'delete'){
                $this.removeClass("btn-danger").addClass("btn-primary");
            }else if(data == 'collected'){
                $this.removeClass("btn-primary").addClass("btn-danger");
            }else{
                alert("返回值无效，收藏失败");
            }
        });
    });

    function over(){
        if(examFlag) {   //如果是模拟考试
            post("?r=exam/over",JSON.stringify(result),TOTAL_TIME-leftTime);
        }else{
            post("?r=practice/over",JSON.stringify(result),null);
        }
    }

    function post(URL, jsonArray, time) {
        var temp = document.createElement("form");
        temp.action = URL;
        temp.method = "post";
        temp.style.display = "none";
        var opt = document.createElement("textarea");
        opt.name = '_csrf';
        opt.value = csrfToken;
        temp.appendChild(opt);
        opt = document.createElement("textarea");
        opt.name = 'result';
        opt.value = jsonArray;
        temp.appendChild(opt);
        if(time!=null){
            opt = document.createElement("textarea");
            opt.name = 'time';
            opt.value = time;
            temp.appendChild(opt);
        }
        document.body.appendChild(temp);
        temp.submit();
        return temp;
    }

    /** 支付界面相关 */
    $(".wxPay").click(function(){
        var money = $("#rechargeform-money").val();
        if(isNaN(money)){
            alert("充值金额必须为数字");
            return false;
        }
        $.post("?r=account/recharge",{_csrf: csrfToken,money:money},function(data){
            body.append(data);
        });
    });

});
