<?php
namespace app\api\controller;
use think\Controller;
use think\File;
use think\Request;
class Image extends Controller
{
    public function upload()
    {
        $file = Request::instance()->file('file');
        $info = $file->move('upload');
        if($info&&$info->getPathname()){
            return show(1,'success','/public/'.$info->getPathname());
        }
        return show(0,'upload error');

    }
}