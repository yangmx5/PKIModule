<?php
/**
 * Created by PhpStorm.
 * User: this
 * Date: 2017/12/25
 * Time: 10:35
 */
include 'rsa.php';
$output = array();
$uinfo = @$_POST['uinfo'] ? $_POST['uinfo'] : 0;
$pinfo = @$_POST['pinfo'] ? $_POST['pinfo'] : 0;

$now_url = $_SERVER['DOCUMENT_ROOT'];
//使用自己的私钥进行解密
$encode = new rsa($now_url . '\public_key.txt', $now_url . '\private_key.txt');
$uinfo = $encode->private_decrypt($uinfo);
$pinfo = $encode->private_decrypt($pinfo);

//比对数据库中的用户信息
if ($uinfo == '123' && $pinfo == '123') {
    $output = array('judge' => '1', 'info' => 'user is leagal', 'code' => 200);
    exit(json_encode($output));
} else {
    $output = array('judge' => '2', 'info' => 'user is inleagal', 'code' => 201);
    exit(json_encode($output));
}


//$now_url=$_SERVER['HTTP_HOST'];

/*
$requesturl="http://localhost:63342/LoginSys/api.php?uid=10001";

//curl方式获取json数组
$file_contents = file_get_contents('http://localhost:63342/LoginSys/api.php?uid=10001');

echo $file_contents;
*/
/*
$url = 'http://localhost:63342/LoginSys/api.php?uid=10001';
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
curl_setopt ( $ch, CURLOPT_POST, 1 ); //启用POST提交
$file_contents = curl_exec ( $ch );
curl_close ( $ch );
echo $file_contents;
*/