$(document).ready(function(){

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
        $.post("?r=invoice/change-state",{invoiceId:id,state:'agree'},function(){
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
        $.post("?r=invoice/change-state",{invoiceId:id,state:'refuse'},function(){
            $(".invoice_"+id).hide();
        });
    });

    $(".edit_number").click(function(){
        var id = $(this).data("id");
        $("input[name=invoiceId]").val(id);
        $(".invoice_nickname").text($(".invoice_nickname_"+id).text());
        $(".invoice_money").text($(".invoice_money_"+id).text());
        $(".invoice_createDate").text($(".invoice_createDate_"+id).text());
        $(".invoice_state").text($(".invoice_state_"+id).text());
    });
});