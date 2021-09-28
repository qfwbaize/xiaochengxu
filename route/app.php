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
//图片上传功能
Route::group('apis', function () {
    Route::post('uploads/staff_evidence_upload', 'Uploads/staff_evidence_upload'); //员工证据上传
    Route::post('uploads/mechanism_evidence_upload', 'Uploads/mechanism_evidence_upload'); //机构证据上传
    Route::post('uploads/mechanism_contract_upload', 'Uploads/mechanism_contract_upload'); //机构证据上传
    Route::post('uploads/staff_contract_upload', 'Uploads/staff_contract_upload'); //个人签署合同


})->ext();
Route::group('apis', function () {

    Route::get('messages/index', 'Messages/index'); //查看消息

    Route::get('messages/read', 'Messages/read'); //阅读消息
    Route::delete('messages/del', 'Messages/delete'); //消息删除
    Route::post('companytask/add', 'CompanyTask/create'); //发布任务
    Route::get('companytask/release_index', 'CompanyTask/release_index'); //查看发出任务
    Route::get('companytask/accept_index', 'CompanyTask/accept_index'); //查看接受任务
    Route::get('companytask/read', 'CompanyTask/read'); //查看任务详情
    Route::get('companytask/company', 'CompanyTask/company'); //查看合作得机构
    Route::put('companytask/task_update', 'CompanyTask/task_update'); //修改任务状态
    Route::get('companytask/task_people', 'CompanyTask/task_people'); //查看正在工作得员工
    Route::get('companytask/task_people_evidence', 'CompanyTask/task_people_evidence'); //查看员工得证据
    Route::put('companytask/task_people_edit', 'CompanyTask/task_people_edit'); //对员工工作进行审批
    Route::get('companytask/evidence', 'CompanyTask/evidence'); //查看证据
    Route::delete('companytask/del', 'CompanyTask/delete'); //机构拒绝任务
    Route::post('companytask/reward', 'CompanyTask/reward'); //对员工进行奖励
    Route::get('companytask/read_company_reward', 'CompanyTask/read_company_reward'); //查看打款凭证
    Route::get('companytask/company_reward', 'CompanyTask/company_reward'); //查看所有凭证


    Route::get('mytask/missed', 'MyTask/index'); //查看我的未接任务
    Route::get('mytask/received', 'MyTask/received'); //查看我的未接任务
    Route::put('mytask/receive_task', 'MyTask/receive_task'); //员工接任务接口
    Route::post('mytask/evidence', 'MyTask/evidence'); //个人上传证据
    Route::get('mytask/read_reward', 'MyTask/read_reward'); //个人查看机构打款得凭证



})->ext();
    //->middleware(['priority', 'verification']);
