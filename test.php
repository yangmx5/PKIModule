<?php
/**
 * Created by PhpStorm.
 * User: this
 * Date: 2017/12/25
 * Time: 17:24
 */
$now_url = $_SERVER['HTTP_HOST'];
$static_url = $_SERVER['DOCUMENT_ROOT'];

$file = $_SERVER['DOCUMENT_ROOT'] . "\public_key.txt";

echo $file . '</br>';

//$file_contents = file_get_contents($now_url.'\LoginSys\api.php?uid=10001');
$file_contents = file_get_contents($file);
/*
$url = $now_url.'\LoginSys\api.php?uid=10001';
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
curl_setopt ( $ch, CURLOPT_POST, 1 ); //启用POST提交
$file_contents = curl_exec ( $ch );
curl_close ( $ch );
*/
echo $file_contents . '</br>';

echo file_exists($file) . '</br>';

$arr = array();
//$file_contents = file_get_contents('http://www.baidu.com');

$file_contents = curl_get('http://' . $now_url . '/api.php?uid=10001');
print_r($file_contents ? $file_contents : -1);

echo '</br>';
if ('1' == '1') {
    echo 'yes';
} else {
    echo 'no';
}
function curl_get($url)
{

    $testurl = $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testurl);
    //参数为1表示传输数据，为0表示直接输出显示。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //参数为0表示不带头文件，为1表示带头文件
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
    //print_r($output);
}

/*
 * url:访问路径
 * array:要传递的数组
 * */
function curl_post($url, $array)
{

    $curl = curl_init();
    //设置提交的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    $post_data = $array;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //获得数据并返回
    return $data;
    //print_r($data);
}
