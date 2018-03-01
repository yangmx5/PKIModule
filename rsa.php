<?php
/*

生成公钥、私钥对，私钥加密的内容能通过公钥解密（反过来亦可以）

下载开源RSA密钥生成工具openssl（通常Linux系统都自带该程序），解压缩至独立的文件夹，进入其中的bin目录，执行以下命令：

openssl genrsa -out rsa_private_key.pem 1024 #生成原始 RSA私钥文件 rsa_private_key.pem
openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem #将原始 RSA私钥转换为 pkcs8格式
openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem #通过私钥生成对应 RSA公钥 rsa_public_key.pem

*/
$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c
-----END RSA PRIVATE KEY-----';
$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';
//file_put_contents('public_key.txt', $public_key);
//file_put_contents('private_key.txt', $private_key);

/**
 * rsa 非对称加解密
 */
class rsa
{
    private $public_key = ''; //公密钥
    private $private_key = ''; //私密钥
    private $public_key_resource = ''; //公密钥资源
    private $private_key_resource = ''; //私密钥资源

    /**
     * 架构函数
     * @param [string] $public_key_file  [公密钥文件地址]
     * @param [string] $private_key_file [私密钥文件地址]
     */
    public function __construct($public_key_file, $private_key_file)
    {
        try {
            if (!file_exists($public_key_file) || !file_exists($private_key_file)) {
                throw new Exception('key file no exists');
            }
            if (false == ($this->public_key = file_get_contents($public_key_file)) || false == ($this->private_key = file_get_contents($private_key_file))) {
                throw new Exception('read key file fail');
            }
            if (false == ($this->public_key_resource = $this->is_bad_public_key($this->public_key)) || false == ($this->private_key_resource = $this->is_bad_private_key($this->private_key))) {
                throw new Exception('public key or private key no usable');
            }

        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function is_bad_public_key($public_key)
    {
        return openssl_pkey_get_public($public_key);
    }

    private function is_bad_private_key($private_key)
    {
        return openssl_pkey_get_private($private_key);
    }

    /**
     * 生成一对公私密钥 成功返回 公私密钥数组 失败 返回 false
     */
    public function create_key()
    {
        $res = openssl_pkey_new();
        if ($res == false) return false;
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        return array('public_key' => $public_key["key"], 'private_key' => $private_key);
    }

    /**
     * 用私密钥加密
     */
    public function private_encrypt($input)
    {
        openssl_private_encrypt($input, $output, $this->private_key_resource);
        return base64_encode($output);
    }

    /**
     * 解密 私密钥加密后的密文
     */
    public function public_decrypt($input)
    {
        openssl_public_decrypt(base64_decode($input), $output, $this->public_key_resource);
        return $output;
    }

    /**
     * 用公密钥加密
     */
    public function public_encrypt($input)
    {
        openssl_public_encrypt($input, $output, $this->public_key_resource);
        return base64_encode($output);
    }

    /**
     * 解密 公密钥加密后的密文
     */
    public function private_decrypt($input)
    {
        openssl_private_decrypt(base64_decode($input), $output, $this->private_key_resource);
        return $output;
    }
}

/*
$now_url=$_SERVER['DOCUMENT_ROOT'];

$rsa = new rsa($now_url.'\public_key.txt',$now_url.'\private_key.txt');



$str = '哈哈哈哈哈';
$str = $rsa->public_encrypt($str); //用公密钥加密
echo $str,'</br>';
$str = $rsa->private_decrypt($str); //用私密钥解密
echo $str,'</br>';
//=============================================================
$str = $rsa->private_encrypt($str); //用丝密钥加密
echo $str,'</br>';
$str = $rsa->public_decrypt($str); //用公密钥解密
echo $str,'</br>';
*/