<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 09:58
 */

namespace app\api\controller\v1;
use app\home\model\Course;
use think\Request;
use think\Db;
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
header('Access-Control-Allow-Headers:Access-Control-Allow-Orgin,XMLHttpRequest,Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With');
class CourseSign
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
            if($sign['user_id']==''){
                return json(array('status'=>0,'msg'=>'工号不能为空'));
            }
            if($sign['user_name']==''){
                return json(array('status'=>0,'msg'=>'姓名不能为空'));
            }
            if($sign['user_department']==''){
                return json(array('status'=>0,'msg'=>'部门不能为空'));
            }
            $Course=new Course();
            $course= $Course->where(array('course_id'=>$sign['course_id']))->find();
            if($course==Null){
                return json(array('status'=>0,'msg'=>'不存在课程'));
            }
            if(time()<($course['course_time_start']-30*60)||time()>($course['course_time_end']+30*60)) {
                return json(array('status'=>0,'msg'=>'超出课程签到时间范围：课程前30分钟至课程后30内'));
            }
            $Sign=new \app\home\model\Sign();
            $signed=$Sign->where(array('course_id'=>$sign['course_id'],'user_id'=>$sign['user_id']))->find();
            if($signed==Null){
                $sign['creat_time']=time();
                $sign['sign_ip']=getIP();
                $Sign->data($sign)->save();
                return json(array('status'=>1,'msg'=>'签到成功'));
            }
            else{
                return json(array('status'=>0,'msg'=>'已签过，不能重复签到'));
            }
    }
}