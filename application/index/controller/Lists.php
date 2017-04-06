<?php
namespace app\index\controller;

use think\Controller;

class Lists extends Base
{

    public function index()
    {
        $firstCatIds = [];
        //获取一级栏目
        $categorys = model('Category')->getNormalCategoryByParentId();
        foreach($categorys as $category){
            $firstCatIds[] = $category->id;
        }
        $id = input('id',0,'intval');
        if(in_array($id,$firstCatIds)){
            //一级分类
            $categoryParentId = 0;
        }elseif($id){
            //二级分类
            //获取二级分类
            $category = model('Category')->get($id);
            if(!$category||$category->status !=1){
                $this->error('商品ID数据不合法');
            }
            $categoryParentId = $category->parent_id;
        }else{
            //id是0
            $categoryParentId = 0;
        }
        //获取父类下所有的子分类
        $sedcategorys = [];
        $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);

        return $this->fetch('',[
            'categorys'=>$categorys,
            'sedcategorys'=>$sedcategorys,
            'id'=>$id,
            'categoryParentId'=>$categoryParentId

        ]);
    }
}
