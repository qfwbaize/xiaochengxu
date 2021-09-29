<?php
declare (strict_types = 1);

namespace app\controller;

use app\common\controller\AdminController;
use app\model\Business;
use app\model\Company;
use app\model\Reward;
use app\model\TaskContent;
use app\model\TaskEvidence;
use app\model\TaskPeople;
use think\App;
use think\Request;

class MyTask extends AdminController
{
    use \app\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\model\Task();
    }

    /**
     * 显示未接得任务
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        list($page, $limit, $where) = $this->buildTableParames();
        $count = $this->model
            ->where('type',3)
            ->where($where)
            ->count();
        $list = $this->model
            ->where('type',3)
            ->where($where)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
        $card_id=$this->CardId();
        $nodelist=[];
        $content= new TaskContent();
        $task_people=new TaskPeople();
        $business=new Business();
        $company=new Company();
        foreach ($list as $value){
            $value['start_time'] = date('Y-m-d', $value['start_time']);
            $value['end_time'] = date('Y-m-d', $value['end_time']);
            $business_name = $business->where('card_id', $value['card_id'])->find();
            if (!empty($business_name)) {
                $value['name'] = $business_name['name'];
            }
            $company_name = $company->where('company_id', $value['company_id'])->find();
            if (!empty($company_name)) {
                $value['company_name'] = $company_name['company_name'];
            }
            $task_content = $content->where('task_id', $value['id'])->field('money')->find();
            if (!empty($task_content)) {
                $value['money'] = $task_content['money'];
            }
            $people=$task_people->where('task_id',$value['id'])->where('card_id',$card_id)->find();
            if(empty($people)){
               $nodelist[]=$value;

            }

        }
        $data = [
            'code'  => 200,
            'msg'   => '成功',
            'total' => $count,
            'data'  => $nodelist,
        ];
        return json($data);
    }
    /**
     * 显示已接得任务
     *
     * @return \think\Response
     */
    public function received(){
        $content= new TaskContent();
        $task_people=new TaskPeople();
        $business=new Business();
        $company=new Company();
        $card_id=$this->CardId();

        list($page, $limit, $where) = $this->buildTableParames();
        $count = $task_people
            ->where('card_id',$card_id)
            ->where($where)
            ->count();
        $people = $task_people
            ->distinct(true)
            ->where('card_id', $card_id)
            ->field('task_id')
            ->buildSql(true);

        $list = $this->model
            ->distinct(true)
            ->where("id IN {$people}")
            ->page($page, $limit)
            ->select();

        foreach ($list as $value){
            $business_name = $business->where('card_id', $value['card_id'])->find();
            if (!empty($business_name)) {
                $value['name'] = $business_name['name'];
            }
            $company_name = $company->where('company_id', $value['company_id'])->find();
            if (!empty($company_name)) {
                $value['company_name'] = $company_name['company_name'];
            }
            $task_content = $content->where('task_id', $value['id'])->field('money')->find();
            if (!empty($task_content)) {
                $value['money'] = $task_content['money'];
            }
            $value['start_time'] = date('Y-m-d', $value['start_time']);
            $value['end_time'] = date('Y-m-d', $value['end_time']);

        }
        $data = [
            'code'  => 200,
            'msg'   => '成功',
            'total' => $count,
            'data'  => $list,
        ];
        return json($data);


    }
    /**
     * 接取任务
     *
     * @return \think\Response
     */
    public function receive_task($id){
        $row=$this->model->find($id);
        empty($row) && $this->error('任务不存在');
        $card_id=$this->CardId();
        $company_id=$this->AdminId();
        $people=new TaskPeople();
        $people_name=$people->where('task_id',$id)->where('card_id',$card_id)->find();
        !empty($people_name) && $this->error('你已经领取任务了');
        $post = $this->request->post();
        $rule = [
            'status'=>'in:1'
        ];
        $this->validate($post, $rule);
        try {
            if($row['type']==4){
                $rule = [
                    'contract|合同'=>'require'
                ];
                $this->validate($post, $rule);
                $data=[
                    'task_id'=>$id,
                    'card_id'=>$card_id,
                    'status'=>$post['status'],
                    'company_id'=>$company_id,
                    'contract'=>$post['contract']];
            }else{
            $data=[
                'task_id'=>$id,
                'card_id'=>$card_id,
                'status'=>$post['status'],
                'company_id'=>$company_id];}
            $save = $people->save($data);
        } catch (\Exception $e) {
            $this->error('接取失败');
        }
        $save ? $this->success('接取成功') : $this->error('接取失败');
    }
    /**
     * 个人证据上传
     *
     * @return \think\Response
     */
    public function evidence(){
        $evidence= new TaskEvidence();
        $post = $this->request->post();
        $rule = [
            'task_id|任务id'=>'require',
            'evidence|证据图片'=>'require',
            'explain|说明'=>'require',
            ];
        $this->validate($post, $rule);
        try {
            $post['card_id']=$this->CardId();
            $save = $evidence->save($post);
        } catch (\Exception $e) {
            $this->error('保存失败:'.$e->getMessage());
        }
        $save ? $this->success('保存成功') : $this->error('保存失败');
    }
    /**
     * 提交验收
     *
     * @return \think\Response
     */
    public function edit_task(){
        $people= new TaskPeople();
        $post = $this->request->post();
        $rule = [
           'task_id'=>'require'
        ];
        $this->validate($post, $rule);
        try {
            $post['card_id']=$this->CardId();
            $post['status']=2;
            $save = $people->where('task_id',$post['task_id'])->where('card_id',$post['card_id'])->save($post);
        } catch (\Exception $e) {
            $this->error('失败:'.$e->getMessage());
        }
        $save ? $this->success('提交成功') : $this->error('提交失败');
    }
    /**
     * 查看自己得证据
     *
     * @return \think\Response
     */
    public function read_reward(){
        $reward= new Reward();
        $get = $this->request->get();
        $rule = [

            'task_id|任务id'=>'require',
        ];
        $this->validate($get, $rule);
        $card_id=$this->CardId();

        $row = $reward->where('task_id',$get['task_id'])->where('task_card_id',$card_id)->find();

        if (!empty($row)) {

            $data = ['code' => 200, 'msg' => '成功', 'data' => $row,];


        } else {

            $data = ['code' => 0, 'msg' => '没数据', 'data' => '',];


        }
        return json($data);
    }



}
