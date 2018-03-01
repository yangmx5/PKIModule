<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Minimal and Clean Sign up / Login and Forgot Form by FreeHTML5.co</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Free HTML5 Template by FreeHTML5.co"/>
    <meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive"/>


    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="og:description" content=""/>
    <meta name="twitter:title" content=""/>
    <meta name="twitter:image" content=""/>
    <meta name="twitter:url" content=""/>
    <meta name="twitter:card" content=""/>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="favicon.ico">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/style.css">


    <!-- Modernizr JS -->
    <script src="js/modernizr-2.6.2.min.js"></script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
<body class="style-2">

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="menu">

            </ul>
        </div>
    </div>
    <div class="row">
        <br class="col-md-4">


        <!-- Start Sign In Form -->
        <form class="fh5co-form animate-box" data-animate-effect="fadeInLeft">
            <h2>Login Result</h2>
            <?php
            /**
             * Created by PhpStorm.
             * User: this
             * Date: 2017/12/24
             * Time: 10:49
             */
            include 'rsa.php';
            $username = $_POST["username"];
            $pswd = $_POST["password"];
            if (isset($_POST["isother"])) {
                $isother = $_POST["isother"];
            } else {
                $isother = "off";
            }
            $publicKey = "ThisIsFromCAPublicKey";

            if ($isother == "on") {
                $now_url = $_SERVER['HTTP_HOST'];
                $static_url = $_SERVER['DOCUMENT_ROOT'];
                $file_contents = file_get_contents('http://' . $now_url . '/api.php?uid=10001');

                //获取返回的证书中的公钥和签名
                $getCite = json_decode($file_contents);

                print_r($getCite ? $getCite : 1);

                $tarpublickey = $getCite->tarPublicKey;
                $cite = $getCite->cite;

                //验证证书签名的合法性
                $judge = new rsa($static_url . '\public_key.txt', $static_url . '\private_key.txt');
                $cite = $judge->public_decrypt($cite);

                //使用目标域公钥加密用户数据
                $secret = new rsa($static_url . '\public_key.txt', $static_url . '\private_key.txt');
                $users = $secret->public_encrypt($username);
                $pswds = $secret->public_encrypt($pswd);

                $arr = array('uinfo' => $users, 'pinfo' => $pswds);
				
				$other_url = '192.168.142.133'

                $ask_result = curl_post('http://' . $now_url . '/forClient.php', $arr);
                echo $ask_result ? $ask_result : 1;

                $ask_result = json_decode($ask_result);

                if ($ask_result->judge == "1") {
                    echo "login success";
                } else {
                    echo "login fail";
                }

            } else {
                echo "本域用户认证</br>";
                if($username=='123' && $pswds='123'){
                    echo 'login success';
                }else{
                    echo 'login fail';
                }
            }
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


            /*        echo $username;?></br>
                    <?php echo $pswd; ?></br>
                    <?php echo $isother;?></br>
    */ ?>
        </form>
        <!-- END Sign In Form -->

    </div>
</div>
<div class="row" style="padding-top: 60px; clear: both;">

</div>
</div>

<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js"></script>
<!-- Placeholder -->
<script src="js/jquery.placeholder.min.js"></script>
<!-- Waypoints -->
<script src="js/jquery.waypoints.min.js"></script>
<!-- Main JS -->
<script src="js/main.js"></script>

</body>
</html>




