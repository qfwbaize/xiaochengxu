<?php
declare (strict_types=1);

namespace app\controller;

use app\common\controller\AdminController;
use app\model\Company;
use app\model\TaskContent;
use app\model\TaskPeople;
use app\model\TaskReceive;
use think\App;
use think\Request;

class CompanyTask extends AdminController
{
    use \app\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\model\Task();
    }

    /**
     * 查看发布的任务
     *
     * @return \think\Response
     */
    public function release_index()
    {
        //
        list($page, $limit, $where) = $this->buildTableParames();
        $company_id = $this->AdminId();
        $count = $this->model
            ->where($where)
            ->where('company_id', $company_id)
            ->count();
        $list = $this->model
            ->where($where)
            ->where('company_id', $company_id)
            ->page($page, $limit)
            ->order($this->sort)
            ->select();
        $task_id = $this->model
            ->distinct(true)
            ->where('company_id', $company_id)
            ->field('id')
            ->buildSql(true);
        $pid = $this->model
            ->distinct(true)
            ->where("pid IN {$task_id}")
            ->select();
        $newNodeList = [];
        $company = new Company();
        foreach ($list as $vo) {

            if ($vo['pid'] == 0) {
                $children = [];
                foreach ($pid as $v) {
                    if ($v['pid'] == $vo['id']) {
                        $v['start_time'] = date('Y-m-d', $v['start_time']);
                        $v['end_time'] = date('Y-m-d', $v['end_time']);
                        $children[] = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;

                $vo['start_time'] = date('Y-m-d', $vo['start_time']);
                $vo['end_time'] = date('Y-m-d', $vo['end_time']);
            }
        }

        $data = [
            'code' => 200,
            'msg' => '成功',
            'total' => $count,
            'data' => $newNodeList,
        ];
        return json($data);

    }
   /**
 * 查看接受的任务
 *
 * @return \think\Response
 */    public function accept_index()
    {
        $revice = new TaskReceive();
        $company_id = $this->AdminId();
        $task_id = $revice
            ->distinct(true)
            ->where('company_task_id', $company_id)
            ->field('task_id')
            ->buildSql(true);
        $pid = $this->model
            ->distinct(true)
            ->where("pid IN {$task_id}")
            ->select();
        list($page, $limit, $where) = $this->buildTableParames();
        $count = $this->model
            ->where($where)
            ->where("id IN {$task_id}")
            ->count();
        $list = $this->model
            ->distinct(true)
            ->where("id IN {$task_id}")
            ->page($page, $limit)
            ->order($this->sort)
            ->where($where)
            ->select();
        $newNodeList=[];
        foreach ($list as $vo) {

            if ($vo['pid'] == 0) {
                $children = [];
                foreach ($pid as $v) {
                    if ($v['pid'] == $vo['id']) {
                        $v['start_time'] = date('Y-m-d', $v['start_time']);
                        $v['end_time'] = date('Y-m-d', $v['end_time']);
                        $children[] = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;

                $vo['start_time'] = date('Y-m-d', $vo['start_time']);
                $vo['end_time'] = date('Y-m-d', $vo['end_time']);
            }
        }
        $data = [
            'code' => 200,
            'msg' => '成功',
            'total' => $count,
            'data' => $newNodeList,
        ];
        return json($data);
    }

    /**
     * 机构任务发布.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        $post = $this->request->post();
        $rule = [
            'pid' => 'require',
            'type' => 'require',
            'pattern' => 'require',
            'title' => 'require',
            'company_task_id' => 'require',
            'content' => 'require',
            'num' => 'require',
            'pro_id' => 'require',
            'contract' => 'require',
            'money' => 'require',
            'start_time' => 'require',
            'end_time' => 'require',
        ];
        $this->validate($post, $rule);
        try {
            $content = new TaskContent();
            $post['company_id'] = $this->AdminId();
            $task = [
                'pid' => $post['pid'],
                'type' => $post['type'],
                'pattern' => $post['pattern'],
                'company_id' => $post['company_id'],
                'title' => $post['title'],
                'status' => 0,
                'start_time' => strtotime($post['start_time']),
                'end_time' => strtotime($post['end_time']),
            ];

            $save = $this->model->save($task);
            $id = $this->model->id;
            $taskcontent = [
                'task_id' => $id,
                'title' => $post['title'],
                'content' => $post['content'],
                'num' => $post['num'],
                'pro_id' => $post['pro_id'],
                'contract' => $post['contract'],
                'money' => $post['money']
            ];
            $content->save($taskcontent);
            switch ($post['type']) {
                case "1":
                    $receive = new TaskReceive();
                    $taskrecive = [
                        'company_task_id' => $post['company_task_id'],
                        'task_id' => $id,

                    ];
                    $receive->save($taskrecive);
                    break;
                case "2":
                    $people = new TaskPeople();
                    $card_id = explode(',', $post['card_id']);
                    $saveAll = [];
                    foreach ($card_id as $v) {
                        $saveAll[] = [
                            'task_id' => $id,
                            'card_id' => $v,
                            'status' => 1,
                            'company_id' => $this->AdminId()
                        ];

                    }
                    $people->saveAll($saveAll);


                    break;
            }


        } catch (\Exception $e) {
            $this->error('保存失败:' . $e->getMessage());
        }
        $save ? $this->success('保存成功') : $this->error('保存失败');
    }

}
