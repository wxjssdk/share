<?php

//����SDK
require_once "jssdk.php";
$jssdk = new JSSDK("", " ");
$signPackage = $jssdk->GetSignPackage();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<title>����΢�ŷ���demo---</title>

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
                title: shareTilte, //�����ȥ�ı���
                desc: shareTilte, //������Ϣ
                link: shareLink,  //Ҫ�����ȥ������
                imgUrl: shareIcon, //ͼ��
                success: function () {
                    //����ɹ�  ����¼� 
                    
                },
                cancel: function () { 
					//ȡ��������
                }
            };
            var shareData2 = {
                title: shareTilte,
                desc: shareTilte,
                link: shareLink,
                imgUrl: shareIcon,
                success: function () {
                    //����ɹ�  ����¼� 
                },
                cancel: function () { 
					//ȡ��������
                }
            };
            wx.onMenuShareTimeline(shareData); //��������Ȧ
            wx.onMenuShareAppMessage(shareData2); //���͸�����
            wx.hideMenuItems({  //��ť��ʾ����
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