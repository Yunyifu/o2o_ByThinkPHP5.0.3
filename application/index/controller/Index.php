<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        //获取首页大图数据

        //获取广告位相关数据


        //商品分类数据获取  根据分类：美食 、城市
        $datas = model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);

        //获取四个子分类的栏目名称
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1,4);

        return $this->fetch('',[
            'datas' => $datas,
            'meishicates' => $meishicates
        ]);
    }
}
