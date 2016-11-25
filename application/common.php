<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//判断是否登陆
function is_login()
{
    $user = session('ke_user_auth');

    if (empty($user)) {
        return 0;
    }
    else {
        return $user['aid'];
    }
}
//获取登陆uid
function is_admin()
{
    $user = session('ke_user_auth');

    if (empty($user)) {
        return 0;
    }
    else {
        return $user['aid'];
    }
}
function set_redirect_url($url)
{
    cookie('ke_redirect_url', $url);
}

function get_redirect_url()
{
    return cookie('ke_redirect_url');
}
function exprot_file($course_id)
{
    $Sign=db('Sign');
    $Course=db('Course');
    $course=$Course->where(array('course_id'=>$course_id))->find();
    $course_time=date('Y-m-d',$course['course_time_start']);
    $title='课程签到表_'.$course_time.'_'.$course['name'];
    $total = $Sign->where(array('course_id'=>$course_id))->count();
    require(EXTEND_PATH .'Excel\PHPExcel.php');
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->getProperties()->setCreator('courseSign')->setLastModifiedBy('courseSign')->setTitle($title)->setSubject($title)->setDescription($title)->setKeywords('课程,签到,列表')->setCategory($title);
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet(0)->setTitle($title);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置水平居中
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet(0)->setCellValue('A1', '姓名');
    $objPHPExcel->getActiveSheet(0)->setCellValue('B1', '工号');
    $objPHPExcel->getActiveSheet(0)->setCellValue('C1', '部门');
    $objPHPExcel->getActiveSheet(0)->setCellValue('D1', '签到地点');
    $objPHPExcel->getActiveSheet(0)->setCellValue('E1', '签到时间');
    $per_time = 100;
    $times = ceil($total / $per_time);
    $i = 2;
    $where = array(
        'course_id' => $course_id,
    );
    for ($j = 0; $j < $times; $j++) {
        $arr = $Sign->where($where)->limit($j * $per_time, $per_time)->select();
        foreach ($arr as $key => $value) {
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('A' . $i, $value['user_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . $i, $value['user_id']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . $i, $value['user_department']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . $i, $value['sign_place'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . $i, date('Y-m-d H:i:s',$value['creat_time']));
            $i++;
        }
    }

    $filename = iconv('UTF-8', 'GB2312', $title . '.xls');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename=' . $filename);
    header('Cache-Control: max-age=0');
    ob_clean();
    flush();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save('php://output');
}
//获取客户端ip
function getIP(){
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow IP";
    return $ip;
}

function getStatus($t){
    return $t?'是':'否';
}
//定义公共获取用户组方法
function getGroupList(){
    $Auth_group=db('Auth_group');
    $Auth_rule=db('Auth_rule');
    $grouplist=$Auth_group->select();
    $rulelist=$Auth_rule->select();
    $i=0;
    foreach ($grouplist as $group){
        for($t=0;$t<count($rulelist);$t++){   ///初始化选择项
            $obr[$t]=0;
        }
        $arrrule=explode(',', $group['rules']);
        foreach ($arrrule as $v=>$u){
            foreach ($rulelist as $a=>$b){
                if($u==$b['id']){
                    $obr[$a]=1;
                    break;
                }
            }
        }
        $group['rules']=$obr;
        unset($grouplist[$i]); //删掉原有的键值
        $grouplist[$i] = $group; //赋值
        $i++;
    }
    $arr['grouplist']=$grouplist;
    $arr['rulelist']=$rulelist;
    return $arr;
}
//定义公共获取权限方法
function getRuleList(){
    $Auth_rule=db('Auth_rule');
    return $Auth_rule->select();
}
//定义公共获取用户方法
function getUserList(){
    $Auth_group=db('Auth_group');
    $User=db('User');
    $join = [
        ['tp_auth_group_access w','a.admin_id=w.uid'],
        ['tp_auth_group c','w.group_id=c.id'],
    ];
    $userlist=$User->alias('a')->join($join)->select();
    $grouplist=$Auth_group->select();
    $arr['userlist']=$userlist;
    $arr['grouplist']=$grouplist;
    return $arr;
}
