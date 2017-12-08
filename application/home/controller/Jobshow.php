<?php
namespace app\home\controller;

use app\home\model\Course;
use app\home\model\Sign;
use app\home\model\Job;
use think\Request;
use think\Db;

class Jobshow extends \think\Controller
{
    // 这是THinkphp下的自动运行的方法，所有继承这个控制器的子类
    // 首先运行此方法
    public function _initialize()
    {
        define('SID', is_login());
        if (!SID) {
            set_redirect_url($_SERVER['REQUEST_URI']);
            $this->redirect('Login/login');
        }
        //权限认证
        $auth = new \Auth\Auth();
        $request = Request::instance();
        if (!$auth->check($request->module() . '-' . $request->controller() . '-' . $request->action(), SID)) {// 第一个参数是规则名称,第二个参数是用户UID
            /* return array('status'=>'error','msg'=>'有权限！');*/
            $this->error('你没有权限');
        }
    }
    //首页
    public function index()
    {
        return view('index');
    }
    //获取课程二维码
    public function signQrcode()
    {
        $path = "./Upload/images/schooljob";//文件夹路径
        if (!file_exists($path)) {
            mkdir($path);
        }
        // 生成的文件名
        $fileName = $path . '/job.png';
        $filePath = '/Upload/images/schooljob/job.png';
        $filePath = str_replace('index.php/', '', $filePath);
        $t=rand(1,100);
        if (!file_exists($fileName)) {
            $url = $_SERVER['HTTP_ORIGIN'] . Url('/JobSign', ['#/job' => $t]);
            if (strpos($url, 'http') === false) {
                $url = 'http:/' . $url;
            }
            $qrCode = new \Endroid\QrCode\QrCode();
            $qrCode
                ->setText($url)
                ->setSize(300)
                ->setPadding(10)
                ->setErrorCorrection('high')
                ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                ->setLabel('scan the code')
                ->setLabelFontSize(16)
                ->setLogo(ROOT_PATH.'/public/static/images/code_logo.png')
                ->setLogoSize(150)
                ->setImageType($qrCode::IMAGE_TYPE_PNG);

            // now we can directly output the qrcode
            header('Content-Type: ' . $qrCode->getContentType());
            $qrCode->render($fileName);
            $q = $qrCode->getImagePath();
        }
        return array('status' => 'success', 'qrcode_path' => $filePath);
    }
    //导出签到列表
    public function export_file_excel()
    {
        $request = Request::instance();
        if ($request->isAjax()) {
            $Job = new Job();
            $result = $Job->getList();
            if (empty($result)) {
                return array('status' => 'error', 'msg' => '不存在数据');
            }
            return array('status' => 'success', 'url' => $request->url(true), 'data' => '1');
            /*$this->ajaxReturn(array('status' => 0, 'msg' =>$q));*/
        }
        exprot_job_file();
    }

    //显示申请列表
    public function showList(){
        if(Request::instance()->isAjax()){
            $Job=new Job();
            $rulelist=$Job->getList();
            return json($rulelist);
        }
        return view('showList');
    }
}
