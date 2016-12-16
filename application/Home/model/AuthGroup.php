<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/28
 * Time: 下午 05:45
 */

namespace app\home\model;


use think\Model;

class AuthGroup extends Model
{
//定义公共获取用户组方法
    function getGroupList(){
        $Auth_rule=db('Auth_rule');
        $grouplist=$this->select();
        $rulelist=$Auth_rule->select();
        $i=0;
        foreach ($grouplist as $group){
            for($t=0;$t<count($rulelist);$t++){   ///初始化选择项
                $obr[$t]=0;
            }
            $arrrule=explode(',', $group['rules']);
            foreach ($arrrule as $v=>$u){
                foreach ($rulelist as $a=>$b){
                    if($u==$b['id']){
                        $obr[$a]=1;
                        break;
                    }
                }
            }
            $group['rules']=$obr;
            unset($grouplist[$i]); //删掉原有的键值
            $grouplist[$i] = $group; //赋值
            $i++;
        }
        $arr['grouplist']=$grouplist;
        $arr['rulelist']=$rulelist;
        return $arr;
    }
}