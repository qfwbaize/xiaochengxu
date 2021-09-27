<?php
declare (strict_types = 1);

namespace app\controller;

use app\common\controller\AdminController;
use app\model\Business;
use app\model\Company;
use app\model\TaskContent;
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
            if($value['type']==1){
                $value['is_show']='false';
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
            if($value['type']==1){
                $value['is_show']='false';
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

}
