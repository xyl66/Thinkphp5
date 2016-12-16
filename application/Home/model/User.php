<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/28
 * Time: 下午 05:42
 */

namespace app\home\model;


use think\Model;

class User extends Model
{
    //定义公共获取用户方法
    function getUserList(){
        $Auth_group=db('Auth_group');
        $join = [
            ['tp_auth_group_access w','a.admin_id=w.uid'],
            ['tp_auth_group c','w.group_id=c.id'],
        ];
        $userlist=$this->alias('a')->join($join)->select();
        $grouplist=$Auth_group->select();
        $arr['userlist']=$userlist;
        $arr['grouplist']=$grouplist;
        return $arr;
    }
}