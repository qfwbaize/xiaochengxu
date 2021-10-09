<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Cache;
use think\facade\Db;

class users
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
        $token = Request()->header('token');


        $data=Cache::store('redis')->get("ONE_STAND:USER:login_token:$token");
        $authentication=Db::name('user_authentication')->where('users_id',$data['id'])
            ->find();
        if(empty($authentication)){
            $datas = ['code' => 0, 'msg' => '您还没有认证',];
            return  json($datas);
        }
        if(empty($authentication['status']==-1)){
            $datas = ['code' => 0, 'msg' => '您的认证失败请重新认证',];
            return  json($datas);
        }
        return $next($request);
    }
}
