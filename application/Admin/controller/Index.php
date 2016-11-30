<?php
namespace app\Admin\controller;
use app\Home\model\AuthGroup;
use think\Request;
use think\Db;
class Index extends \think\Controller
{
    // 这是THinkphp下的自动运行的方法，所有继承这个控制器的子类
    // 首先运行此方法
    public function _initialize(){
        define('SID', is_login());
        if (!SID) {
            set_redirect_url($_SERVER['REQUEST_URI']);
            $this->redirect('Login/login');
        }
        $user = session('ke_user_auth');
        if($user['account']!='admin'){
        //权限认证
         $auth=new \Auth\Auth();
         $request = Request::instance();
         if(!$auth->check($request->module().'-'.$request->controller().'-'.$request->action(),SID)){// 第一个参数是规则名称,第二个参数是用户UID
             /* return array('status'=>'error','msg'=>'有权限！');*/
             $this->error('你没有权限');
         }
        }
    }
    //权限
    public function rule(){
        if(Request::instance()->isAjax()){
            $rulelist=getRuleList();
            return json($rulelist);
        }
        return view('rule');
    }
    //修改权限
    public function ruleUp(){
        if(Request::instance()->isAjax()){
            $rule=input('post.rule/a');
            if(empty($rule['name'])){
                return array('status'=>'erro','msg'=>'更新失败,权方法名不能为空');
            };
            if(empty($rule['title'])){
               return array('status'=>'erro','msg'=>'更新失败,权限名不能为空');
            }
            $Auth_rule=Db::name('Auth_rule');
            $result=$Auth_rule->update($rule);
            if($result===false){
                return array('status'=>'erro','msg'=>'更新失败');
            }
            return array('status'=>'success','msg'=>'更新成功');
        }
    }
    //启禁用权限
    public function ruleStatusUp(){
        $rule=input('rule/a');
        if($rule['status']==='false'){
            $rule['status']=0;
        }
        if($rule['status']==='true'){
            $rule['status']=1;
        }
        $Auth_rule=Db::name('Auth_rule');
        $result=$Auth_rule->update($rule);
        if($result===false){
            return array('status'=>'erro','msg'=>'状态修改失败');
        }
        return array('status'=>'success','msg'=>'更新成功');
    }
    //添加权限
    public function ruleAdd(){
        if(Request::instance()->isAjax()){
            $rule=input('rule/a');
            if(empty($rule['name'])){
                return array('status'=>'error','msg'=>'添加失败,规则不能为空！');
            };
            if(empty($rule['title'])){
                return array('status'=>'error','msg'=>'添加失败,规则名不能为空！');
            }
            $Auth_rule=Db::name('Auth_rule');
            $result=$Auth_rule->insert($rule);
            if($result){
                $rulelist=getRuleList();
                return array('status'=>'success','data'=>$rulelist);
            }
            return array('status'=>'erro','msg'=>'添加失败');

        }
    }

    //用户组
    public function group(){
        if(Request::instance()->isAjax()){
            $arr=getGroupList();
            return json($arr);
        }
        return view('group');
    }
    //修改用户组权限
    public function groupUp(){
        $Auth_group=db('Auth_group');
        $Auth_rule=db('Auth_rule');
        if(Request::instance()->isAjax()) {
            $group=input('group/a');//获取客户端传过来的参数
            $rulelist = $Auth_rule->select();//获取权限列表
            $newgroup['title']=$group['title'];
            $newgroup['id']=$group['id'];
            $str='';                            //权限字符串
            foreach ($group['rules'] as $u=>$v){//遍历获取权限字符串
                if($v=='1'||$v=='true'){
                    $str=$str.strval($rulelist[$u]['id']).',';
                }
            }
            $newgroup['rules']=$str;
           if($Auth_group->update($newgroup)) {
               return array('status'=>'success','msg'=>'更新成功！');
           }
            return array('status'=>'error','msg'=>'更新失败！');;
        }
    }
    //修改用户组状态
    public function groupstatusup(){
        $Auth_group=new AuthGroup();
        if(Request::instance()->isAjax()){
            $group=input('group/a');//获取客户端传过来的参数
            if($group['status']==='false'){
                $group['status']=0;
            }
            if($group['status']==='true'){
                $group['status']=1;
            }
            $Auth_group->allowField(['status'])->save($group,['id'=>$group['id']]);
            return array('status'=>'success','msg'=>'更新成功！');
        }

    }
    //添加用户组
    public function groupAdd(){
        $Auth_group=db('Auth_group');
        $Auth_rule=db('Auth_rule');
        if(Request::instance()->isAjax()) {
            $group=input('group/a');//获取客户端传过来的参数
            $rulelist = $Auth_rule->select();//获取权限列表
            $newgroup['title']=$group['title'];
            $str='';                            //权限字符串
            foreach ($group['rules'] as $u=>$v){//遍历获取权限字符串
                if($v=='1'||$v=='true'){
                    $str=$str.strval($rulelist[$u]['id']).',';
                }
            }
            $newgroup['rules']=$str;
            if($Auth_group->insert($newgroup)) {
                $arr=getGroupList();//添加成功刷新页面
                return array('status'=>'success','data'=>$arr);
            }
            return array('status'=>'error','msg'=>'添加失败！');
        }
    }


    //用户
    public function user(){
        if(Request::instance()->isAjax()){
            $arr=getUserList();
            return json($arr);
        }
        return view('user');
    }
    //更该用户
    public function userUp(){
        if(Request::instance()->isAjax()){
            $user=input('user/a');
            Db::startTrans();
            try{
                Db::name('Auth_group_access')->where(array('uid'=>$user['admin_id']))->update(array('group_id'=>$user['group_id']));
                Db::name('User')->where(array('admin_id'=>$user['admin_id']))->update(array('password'=>$user['password']));
                Db::commit();
                return array('status'=>'success','保存成功！');
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return array('status'=>'error','保存失败！');
            }
        }
    }
}
