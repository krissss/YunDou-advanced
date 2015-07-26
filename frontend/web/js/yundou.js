$(document).ready(function(){
    /**
     * 答对答错的解析显示
     */
    $(function(){
        $("#analysis").hide();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var answer = $("#answer").data("answer");
        var user_answer = "";
        var testLibraryId = parseInt($("#testLibraryId").data("id"));
        var answerType = "";

        //单选
        $("input[name=danxuan]").change(function(){
            user_answer = $("input[name=danxuan]:checked").val();
            if(user_answer == answer){
                answerType = 'right';
                $(this).parent(".mui-radio").addClass("right");
            }else{
                answerType = 'wrong';
                $(this).parent(".mui-radio").addClass("wrong");
                $("#"+answer).addClass("right");
            }
            $("input[name=danxuan]").attr("disabled","disabled");
            $("#analysis").show();
            $.ajax({
                type: "post",
                url: "index.php?r=practice/single-save",
                cache:false,
                async:true,
                data: {_csrf : csrfToken,type:answerType,testLibraryId:testLibraryId},
                dataType: "text",
                success: function (date) {
                    if(date!='success'){
                        alert(date);
                    }
                }
            });
        });

        //判断
        $("input[name=panduan]").change(function(){
            user_answer = $("input[name=panduan]:checked").val();
            if(user_answer == answer){
                answerType = 'right';
                $(this).parent(".mui-radio").addClass("right");
            }else{
                answerType = 'wrong';
                $(this).parent(".mui-radio").addClass("wrong");
                $("#"+answer).addClass("right");
            }
            $("input[name=panduan]").attr("disabled","disabled");
            $("#analysis").show();
            $.ajax({
                type: "post",
                url: "index.php?r=practice/single-save",
                cache:false,
                async:true,
                data: {_csrf : csrfToken,type:answerType,testLibraryId:testLibraryId},
                dataType: "text",
                success: function (date) {
                    if(date!='success'){
                        alert(date);
                    }
                }
            });
        });

        //多选
        $("input[name=duoxuan]").change(function(){
            user_answer = "";
            $("input[name=duoxuan]:checked").each(function(){
                user_answer += $(this).val();
            });
            if(user_answer){
                $("#duoxuan-ok").addClass("question-btn-active");
            }else{
                $("#duoxuan-ok").removeClass("question-btn-active");
            }
        });
        $("#duoxuan-ok").click(function(){
            if($(this).hasClass("question-btn-active")){
                var i=0;
                if(user_answer == answer){
                    answerType = 'right';
                    for(i=0; i<answer.length; i++){
                        $("#"+answer[i]).addClass("right");
                    }
                }else{
                    answerType = 'wrong';
                    for(i=0; i<answer.length; i++){
                        $("#"+answer[i]).addClass("right");
                    }
                    for(i=0; i<user_answer.length; i++){
                        var obj = $("#"+user_answer[i]);
                        if(!obj.hasClass("right")){
                            obj.addClass("wrong");
                        }
                    }
                }
                $("input[name=duoxuan]").attr("disabled","disabled");
                $("#analysis").show();
                $.ajax({
                    type: "post",
                    url: "index.php?r=practice/single-save",
                    cache:false,
                    async:true,
                    data: {_csrf : csrfToken,type:answerType,testLibraryId:testLibraryId},
                    dataType: "text",
                    success: function (date) {
                        if(date!='success'){
                            alert(date);
                        }
                    }
                });
            }
        });

        //案例
        var questionNumber = $(".mui-input-group").length;
        $("input[type=radio]").change(function(){
            var parent = $(this).parent().parent(".mui-input-group");
            parent.addClass("done");
            parent.attr("data-user_answer",$(this).val());
            if($(".done").length == questionNumber){
                $("#anli-ok").addClass("question-btn-active");
            }
        });
        $("#anli-ok").click(function(){
            if($(".done").length == questionNumber){
                var obj = "";
                for(var i=1; i<=questionNumber; i++){
                    obj = $("#question_"+i);
                    answer = obj.data('answer');
                    user_answer = obj.data('user_answer');
                    if(answer == user_answer){
                        answerType = 'right';
                        obj.children("."+answer).addClass("right");
                    }else{
                        answerType = 'wrong';
                        obj.children("."+user_answer).addClass("wrong");
                        obj.children("."+answer).addClass("right");
                    }
                }
                $("input[type=radio]").attr("disabled","disabled");
                $("#analysis").show();
                $.ajax({
                    type: "post",
                    url: "index.php?r=practice/single-save",
                    cache:false,
                    async:true,
                    data: {_csrf : csrfToken,type:answerType,testLibraryId:testLibraryId},
                    dataType: "text",
                    success: function (date) {
                        if(date!='success'){
                            alert(date);
                        }
                    }
                });
            }
        });
    });

});
