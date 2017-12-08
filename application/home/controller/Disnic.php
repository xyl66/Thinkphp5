<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/22
 * Time: 上午 09:58
 */

namespace app\home\controller;
use app\home\model\PageDesign;
use think\Request;
use think\Db;
class Disnic extends \think\Controller
{
    public function index(){

    }
    //签到页面
    public function add()
    {
        $id = input('id');
        $this->assign('id', $id);
        $Is = input('Is');
        $this->assign('Is', $Is);
        $Pagedesign = new PageDesign();
        $c_old_url = cookie('c_old_url');
        $c_old_url = ($c_old_url ? str_replace('.html', '', url_jiemi($c_old_url)) : 'Item/lists');
        $request=request::instance();
        if ($request->isPost()) {
            if (!$id) {
                return json(array('status' => 0, 'msg' => ''));
            }

            $is_preview = input('is_preview', 0);
            $content = htmlspecialchars(input('content'));

            if (!$Is) {
                $data = array( 'id' => $id, 'content' => $content);
                $res = $Pagedesign->update($data);

                if ($res) {
                    return json(array('status' => 1, 'msg' =>  '添加商品详情'));
                }

                if (!$res) {
                    return json(array('status' => 0, 'msg' => ''));
                }
                else {
                    if ($is_preview) {
                        $link = preview_url(url('Disnic/add?id=' . $id . '&is_preview=1'));
                    }
                    else {
                        $link = url($c_old_url);
                    }

                    return json(array('status' => 1, 'link' => $link));
                }
            }
            else {
                $data = array('id' => $id);
                $design_id = $Pagedesign->where($data)->value('id');

                if ($design_id) {
                    $res = $Pagedesign->where('id = ' . $design_id)->update(['content'=>$content]);

                    if ($res) {
                        return json(array('status' => 1, 'msg' =>  '編輯商品详情'));
                    }
                }
                else {
                    $data['content'] = $content;
                    $res = $Pagedesign->insert($data);

                    if ($res) {
                        return json(array('status' => 1, 'msg' =>  '添加商品详情'));
                    }
                }

                if ($res === false) {
                    return json(array('status' => 0, 'msg' => ''));
                }
                else {
                    if ($is_preview) {
                        $link = preview_url(url('Disnic/add?id=' . $id . '&is_preview=1'));
                    }
                    else {
                        $link = url($c_old_url);
                    }

                    return json(array('status' => 1, 'link' => $link));
                }
            }
        }

        if ($Is) {
            $map = array('id' => $id);
            $content = $Pagedesign->where($map)->value('content');
            $content = xss_filter(htmlspecialchars_decode($content));
            $content = htmlspecialchars($content);
            $this->assign('content', $content);
        }
        return view();
    }

}