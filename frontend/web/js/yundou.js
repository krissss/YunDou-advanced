$(document).ready(function(){
    /**
     * 答对答错的解析显示
     */
    $(function(){
        if($("#examFlag").val()!="exam") {
            $("#analysis").hide();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            var answer = $("#answer").data("answer");
            var user_answer = "";
            var testLibraryId = parseInt($("#testLibraryId").data("id"));
            var answerType = "";

            //单选
            $("input[name=danxuan]").change(function () {
                user_answer = $("input[name=danxuan]:checked").val();
                if (user_answer == answer) {
                    answerType = 'right';
                    $(this).parent(".mui-radio").addClass("right");
                } else {
                    answerType = 'wrong';
                    $(this).parent(".mui-radio").addClass("wrong");
                    $("#" + answer).addClass("right");
                }
                $("input[name=danxuan]").attr("disabled", "disabled");
                $("#analysis").show();
                $.ajax({
                    type: "post",
                    url: "index.php?r=practice/single-save",
                    cache: false,
                    async: true,
                    data: {_csrf: csrfToken, type: answerType, testLibraryId: testLibraryId},
                    dataType: "text",
                    success: function (date) {
                        if (date != 'success') {
                            alert(date);
                        }
                    }
                });
            });

            //判断
            $("input[name=panduan]").change(function () {
                user_answer = $("input[name=panduan]:checked").val();
                if (user_answer == answer) {
                    answerType = 'right';
                    $(this).parent(".mui-radio").addClass("right");
                } else {
                    answerType = 'wrong';
                    $(this).parent(".mui-radio").addClass("wrong");
                    $("#" + answer).addClass("right");
                }
                $("input[name=panduan]").attr("disabled", "disabled");
                $("#analysis").show();
                $.ajax({
                    type: "post",
                    url: "index.php?r=practice/single-save",
                    cache: false,
                    async: true,
                    data: {_csrf: csrfToken, type: answerType, testLibraryId: testLibraryId},
                    dataType: "text",
                    success: function (date) {
                        if (date != 'success') {
                            alert(date);
                        }
                    }
                });
            });

            //多选
            $("input[name=duoxuan]").change(function () {
                user_answer = "";
                $("input[name=duoxuan]:checked").each(function () {
                    user_answer += $(this).val();
                });
                if (user_answer) {
                    $("#duoxuan-ok").addClass("question-btn-active");
                } else {
                    $("#duoxuan-ok").removeClass("question-btn-active");
                }
            });
            $("#duoxuan-ok").click(function () {
                if ($(this).hasClass("question-btn-active")) {
                    var i = 0;
                    if (user_answer == answer) {
                        answerType = 'right';
                        for (i = 0; i < answer.length; i++) {
                            $("#" + answer[i]).addClass("right");
                        }
                    } else {
                        answerType = 'wrong';
                        for (i = 0; i < answer.length; i++) {
                            $("#" + answer[i]).addClass("right");
                        }
                        for (i = 0; i < user_answer.length; i++) {
                            var obj = $("#" + user_answer[i]);
                            if (!obj.hasClass("right")) {
                                obj.addClass("wrong");
                            }
                        }
                    }
                    $("input[name=duoxuan]").attr("disabled", "disabled");
                    $("#analysis").show();
                    $.ajax({
                        type: "post",
                        url: "index.php?r=practice/single-save",
                        cache: false,
                        async: true,
                        data: {_csrf: csrfToken, type: answerType, testLibraryId: testLibraryId},
                        dataType: "text",
                        success: function (date) {
                            if (date != 'success') {
                                alert(date);
                            }
                        }
                    });
                }
            });

            //案例
            var questionNumber = $(".mui-input-group").length;
            $("input[type=radio]").change(function () {
                var parent = $(this).parent().parent(".mui-input-group");
                parent.addClass("done");
                parent.attr("data-user_answer", $(this).val());
                if ($(".done").length == questionNumber) {
                    $("#anli-ok").addClass("question-btn-active");
                }
            });
            $("#anli-ok").click(function () {
                if ($(".done").length == questionNumber) {
                    var obj = "";
                    for (var i = 1; i <= questionNumber; i++) {
                        obj = $("#question_" + i);
                        answer = obj.data('answer');
                        user_answer = obj.data('user_answer');
                        if (answer == user_answer) {
                            answerType = 'right';
                            obj.children("." + answer).addClass("right");
                        } else {
                            answerType = 'wrong';
                            obj.children("." + user_answer).addClass("wrong");
                            obj.children("." + answer).addClass("right");
                        }
                    }
                    $("input[type=radio]").attr("disabled", "disabled");
                    $("#analysis").show();
                    $.ajax({
                        type: "post",
                        url: "index.php?r=practice/single-save",
                        cache: false,
                        async: true,
                        data: {_csrf: csrfToken, type: answerType, testLibraryId: testLibraryId},
                        dataType: "text",
                        success: function (date) {
                            if (date != 'success') {
                                alert(date);
                            }
                        }
                    });
                }
            });
        }else{
            var number = 1; //题目编号
            var max = parseInt($(".totalNumber").text());   //题目数
            var lastFlag = false;   //最后一题的标志
            var anliFlag = false;   //案例题的标志
            $(".question_"+number).removeClass("mui-hidden");
            $(".next_question").click(function(){
                var input = $(".input_question_"+number);
                if(input.length==0){    //表明当前题目是案例题
                    input = $(".input_question_anli_"+number);
                    anliFlag = true;
                }
                var userAnswer = "";
                var length = input.length;
                for(var i=0;i<length;i++){
                    if(input[i].checked){
                        if(anliFlag){   //案例题答案合成
                            userAnswer += input[i].value+"}";
                        }else{  //非案例题答案合成
                            userAnswer += input[i].value;
                        }
                    }
                }
                if(userAnswer){
                    $(".answer_"+number).attr("data-useranswer",userAnswer);
                }
                if(!lastFlag){  //点击到最后一题
                    $(".question_"+number).addClass("mui-hidden");
                    $(".question_"+(++number)).removeClass("mui-hidden");
                    $(".currentNumber").text(number);
                    if(number >= max){  //点到最后一题
                        $(this).text("完成");
                        lastFlag = true;
                    }
                }else{  //点击完成按钮后的处理
                    $("header").hide(); //隐藏header
                    $(".question_"+number).addClass("mui-hidden");  //隐藏最后一题
                    $(this).hide(); //隐藏完成按钮
                    var rightNumber = 0;
                    var wrongNumber = 0;
                    $(".allAnswers").each(function(key,obj){
                        if($(obj).data('answer') == $(obj).data('useranswer')){
                            rightNumber++;
                        }else{
                            wrongNumber++;
                        }
                    });
                    $(".rightNumber").text(rightNumber);
                    $(".wrongNumber").text(wrongNumber);
                    $("#over").removeClass("mui-hidden");   //显示结束界面
                }
            });
        }
    });

});
