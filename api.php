<?php
include 'rsa.php';
$output = array();
$uid = @$_GET['uid'] ? $_GET['uid'] : 0;
//检查用户
if ($uid == -1) {
    $output = array('tarPublicKey' => 'erro', 'cite' => 'erro');
    exit(json_encode($output));
}

$now_url = $_SERVER['DOCUMENT_ROOT'];

//使用CA的私钥对证书签名进行加密
$CAPrivateKey = new rsa($now_url . '\public_key.txt', $now_url . '\private_key.txt');
$cite = "ThiIsFromCA'sCite";
$cite = $CAPrivateKey->private_encrypt($cite);
$tarPublicKey = file_get_contents($now_url . '\public_key.txt');

//假设 $mysql 是数据库
$mysql = array(
    0 => array(
        'uid' => 0,
        'ip' => "192.10.10.1",
        'tarPublicKey' => $tarPublicKey,
        'cite' => $cite
    ),
    10001 => array(
        'uid' => 10001,
        'ip' => "192.10.10.1",
        'tarPublicKey' => $tarPublicKey,
        'cite' => $cite
    ),
    10002 => array(
        'uid' => 10002,
        'ip' => "192.10.10.2",
        'tarPublicKey' => 'ThisIsTar2PublicKey',
        'cite' => $cite
    ),
    10003 => array(
        'uid' => 10003,
        'ip' => "192.10.10.3",
        'tarPublicKey' => 'ThisIsTar3PublicKey',
        'cite' => $cite
    ),
);

$uidArr = array(0, 10001, 10002, 10003);
if (!in_array($uid, $uidArr, false)) {
    $output = array('data' => NULL, 'info' => 'The user does not exist!', 'code' => -402);
    exit(json_encode($output));
}
//查询数据库
$userInfo = $mysql[$uid];

//输出数据
$output = $userInfo;
exit(json_encode($output));