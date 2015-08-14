$(document).ready(function(){
    $(".edit_number").click(function(){
        var id = $(this).data("id");
        $(".invoiceId").val(id);
    });
});
