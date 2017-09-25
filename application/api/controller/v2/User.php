<?php
namespace app\api\controller\v2;

use app\api\model\User as UserModel;

class User
{
    // 获取用户信息
    public function read($id = 0)
    {
        $user = UserModel::get($id, 'sign1');
        if ($user) {
            return json($user);
        } else {
            // 抛出HTTP异常 并发送404状态码
            abort(404);
        }
    }

}