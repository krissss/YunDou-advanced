$(document).ready(function(){
    $("#analysis").hide();
    var answer = $("#answer").data("answer");
    var user_answer = "";
    $("input[type=radio]").change(function(){
        user_answer = $("input[type=radio]:checked").val();
        if(user_answer == answer){
            $(this).parent(".mui-radio").addClass("right");
        }else{
            $(this).parent(".mui-radio").addClass("wrong");
            $("#"+answer).addClass("right");
        }
        $("input[type=radio]").attr("disabled","disabled");
        $("#analysis").show();
    });

});
