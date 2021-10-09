<?php
declare (strict_types = 1);

namespace app\controller;

use app\common\controller\AdminController;
use think\App;
use think\facade\Filesystem;
use think\Request;

class Uploads extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);


    }
    /**
     * 员工上传证据
     *
     * @return \think\Response
     */
    public function staff_evidence_upload(){
        $card_id='staff/'.$this->CardId();
        $files = $this->request->file('file');

        if($files==NULL){
            $this->error('没上传文件');
        }

        foreach($files as $k=>$file){


            $temp=explode(".",$_FILES['file']['name'][$k]);

            $extension =end($temp);

            if(!in_array($extension,array("jpg","png",))){

                $this->error('不合法');
            }
            $saveName[] = Filesystem::disk('aliyun')->putFile("$card_id",$file,'uploads');

        }
        $data=[];
        foreach ($saveName as $v){
            $v=env('FILESYSTEM.URL').$v;
            $data[]=$v;
        }

        return json(['code'=>200,'msg'=>'成功','data'=>$data]);
    }
    /**
     * 员工签署合同
     *
     * @return \think\Response
     */
    public function staff_contract_upload(){
        $company_id='staffcontract/'.$this->AdminId();
        $files = $this->request->file('file');

        if($files==NULL){
            $this->error('没上传文件');
        }

        foreach($files as $k=>$file){


            $temp=explode(".",$_FILES['file']['name'][$k]);

            $extension =end($temp);

            if(!in_array($extension,array("pdf","jpg","png","docs","doc","docx"))){

                $this->error('不合法');
            }
            $saveName[] = Filesystem::disk('aliyun')->putFile("$company_id",$file,'uploads');

        }
        $data=[];
        foreach ($saveName as $v){
            $v=env('FILESYSTEM.URL').$v;
            $data[]=$v;
        }

        return json(['code'=>200,'msg'=>'成功','data'=>$data]);
    }
    /**
     * 机构上传证据
     *
     * @return \think\Response
     */
    public function mechanism_evidence_upload(){
        $company_id='mechanism/'.$this->AdminId();
        $files = $this->request->file('file');

        if($files==NULL){
            $this->error('没上传文件');
        }

        foreach($files as $k=>$file){


            $temp=explode(".",$_FILES['file']['name'][$k]);

            $extension =end($temp);

            if(!in_array($extension,array("jpg","png"))){

                $this->error('不合法');
            }
            $saveName[] = Filesystem::disk('aliyun')->putFile("$company_id",$file,'uploads');

        }
        $data=[];
        foreach ($saveName as $v){
            $v=env('FILESYSTEM.URL').$v;
            $data[]=$v;
        }

        return json(['code'=>200,'msg'=>'成功','data'=>$data]);
    }
    /**
     * 机构上传合同
     *
     * @return \think\Response
     */
    public function mechanism_contract_upload(){
        $company_id='contract/'.$this->AdminId();
        $files = $this->request->file('file');

        if($files==NULL){
            $this->error('没上传文件');
        }

        foreach($files as $k=>$file){


            $temp=explode(".",$_FILES['file']['name'][$k]);

            $extension =end($temp);

            if(!in_array($extension,array("pdf","jpg","png","docs","doc","docx"))){

                $this->error('不合法');
            }
            $saveName[] = Filesystem::disk('aliyun')->putFile("$company_id",$file,'uploads');

        }
        $data=[];
        foreach ($saveName as $v){
            $v=env('FILESYSTEM.URL').$v;
            $data[]=$v;
        }

        return json(['code'=>200,'msg'=>'成功','data'=>$data]);
    }
    /**
     * 个人实名认证
     *
     * @return \think\Response
     */
    public function authentication(){
        $company_id='userauthentication/'.$this->UserId();
        $files = $this->request->file('file');

        if($files==NULL){
            $this->error('没上传文件');
        }

        foreach($files as $k=>$file){


            $temp=explode(".",$_FILES['file']['name'][$k]);

            $extension =end($temp);

            if(!in_array($extension,array("jpg","png"))){

                $this->error('不合法');
            }
            $saveName[] = Filesystem::disk('aliyun')->putFile("$company_id",$file,'upload');

        }
        $data=[];
        foreach ($saveName as $v){
            $v=env('FILESYSTEM.URL').$v;
            $data[]=$v;
        }

        return json(['code'=>200,'msg'=>'成功','data'=>$data]);
    }

}
