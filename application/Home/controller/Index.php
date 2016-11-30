<?php
namespace app\Home\controller;
use app\Home\model\Course;
use app\Home\model\Sign;
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
        //权限认证
            $auth=new \Auth\Auth();
            $request = Request::instance();
            if(!$auth->check($request->module().'-'.$request->controller().'-'.$request->action(),SID)){// 第一个参数是规则名称,第二个参数是用户UID
               /* return array('status'=>'error','msg'=>'有权限！');*/
                $this->error('你没有权限');
            }
    }
    //首页
    public function index(){
        $Course=new Course;
        $course_list= array();
        if(Request::instance()->isAjax()){
            $course_time=input('post.course_time');;
            $course_time_start=$course_time-8*3600;
            $course_time_end=$course_time+16*3600;
            $map['course_time_start']=array(array('egt',$course_time_start),array('elt',$course_time_end));
            $course=$Course->where($map)->select();
            return json($course);
        }
        $this->assign('course_list',$course_list);
        return view('index');
    }
    //修改课程
    public function courseUp(){
        if(Request::instance()->isAjax()){
            $course=input('post.course/a');
            if(empty($course['name'])){
                return array('status'=>'erro','msg'=>'更新失败,课程名不能为空');
            };
            if(empty($course['course_time_start'])||empty($course['course_time_end'])){
               return array('status'=>'erro','msg'=>'更新失败,课程日期不能为空');
            }
            if($course['course_time_start']>$course['course_time_end']){
               return array('status'=>'erro','msg'=>'更新失败,课程日期范围错误');
            }
            $course['course_time_start']=strpos($course['course_time_start'],'-')!==false?strtotime($course['course_time_start']):$course['course_time_start'];
            $course['course_time_end']=strpos($course['course_time_end'],'-')!==false?strtotime($course['course_time_end']):$course['course_time_end'];
            $Course=new Course;
            $result=$Course->save($course,['course_id'=>intval($course['course_id'])]);
            if($result===false){
                return array('status'=>'erro','msg'=>'更新失败');
            }
            return array('status'=>'success','msg'=>'更新成功');
        }
    }
    //创建课程
    public function creatCourse(){
        if(Request::instance()->isAjax()){
            $course=input('course/a');
            if(empty($course['name'])){
                return array('status'=>'error','msg'=>'新增失败,课程名不能为空！');
            };
            if(empty($course['course_time_start'])||empty($course['course_time_end'])){
                return array('status'=>'error','msg'=>'新增失败,课程日期不能为空！');
            }
            if($course['course_time_start']>$course['course_time_end']){
                return array('status'=>'error','msg'=>'新增失败,课程日期范围错误！');
            }
            $course['course_time_start']=strtotime($course['course_time_start']);
            $course['course_time_end']=strtotime($course['course_time_end']);
            $course['creat_time']= time();
            $Course=new Course;
            if($course['name']!=''||$course['course_time_start']!=''){
                $Course->data($course);
                $Course->save();
                $cid=$Course->course_id;
                if($cid){
                    $sin_id=$cid.rand(100,999);
                    $Course->save(array('course_sign_id'=>$sin_id),array('course_id'=>$cid));
                    return array('status'=>'success','msg'=>'新增成功');
                }
            }
        }
        return view();
    }
    //获取课程二维码
    public function signQrcode(){
        $cid=input('courseid');
        $Course=new Course;
        $course=$Course->where(array('course_id'=>$cid))->find();
        $course_time=date('Y-m-d',$course['course_time_start']);
        $course_sign_id=$course['course_sign_id'];
        if(!$course_sign_id||empty($course_sign_id)){
            $course_sign_id=$cid.rand(100,999);
            $Course->save(array('course_sign_id'=>$course_sign_id),array('course_id'=>$cid));
        }
        $path = "./Upload/images/".$course_time;//文件夹路径
        if(!file_exists($path)){
            mkdir($path);
        }
        // 生成的文件名
        $fileName = $path.'/'.$course_sign_id.'.png';
        $filePath='/Upload/images/'.$course_time.'/'.$course_sign_id.'.png';
        $filePath=str_replace('index.php/','',$filePath);
        if(!file_exists($fileName)){
            $url=$_SERVER['HTTP_ORIGIN'].Url('Home/Sign/sign',['cid'=>$course_sign_id]);
            if (strpos($url, 'http')===false) {
                $url='http:/'.$url;
            }
            $qrCode = new \Endroid\QrCode\QrCode();
            $qrCode
                ->setText($url)
                ->setSize(300)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel('Scan the code')
                ->setLabelFontSize(16)
                ->setImageType($qrCode::IMAGE_TYPE_PNG)
            ;

            // now we can directly output the qrcode
            header('Content-Type: '.$qrCode->getContentType());
            $qrCode->render($fileName);
            $q=$qrCode->getImagePath();
        }
        return array('status'=>'success','qrcode_path'=>$filePath);
    }
    //获取课程地址
    public function getUrl(){
        $cid=input('courseid');
        $Course=new Course;
        $course=$Course->where(array('course_id'=>$cid))->find();
        $course_sign_id=$course['course_sign_id'];
        if(empty($course_sign_id)||!$course_sign_id){
            $course_sign_id=$cid.rand(100,999);
            $Course->save(array('course_sign_id'=>$course_sign_id),array('course_id'=>$cid));
        }
        $url=$_SERVER['HTTP_ORIGIN'].Url('Sign/sign',['cid'=>$course_sign_id]);
        if (strpos($url, 'http')===false) {
            $url='http:/'.$url;
        }
        return array('status'=>'success','url'=>$url);
    }
    public function export_file_excel(){
        $request = Request::instance();
        if($request->isAjax()){
            $course_id=input('post.courseid');
            $Sign=new Sign();
            $result=$Sign->where(array('course_id'=>$course_id))->find();
            if(empty($result)){
                return array('status'=>'error','msg'=>'不存在数据');
            }
            return array('status'=>'success','url'=>$request->url(true),'data'=>$course_id);
            /*$this->ajaxReturn(array('status' => 0, 'msg' =>$q));*/
        }
        $course_id=input('course_id');
        exprot_file($course_id);
    }
}
