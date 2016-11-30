<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 10:55
 */

namespace app\Home\controller;
use app\Home\model\AuthGroup;
use app\Home\model\AuthGroupAccess;
use app\Home\model\User;
use think\Db;
use think\Request;
class Login extends \think\Controller
{
    //首页
    public function index(){
    }
    //登陆
    public function login(){
        if(Request::instance()->isAjax()){
            $user=input('user/a');
            if($user['account']==''){
                return array('status'=>'error','msg'=>'用户名不能为空！');
            }
            if($user['pwd']==''){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            $User=new User();
            $admin=$User->where(array('account'=>$user['account']))->find();
            if($admin==Null){
                return array('status'=>'error','msg'=>'用户名不存在！');
            }
            if($user['pwd']!=$admin['password']){
                return array('status'=>'error','msg'=>'密码错误！');
            }
            $time = $this->request->time();
            $auth = array('aid' => $admin['admin_id'], 'last_time' => $time,'account'=>$admin['account']);
            session('ke_user_auth', $auth);
            return array('status' => 1, 'url' => Url('Home/Index/index'));
        }
        if (is_login()) {
            $rurl=get_redirect_url();
            if ($rurl&&$rurl!='/') {
                redirect($rurl);
            }
            else {
                $this->redirect('Index/index');
            }
        }
        return view();
    }
    //登出
    public  function logout(){
        if(is_login()){
            session('ke_user_auth',null);
            $this->redirect('login');
        }
        else{
            $this->redirect('login');
        }
    }
    //新建课程管理员账号
    public function creatAccount(){
        //权限认证
        define('SID', is_login());
        $auth=new \Auth\Auth();
        $request = Request::instance();
        if(!$auth->check($request->module().'-'.$request->controller().'-'.$request->action(),SID)){// 第一个参数是规则名称,第二个参数是用户UID
            /* return array('status'=>'error','msg'=>'有权限！');*/
            $this->error('你没有权限');
        }
        if(Request::instance()->isAjax()){
            $User=new User();
            $Auth_group_access=new AuthGroupAccess();
            $aid=is_admin();
            $isad=$User->where(array('admin_id'=>$aid))->find();
            if($isad==Null||$isad['account']!='admin'){
                return array('status'=>'error','msg'=>'您没有权限！');
            }
            $user=input('user/a');
            if(empty($user)){
                return array('status'=>'error','msg'=>'用户名密码不能为空！');
            }
            if($user['account']==''){
                return array('status'=>'error','msg'=>'用户名不能为空！');
            }
            if($user['password']==''){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            if($user['group']==''||$user['group']=='0'){
                return array('status'=>'error','msg'=>'用户组不能为空！');
            }
            $account=$User->where(array('account'=>$user['account']))->find();
            if($account){
                return array('status'=>'error','msg'=>'用户名已存在！');
            }
            $user['creat_time']=time();
            $data['group_id']=$user['group'];
            unset($user['group']);
            $User->data($user);
            $User->save();
            $uid=$User->admin_id;
            if($uid){
                $data['uid']=$uid;
                if($Auth_group_access->data($data)->save()){
               return array('status'=>'success','msg'=>'创建成功');
                }
            }
        }
        $Admin_Group=new AuthGroup();
        $group_list=$Admin_Group->select();
        return view('creatAccount',['grouplist'=>$group_list]);
    }
    //更改密码
    public function updateAccount(){
        if(Request::instance()->isAjax()){
            $User=new User();
            $aid=is_admin();
            $acount=$User->where(array('admin_id'=>$aid))->find();
            if($acount==Null){
                return array('status'=>'error','msg'=>'用户不存在！');
            }
            $user=input('user/a');
            if(empty($user)){
                return array('status'=>'error','msg'=>'密码不能为空！');
            }
            if($user['opassword']==''){
                return array('status'=>'error','msg'=>'原密码不能为空！');
            }
            if($user['opassword']!=$acount['password']){
                return array('status'=>'error','msg'=>'原密码错误！');
            }
            if($user['npassword1']==''){
                return array('status'=>'error','msg'=>'新密码不能为空！');
            }
            if($user['npassword1']!=$user['npassword2']){
                return array('status'=>'error','msg'=>'新密码不一致！');
            }
            $data['password']=$user['npassword1'];
            $uid=$User->save($data,array('admin_id'=>$aid));
            if($uid){
                return array('status'=>'success','msg'=>'修改成功！');
            }
            return array('status'=>'error','msg'=>'修改失败！');
        }
        return view();
    }
}