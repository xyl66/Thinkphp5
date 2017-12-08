<?php
/**
 * Created by PhpStorm.
 * User: F3233253
 * Date: 2016/11/28
 * Time: 下午 04:45
 */

namespace app\home\model;

use think\Model;
class PageDesign extends Model
{
  function getList(){
    return $this->select();
}
}