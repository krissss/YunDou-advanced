$(document).ready(function(){

    /** 基础数据，区域或专业岗位 */
    $(".update_area_major").click(function(){
        var id = $(this).data("id");
        var name = $(this).data("name");
        $("input[name=id]").val(id);
        $("input[name=name]").val(name);
        $(".modal-title").text("修改区域");
    });
});