$(document).ready(function(){
    var body = $("body");
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    /** 实名认证相关 */
    /** 获取手机验证码 */
    var yzmFlag = false;    //是否正在获取验证码的标志
    $(".get_yzm").click(function(){
        var verifyCode = $("#updatecellphoneform-verifycode");
        if(verifyCode.length>0){    //存在图片验证码，需要先验证图片验证码
            if(!verifyCode.val()){
                alert("请先填写图片验证码");
                verifyCode.focus();
                return false;
            }
        }
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
                console.log(data);
            }
        });
    });
    /** 验证推荐人 */
    var validateRecommendFlag = false;  //防止频繁点击
    $(".validate_recommend").click(function(){
        if(!validateRecommendFlag){
            var recommendCode = $("#registerform-tjm").val();
            if(recommendCode){
                var $this = $(this);
                $this.text("检查中。。。");
                validateRecommendFlag = true;
                $.post("?r=account/get-recommend",{_csrf: csrfToken,recommendCode:recommendCode},function(data){
                    $this.text("推荐人检查");
                    validateRecommendFlag = false;
                    alert(data);
                });
            }else{
                alert("请先填写推荐码");
            }
        }
    });

    $(".registerSubmit").click(function(){
        if($("input[name=agreement]:checked").length<1){
            alert("必须先同意云豆讲堂服务协议");
            return false;
        }else{
            $("#account-register").submit();
        }
    });

    /** 注册和修改信息页面的省份和专业的级联 */
    body.on("click",".majorJob_select",function(){  //专业修改
        var id = $(this).data("id");
        $(".majorJob_input").val($(this).text().trim());    //修改显示的字
        $(".majorJob_hidden").val(id);  //修改实际的值
        $(".majorJob_select").removeClass("active");   //去除所有的已选中
        $(this).addClass("active"); //当前选中的添加已选中
        $(".modal").modal("hide");
    });
    body.on("click",".province_select",function(){  //省份修改
        var id = $(this).data("id");
        $(".province_input").val($(this).text().trim());
        $(".province_hidden").val(id);
        $(".province_select").removeClass("active");
        $(this).addClass("active");

        $(".majorJob_input").val("");   //置空专业显示的字
        $(".majorJob_hidden").val("");  //置空专业的值
        $(".majorJob_select").removeClass("active").removeClass("pic_box_2").addClass("pic_box_2_hide");   //取消专业选中状态，并让所有隐藏
        $(".province_"+id).removeClass("pic_box_2_hide").addClass("pic_box_2"); //显示当前省份的专业

        $(".modal").modal("hide");
    });
    $(".province_select").click(function(){
        var provinceId = $(this).val();
        $(".major_select").val("");
        $(".province_major").hide();
        $(".province_"+provinceId).show();
    });

    /** 练习首页相关 */
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

    /** 做题付款 */
    $(".practice_select").click(function(){
        var id = $(this).data("id");
        $("input[name=schemeId]").val(id);
        $(".practice_select").each(function(){
            $(this).removeClass("active");
        });
        $(this).addClass("active");
    });
    var pay_click_flag = false;
    $(".pay_click").click(function(){
        if(pay_click_flag){
            alert("正在支付，请勿频繁点击");
            return false;
        }
        pay_click_flag = true;
        var schemeId = $("input[name=schemeId]").val();
        $.post("?r=account/pay", {_csrf: csrfToken,schemeId:schemeId},function(data){
            var data_msg = data.substr(0,7);
            if(data_msg=='success'){
                alert(data.substr(7));
                pay_modal.modal('hide');
                if(redirect_url){
                    redirect(redirect_url);
                }
            }else{
                alert(data);
            }
            pay_click_flag = false;
        });
    });

    $(".pay_redirect").click(function(){
        redirect($(this).data("href"));
    });

    function redirect(url){
        $(".loading p").text("页面跳转中，请稍等");
        $(".loading").show();
        window.location.href=url;
    }

    /** 做题页面相关 */
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
            next($(this));
        }
    });

    //最后一题点击后
    body.on("click",".over_test_library",function(){
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            next($(this));
        }
        over();
    });

    /** 点击下一题和点击最后一题都要执行的方法 */
    function next($this){
        var id = $this.data("id");
        if(id >= currentTestLibraryId){  //当前点击的题目的id号大于当前题目id号才提交记录
            var lastResult = result[result.length-1];   //获取结果数组中的最后一个
            var answerType = 2; //无意义
            if(lastResult && lastResult['testLibraryId']==id){    //如果相等，即点击下一题时是当前题的答案
                answerType = lastResult['answerType'];
            }
            $.post("?r=practice/next", {_csrf: csrfToken, type: answerType, testLibraryId: id});
        }
    }

    var result = [];    //存放结果，是一个json数组
    body.on("click","input[type=radio]",function(){
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

    body.on("click","input[type=checkbox]",function(){
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

    body.on("click",".btn_over",function(){
        over();
    });

    /** 显示答案 */
    body.on("click",".show_answer",function(){
        var id = $(this).data('id');
        $(".answer_show_" + id).show(100);
        var testLibraryId = $(".id_"+id);
        testLibraryId.find(":radio").each(function(){
            if($(this).data('answer').indexOf($(this).val())>-1){
                $(this).parents(".radio").addClass("bg-success");
            }
        });
        testLibraryId.find(":checkbox").each(function(){
            console.log($(this).data('answer').indexOf($(this).val()));
            if($(this).data('answer').indexOf($(this).val())>-1){
                $(this).parents(".checkbox").addClass("bg-success");
            }
        });
    });

    body.on("click",".add_collection",function(){
        var id = $(this).data('id');
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
    /** 充值金额改变 */
    $("input[name=money]").change(function(){
        var money = parseInt($(this).val());
        var proportion = parseInt($("input[name=proportion]").val());
        var bitcoin = money*proportion;
        $(".get_bitcoin").text(bitcoin);
        var rebate = 0;
        var rebate_bitcoin = $(".rebate_bitcoin");
        if(rebate_bitcoin.length>0){
            var rebate_money = rebate_bitcoin.data('money');
            var rebate_rebate = rebate_bitcoin.data('rebate');
            if(money >= rebate_money){
                rebate = bitcoin*rebate_rebate;
                rebate_bitcoin.text(rebate);
            }
        }
        $(".total_bitcoin").text(bitcoin+rebate);
    });

});
