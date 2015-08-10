$(document).ready(function(){
    var number = 0;
    var testLibraries = $(".test_library");
    testLibraries.eq(number).show();

    var examFlag = $("input[name=examFlag]").val();

    var showAnswerFlag = false; //是否点击确定的标志

    $(".show_answer").click(function(){
        showAnswerFlag = true;
        var id = $(this).data('id');
        $("input[name=input_question_" + id + "]").each(function () {
            value += $(this).attr("disabled","disabled");
        });
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            var value = "";
            $("input[name=input_question_" + id + "]:checked").each(function () {
                value += $(this).val();
            });
            var trueAnswer = $(".true_answer_" + id).text();
            $(".user_answer_" + id).text(value);
            if (value == trueAnswer) {
                $("input[name=answer_type_" + id + "]").val("right");
                $(".answer_type_" + id).text("答案正确");
            } else {
                $("input[name=answer_type_" + id + "]").val("wrong");
                $(".answer_type_" + id).text("答案错误");
            }
            $(".answer_show_" + id).show(200);
        }
    });

    $(".show_answer_anli").click(function(){
        showAnswerFlag = true;
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            var id = $(this).data('id');
            var number = $(this).data('number');    //大题包含的小题数
            var trueAnswers = $("input[name=true_answer_" + id + "]").val();    //正确答案
            var trueAnswerArray = trueAnswers.split("}");   //将正确答案分割成数组
            var userAnswer = "";    //填写用户答案
            var trueAnswer = "";    //填写正确答案
            var trueNumber = 0;     //正确题数
            for (var i = 0; i < number; i++) {
                var value = "";
                var innerId = "input_question_" + id + "_" + i;
                $("input[name=" + innerId + "]:checked").each(function () {
                    value += $(this).val();
                });
                if (value) {
                    userAnswer += value + " ";
                    if (value == trueAnswerArray[i]) {
                        trueNumber++;
                    }
                } else {
                    userAnswer += "&nbsp;&nbsp;" + " ";
                }
                trueAnswer += trueAnswerArray[i] + " ";
            }
            if (trueNumber != number) {  //不全正确则添加wrong，用于保存错题
                $("input[name=answer_type_" + id + "]").val("wrong");
            } else {
                $("input[name=answer_type_" + id + "]").val("right");
            }
            $(".answer_type_" + id).text("正确" + trueNumber + "题,错误" + (number - trueNumber) + "题");
            $(".true_answer_" + id).html(trueAnswer);
            $(".user_answer_" + id).html(userAnswer);
            $(".answer_show_" + id).show(200);
        }
    });

    $(".next_test_library").click(function(){
        if(!showAnswerFlag){    //如果没有点击过确定按钮
            alert("请先点击确定后再做下一题");
            return false;
        }
        showAnswerFlag = false; //显示下一题前把该标志置为false，保证下一题也需要做确定判断
        //显示下一题
        testLibraries.eq(number).hide();
        number++;
        var currentNumber = $(".current_number");
        currentNumber.text(parseInt(currentNumber.text())+1);
        testLibraries.eq(number).show();
        if(examFlag != 'examFlag') {    //非模拟考试情况下执行
            //提交数据
            var id = $(this).data("id");
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            var answerType = $("input[name=answer_type_" + id + "]").val();
            $.post("?r=practice/next", {_csrf: csrfToken, type: answerType, testLibraryId: id});
        }
    });
});