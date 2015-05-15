<?php

//调用SDK
require_once "jssdk.php";
$jssdk = new JSSDK("", " ");
$signPackage = $jssdk->GetSignPackage();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<title>简略微信分享demo---</title>

    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        wx.config({
            debug: false,
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: <?php echo $signPackage["timestamp"];?>,
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'hideMenuItems'
            ]
        });
        wx.ready(function () {
            var shareData = {
                title: shareTilte, //分享出去的标题
                desc: shareTilte, //描述信息
                link: shareLink,  //要分享出去的链接
                imgUrl: shareIcon, //图标
                success: function () {
                    //分享成功  检测事件 
                    
                },
                cancel: function () { 
					//取消分享了
                }
            };
            var shareData2 = {
                title: shareTilte,
                desc: shareTilte,
                link: shareLink,
                imgUrl: shareIcon,
                success: function () {
                    //分享成功  检测事件 
                },
                cancel: function () { 
					//取消分享了
                }
            };
            wx.onMenuShareTimeline(shareData); //分享到朋友圈
            wx.onMenuShareAppMessage(shareData2); //发送给好友
            wx.hideMenuItems({  //按钮显示控制
                menuList: [
                    'menuItem:share:qq',
                    'menuItem:share:weiboApp',
                    'menuItem:favorite',
                    'menuItem:share:facebook',
                    'menuItem:copyUrl',
                    'menuItem:readMode',
                    'menuItem:openWithQQBrowser',
                    'menuItem:openWithSafari'
                ]
            });
        });
    </script>
</body>
</html>