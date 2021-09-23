<?php
declare (strict_types=1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;
/**
 * @mixin \think\Model
 */
class Auth extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $name = "company_auth";

    /**
     * 根据角色ID获取授权菜单
     * @param $authId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAuthorizeNodeListByAdminId($authId)
    { $systemNode = new Menu();
       if($authId!=0){
        $checkNodeList = (new AuthNode())
            ->where('auth_id', $authId)
            ->field('menu_id')
            ->buildSql(true);


        $nodelList = $systemNode
            ->where('status', '>', 0)
            ->field('id,pid,name,status,path,icon')
            ->where("id IN {$checkNodeList}")
            ->select()
            ->toArray();}else{
           $nodelList = $systemNode
               ->where('status', '>', 0)
               ->field('id,pid,name,status,path,icon')

               ->select()
               ->toArray();
       }

        $newNodeList = [];
        foreach ($nodelList as $vo) {
            if ($vo['pid'] == 0) {
                $children = [];
                foreach ($nodelList as $v) {
                    if ($v['status'] > 0 && $v['pid'] == $vo['id']) {
                        $v['name'] = "{$v['name']}";
                        $children[] = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;
            }
        }

        return $newNodeList;

    }


}
