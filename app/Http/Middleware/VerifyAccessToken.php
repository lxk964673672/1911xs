<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //验证是否有token
        $token = $request->get('token');
        //判断token是否在黑名单中,如果在则提示错误
        $key = 's:token:blacklist';
        Redis::sismember($key, $token);

        if (empty($token)) {
            $response = [
                'error' => '40003',
                'msg' => '未授权'
            ];
            return $response;
        }
        //验证token是否有效
        $t = TokenModel::where(['token' => $token])->first();
        //未找到token信息
        if (empty($t)) {
            $response = [
                'error' => '40003',
                'msg' => 'token无效'
            ];
            return $response;
        }

        return $next($request);
    }
}
