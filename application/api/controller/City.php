<?php
namespace app\api\controller;
use think\Controller;
class City extends Controller{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('city');
    }
    public function getCitysByParentId(){
        $id = input('post.id');
        if(!$id){
            $this->error('Id不存在');
        }
        //通过ID获取二级城市
        $citys = $this->obj->getNormalCitysByParentId($id);
        return show(1,'success',$citys);

    }

}