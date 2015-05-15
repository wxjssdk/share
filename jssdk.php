<?php

// zhanglei 2015-05-01
//测试时打开
//$jssdk = new JSSDK("", "");
//$signPackage = $jssdk->GetSignPackage();
//var_export($signPackage);

class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  public function getJsApiTicket() {
    //jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	$cachefile = "jsapi_ticket.txt";
	$writeFlag = 0;
	if(file_exists($cachefile) and is_readable($cachefile) ){ 
		$data = json_decode(file_get_contents($cachefile), true);

		if ( isset($data["expire_time"]) and $data["expire_time"] < time()) {  
		  $writeFlag = 1;
		} else {
		  if (isset($data["jsapi_ticket"])) {  
				$ticket = $data["jsapi_ticket"];
		  } else {
				$writeFlag = 1;
		  }
		}
	} else {
		$writeFlag = 1;
	}

	if ( $writeFlag == 1 ) {
		$accessToken = $this->getAccessToken();
		  // 如果是企业号用以下 URL 获取 ticket
		  // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
		  $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		  $res = json_decode($this->httpGet($url), true);
		  if (isset($res["ticket"])) {
			  $ticket = $res["ticket"];
			  if ($ticket) {
				$data["expire_time"] = time() + 7000;
				$data["jsapi_ticket"] = $ticket;
				file_put_contents($cachefile, json_encode($data) );
			  }
		  } else {
			  $ticket = "";
		  }
		  
	}

	
    return $ticket;
  }

  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	$cachefile = "access_token.txt";
	if(is_readable($cachefile)){
		if (isset($data["expire_time"]) and $data["expire_time"] < time()) {
		  // 如果是企业号用以下URL获取access_token
		  $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
		  $res = json_decode($this->httpGet($url));
		  $access_token = $res["access_token"];
		  if ($access_token) {
			$data["expire_time"] = time() + 7000;
			$data["access_token"] = $access_token;
			file_put_contents($cachefile, json_encode($data) );
		  }
		} else {
			if (isset($data["access_token"])) {
				 $access_token = $data["access_token"];
			} else {
				$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
				  $res = json_decode($this->httpGet($url), true);
				  if (isset($res["access_token"])) {
					  $access_token = $res["access_token"];
					  if ($access_token) {
						$data["expire_time"] = time() + 7000;
						$data["access_token"] = $access_token;
						file_put_contents($cachefile, json_encode($data) );
					  }
				  } else {
					$access_token = "";
				  }
				  
			}
		}
	} else {
		  $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
		  $res = json_decode($this->httpGet($url),true); 
		  $access_token = $res["access_token"];
		  if ($access_token) {
			$data["expire_time"] = time() + 7000;
			$data["access_token"] = $access_token;
			file_put_contents($cachefile, json_encode($data) );
		  }
	}
    
    return $access_token;

  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
}

