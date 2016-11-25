<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/23
 * Time: 上午 09:08
 */
return [
    'auth_config'=>array(
    'AUTH_ON' => true, //认证开关
    'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
    'AUTH_GROUP' => 'think_auth_group', //用户组数据表名
    'AUTH_GROUP_ACCESS' => 'think_auth_group_access', //用户组明细表
    'AUTH_RULE' => 'think_auth_rule', //权限规则表
    'AUTH_USER' => 'tp_admin'//用户信息表
)
];