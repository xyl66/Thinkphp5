<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/30
 * Time: 下午 03:11
 */

namespace app\home\model;


use think\Model;

class AuthRule extends Model
{
//定义公共获取权限方法
    function getRuleList(){
        return $this->select();
    }
}