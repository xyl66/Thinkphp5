<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 09:58
 */

namespace app\api\controller\v1;
use app\home\model\Course;
use app\home\model\Job;
use think\Request;
use think\Db;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');
class JobSign
{
    public function index(){

    }
    //获取课程信息
    public function getCourseInfo(){
        $course_sign_id=input('cid');
        $Course=new Course();
        $course=$Course->where(array('course_sign_id'=>$course_sign_id))->find();
        if(empty($course)){
            return json(array('status'=>0,'msg'=>'不存在的课程'));
        }
        $course_time['start']=date("Y-m-d H:i:s",$course['course_time_start']);
        $course_time['end']=date("Y-m-d H:i:s",$course['course_time_end']);
        $dataArr=array('course' => $course,'course_time'=>$course_time);
        return json(array('status'=>1,'msg'=>'成功','data'=>$dataArr));
    }
    //签到页面
    public function sign(){
            $sign=input('');
            if($sign['user_name']==''){
                return json(array('status'=>0,'msg'=>'姓名不能为空'));
            }
            if($sign['user_sex']==''){
                return json(array('status'=>0,'msg'=>'性别不能为空'));
            }
            if($sign['user_tel']==''){
                return json(array('status'=>0,'msg'=>'电话不能为空'));
            }  
            if($sign['user_school']==''){
                return json(array('status'=>0,'msg'=>'学校不能为空'));
            }
            if($sign['user_special']==''){
                return json(array('status'=>0,'msg'=>'专业不能为空'));
            }
            if($sign['user_classes']==''){
              return json(array('status'=>0,'msg'=>'班级不能为空'));
            }
            
            $Job=new Job();
            $job= $Job->where(array('user_name'=>$sign['user_name'],'user_tel'=>$sign['user_tel']))->find();
            if($job==Null){
              $sign['sign_ip']=getIP();
              $Job->data($sign)->save();
              return json(array('status'=>1,'msg'=>'申请成功'));
            }
            else{
              if($job['user_school']==$sign['user_school']&&$job['user_sex']==$sign['user_sex']&&$job['user_special']==$sign['user_special']&&$job['user_classes']==$sign['user_classes']){
                return json(array('status'=>0,'msg'=>'已申请，不能重复申请'));
              }
              else{
                $job['user_school']=$sign['user_school'];
                $job['user_special']=$sign['user_special'];
                $job['user_sex']=$sign['user_sex'];
                $job['user_classes']=$sign['user_classes'];
                $result=$Job->where('id',$job['id'])->update(['user_school'=>$job['user_school'],'user_special'=>$job['user_special'],'user_sex'=>$job['user_sex'],'user_classes'=>$job['user_classes']]);
                if($result===false){
                    return json(array('status'=>'erro','msg'=>'更新失败'));
                }
                return json(array('status'=>'success','msg'=>'更新成功'));
              }
            }
    }
}