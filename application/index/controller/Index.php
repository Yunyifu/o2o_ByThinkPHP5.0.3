<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        //获取首页大图数据
        $midArea = model('Featured')->getFeaturesByType(0);
        //获取广告位相关数据
        $rightSideBar = model('Featured')->getFeaturesByType(1);

        //商品分类数据获取  根据分类：美食 、城市
        $datas = model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);
        $yuledatas = model('Deal')->getNormalDealByCategoryCityId(8,$this->city->id);
        $lirendatas = model('Deal')->getNormalDealByCategoryCityId(6,$this->city->id);

        //获取四个子分类的栏目名称
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1,4);
        $yulecates = model('Category')->getNormalRecommendCategoryByParentId(8,4);
        $lirencates = model('Category')->getNormalRecommendCategoryByParentId(6,4);

        return $this->fetch('',[
            'datas' => $datas,
            'yuledatas' => $yuledatas,
            'meishicates' => $meishicates,
            'yulecates' => $yulecates,
            'lirendatas' => $lirendatas,
            'lirencates' => $lirencates,
            'midArea' => $midArea,
            'rightSideBar' =>$rightSideBar,
        ]);
    }
}
