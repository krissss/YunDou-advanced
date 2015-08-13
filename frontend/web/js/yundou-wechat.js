$(document).ready(function(){
    //wx.checkJsApi({
    //    jsApiList: ['onMenuShareTimeline'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
    //    success: function(res) {
    //        console.log(res);
    //        alert(res);
    //        // 以键值对的形式返回，可用的api值true，不可用为false
    //        // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
    //    }
    //});
    wx.ready(function(){
        $(".share").click(function(){
            wx.onMenuShareTimeline({
                title: '互联网之子',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
                trigger: function (res) {
                    alert('用户点击分享到朋友圈');
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        });
    });



});