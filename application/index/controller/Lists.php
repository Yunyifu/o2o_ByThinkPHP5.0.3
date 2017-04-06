<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

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
        $data = [];
        if(in_array($id,$firstCatIds)){
            //一级分类
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id){
            //二级分类
            //获取二级分类
            $category = model('Category')->get($id);
            //halt($category);
            if(!$category||$category->status !=1){
                $this->error('商品ID数据不合法');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;
        }else{
            //id是0
            $categoryParentId = 0;
        }

        //获取父类下所有的子分类
        $sedcategorys = [];
        $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        //halt($sedcategorys);

        //排序数据获取逻辑
        //$order_sales = input('oder_sales','');//排查发现input无法获取到值,使用Request解决
        $order_sales =Request::instance()->get('order_sales');
        $order_price = Request::instance()->get('order_price');
        $order_time = Request::instance()->get('order_time');
        $orders =[];
        //$orders['order_sales'] = $order_sales;
        if(!empty($order_sales)){
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)){
            $orderflag = 'order_price';
            $orders['order_price'] = $order_price;
        }elseif(!empty($order_time)){
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag ='';
        }

        $data['city_id'] = $this->city->id;
        //根据上面的条件查询商品列表数据
        $deals = model('Deal')->getDealByCondition($data,$orders);

        return $this->fetch('',[
            'categorys'=>$categorys,
            'sedcategorys'=>$sedcategorys,
            'id'=>$id,
            'orderflag'=>$orderflag,
            'categoryParentId'=>$categoryParentId,
            'orders'=>$orders,
            'deals'=>$deals,


        ]);
    }
}
