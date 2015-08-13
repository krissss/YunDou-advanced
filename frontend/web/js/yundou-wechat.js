$(document).ready(function(){
    wx.checkJsApi({
        jsApiList: ['onMenuShareTimeline'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
        success: function(res) {
            console.log(res);
            alert(res);
            // 以键值对的形式返回，可用的api值true，不可用为false
            // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
        }
    });

    $(".share").click(function(){
        wx.onMenuShareTimeline({
            title: '你好吗', // 分享标题
            link: 'http://baidu.com', // 分享链接
            imgUrl: '', // 分享图标
            success: function () {
                alert('success');
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                alert('cancel');
                // 用户取消分享后执行的回调函数
            }
        });
    });
});