$(document).ready(function(){
    $(".loading").hide();

    var questionNumber; //题号，获取当前题号，用于显示上下一题
    var testLibraries = $(".test_library");

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
    testLibraries.eq(questionNumber).show();

    //显示上一题
    $(".previous_text_library").click(function(){
        testLibraries.eq(questionNumber).hide();
        questionNumber--;
        testLibraries.eq(questionNumber).show();
    });

    //显示下一题
    $(".next_test_library").click(function(){
        testLibraries.eq(questionNumber).hide();
        questionNumber++;
        testLibraries.eq(questionNumber).show();
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
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
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
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
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
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
});