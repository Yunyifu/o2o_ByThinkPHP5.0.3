<?php
namespace app\api\controller;
use think\Controller;
class Category extends Controller{
    private $obj;
    public function _initialize()
    {
        $this->obj = model('category');
    }
    public function getCategoryByParentId(){
        $id = input('post.id',0);
        if(!$id){
            $this->error('Id不存在');
        }
        //通过ID获取二级分类
        $categorys = $this->obj->getNormalCategoryByParentId($id);
        return show(1,'success',$categorys);

    }

}