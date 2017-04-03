<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
   }

    public function map(){
       //return \Map::getLntLat('中山市人民医院');
    }

    public function welcome(){

           return 0;

    }
}
