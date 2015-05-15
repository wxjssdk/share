最近微信分享 SDK + demo  (PHP版)

/* zhanglei 2015-05-01

jssdk.php  微信分享SDK
index.php  demo调用例子


注意:改SDK运行时, 会根据appId 和 appSecret 获取微信token用于分享
由于微信接口调用限制, 每次获取token后,会写文件,用于缓存token,待失效后会重新获取
请确保有文件读写的权限。。。