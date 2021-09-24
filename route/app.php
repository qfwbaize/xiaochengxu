<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use think\middleware;
use think\middleware\Throttle;
/*
     * verification 加密验证
     * priority 登陆验证 token验证
     */
//问题发布接口
Route::group('apis', function () {



})->ext();
Route::group('apis', function () {

    Route::get('messages/index', 'Messages/index'); //查看消息

    Route::get('messages/read', 'Messages/read'); //阅读消息
    Route::delete('messages/del', 'Messages/delete'); //角色删除
    Route::post('companytask/add', 'CompanyTask/create'); //发布任务
    Route::get('companytask/release_index', 'CompanyTask/release_index'); //查看发出任务
    Route::get('companytask/accept_index', 'CompanyTask/accept_index'); //查看接受任务
    Route::put('companytask/task_update', 'CompanyTask/task_update'); //修改任务状态


    

})->ext();
    //->middleware(['priority', 'verification']);
