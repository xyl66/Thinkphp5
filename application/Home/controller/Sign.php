<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 09:58
 */

namespace app\home\controller;
use app\home\model\Course;
use think\Request;
use think\Db;
class Sign extends \think\Controller
{
    public function index(){

    }
    //签到页面
    public function sign(){
        $course_sign_id=input('cid');
        $Course=new Course();
        $course=$Course->where(array('course_sign_id'=>$course_sign_id))->find();
        $request=request::instance();
        if($request->isPost()){
            $sign=input('sign/a');
            if($sign['user_id']==''){
                return array('status'=>'error','msg'=>'工号不能为空');
            }
            if($sign['user_name']==''){
                return array('status'=>'error','msg'=>'姓名不能为空');
            }
            if($sign['user_department']==''){
                return array('status'=>'error','msg'=>'部门不能为空');
            }
            $course= $Course->where(array('course_id'=>$sign['course_id']))->find();
            if($course==Null){
                return array('status'=>'error','msg'=>'不存在课程');
            }
            if(time()<($course['course_time_start']-30*60)||time()>($course['course_time_end']+30*60)) {
                return array('status'=>'error','msg'=>'超出课程签到时间范围：课程前30分钟至课程后30内');
            }
            $Sign=new \app\home\model\Sign();
            $signed=$Sign->where(array('course_id'=>$sign['course_id'],'user_id'=>$sign['user_id']))->find();
            if($signed==Null){
                $sign['creat_time']=time();
                $sign['sign_ip']=getIP();
                $Sign->data($sign)->save();
                return array('status'=>'success','msg'=>'签到成功');
            }
            else{
                return array('status'=>'error','msg'=>'已签过，不能重复签到');
            }
        }
        if(empty($course)){
            $this->error('不存在的课程');
        }
        $course_time['start']=date("Y-m-d H:i:s",$course['course_time_start']);
        $course_time['end']=date("Y-m-d H:i:s",$course['course_time_end']);
        $this->assign('course_speaker',$course['course_speaker']);
        $this->assign('course_id',$course['course_id']);
        $this->assign('course_name',$course['name']);
        $this->assign('course_time',$course_time);
        return view();
    }
}