<?php
declare (strict_types = 1);

namespace app\controller;

use app\common\controller\AdminController;
use think\App;
use think\Request;

class Userauthentication extends AdminController
{
    use \app\traits\Curd;
    protected $rule = [
        'cert_positive'=>'require',
        'cert_side'=>'require',
        'agree'=>'in:1',
        'name'=>'require',
        'cate_num'=>'require',
    ];
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\model\Userauthentication();
    }
    /**
     * 查看是否已经实名.
     *
     * @param int $company_id
     * @return \think\Response
     */
    public function read(){
        $user_id=$this->UserId();
        $row=$this->model->where('users_id',$user_id)->find();
        empty($row) && $this->error('没有认证过');

        if($row['status']=='-1'){
            $data = ['code' => -1, 'msg' => '审核失败', 'data' => $row,];
        }elseif($row['status']==0){
            $data = ['code' => 1, 'msg' => '审核中', 'data' =>$row ,];
        }else{
            $data = ['code' => 200, 'msg' => '审核成功', 'data' =>'' ,];
        }
        return json($data);
    }
    /**
     * 提交审核.
     *
     * @param int $company_id
     * @return \think\Response
     */
    public function create()
    {
        $post = $this->request->post();
        $rule = $this->rule;
        $this->validate($post, $rule);
        try {
            $post['users_id']=$this->UserId();
            $save = $this->model->save($post);
        } catch (\Exception $e) {
            $this->error('保存失败:'.$e->getMessage());
        }
        $save ? $this->success('保存成功') : $this->error('保存失败');
    }
}
