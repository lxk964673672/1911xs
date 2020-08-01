<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function test2(){
    $data=[
        'error'=>0,
        'msg'=>'ok'
    ];
        return $data;
    }
    //对称加密
    public function aes1(){
        $data="Hellow world";
        $methoh='AES-256-CBC';
        $key='1911api';
        $iv='aaaabbbbccccdddd';

        echo "原始数据: ".$data;echo '</br>';

        //计算密文
        $enc_data=openssl_encrypt($data,$methoh,$key,OPENSSL_RAW_DATA,$iv);

        //将密文发送
        echo "加密后的密文: ".$enc_data;echo '</br>';

    }
    public function dec1(Request $request){
        $key='1911api';
        $iv='aaaabbbbccccdddd';
        $method='AES-256-CBC';

        $enc_data=$request->post('data');
        //解密数据
        $dec_data=openssl_decrypt($enc_data,$method,$key,OPENSSL_RAW_DATA,$iv);
        var_dump($dec_data);

    }

    //解密
    public function dec(Request $request)
    {
        $method = 'AES-256-CBC';
        $key = '1911api';
        $iv = 'aaaabbbbccccxxxx';
        $option = OPENSSL_RAW_DATA;

        echo '<pre>';print_r($_POST);echo '</pre>';echo '</br>';

        $enc_data = base64_decode($_POST['data']);

        //解密数据
        $dec_data = openssl_decrypt($enc_data, $method, $key, $option, $iv);

        echo "解密数据: " . $dec_data;
    }

    public  function rsa1(){
        $data='长江长江我是黄河';

        $content=file_get_contents(storage_path('keys/pub.key'));
        $pub_key=openssl_get_publickey($content);
        openssl_public_encrypt($data,$enc_data,$pub_key);

        //发送加密数据 post vgv

    }
    //签名测试
    public function sign1(Request $request){

        $key='1911api'; //计算签名的key

        //接收数据
        $data=$request->get('data');
        $sign=$request->get('sign'); //接收到的签名

        //计算签名
        $sign_str=sha1($data . $key); //接受端计算的签名

        if($sign_str==$sign){
            echo '验签通过';
        }else{
            echo '验签失败';
        }


    }

}
