<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\TokenModel;
use Illuminate\Support\Str;
use App\Model\GoodsModel;
use DB;
use Illuminate\Support\Facades\Redis;
class IndexController extends Controller
{
    //接口测试 注册
    public function reg(Request $request)
    {
        $user_name = $request->post('user_name');
        $user_email = $request->post('user_email');
        $pass1 = $request->post('pass1');
        $pass2 = $request->post('pass2');

        //  todo 验证用户名 email 密码

        $pass = password_hash($pass1, PASSWORD_BCRYPT);

        $user_info = [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'password' => $pass,
            'reg_time' => time()
        ];

        $uid = UserModel::insertGetId($user_info);
        $response = [
            'error' => 0,
            'msg' => 'ok'
        ];
        return $response;
    }

    //接口测试 登录
    public function login(Request $request)
    {
        $user_name = $request->post('user_name');
        $pass = $request->post('pass');

        //验证登录信息
        $u = UserModel::where(['user_name' => $user_name])->first();
        if ($u) {
            //验证密码
            if (password_verify($pass, $u->password)) {
                //生成token
                $token = Str::random(32);

                $expire_seconds = 3600; //token的有效期
                $data = [
                    'token' => $token,
                    'uid' => $u->user_id,
                    'expire_at' => time() + $expire_seconds
                ];
                //入库
                $tid = TokenModel::insertGetId($data);

                $response = [
                    'error' => 0,
                    'msg' => 'ok',
                    'data' => [
                        'token' => $token,
                        'expire_in' => $expire_seconds
                    ]
                ];
            } else {
                $response = [
                    'error' => 500001,
                    'msg' => '密码错误'
                ];
            }
        } else {
            $response = [
                'error' => 400001,
                'msg' => '用户不存在'
            ];
        }
        return $response;
    }

    //个人中心
    public function center(Request $request)
    {
        $user_info = UserModel::find($t->uid);
        $response = [
            'error' => 0,
            'msg' => 'ok',
            'data' => [
                'user_info' => $user_info
            ]
        ];
        return $response;
    }

    public function goods(Request $request)
    {
        $goods_id=$request->get('id');
        $key='h:goods_info:'.$goods_id;

        //先判断缓存
        $goods_info=Redis::hgetAll($key);

        if(empty($goods_info))
        {
         $g=GoodsModel::select('goods_id','goods_sn','cat_id','goods_name')->find($goods_id);
        //缓存到redis中
        $goods_info=$g->toArray();
        Redis::hMset($key,$goods_info);
            echo '无缓存';
            echo '<pre>';print_r($goods_info);echo '</pre>';
        }else{
            echo '缓存';
            echo '<pre>';print_r($goods_info);echo '</pre>';
        }
        //增加访问次数
        Redis::hincrby($key,'view_count',1);
}
}
