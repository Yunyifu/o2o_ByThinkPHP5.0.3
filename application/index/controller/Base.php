<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    public $city = '';
    public $account = '';
    public $cats = '';
    public function _initialize()
    {
        $citys = model('City')->getNormalCitys();
        //获取城市数据
        $this->getCity($citys);
        //获取首页分类数据
        $cats = $this->getRecommendCats();
        //halt($cats);
        $this->assign('cats',$cats);
        $this->assign('citys',$citys);
        $this->assign('city',$this->city);
        $this->assign('controller',strtolower(request()->controller()));
        $this->assign('user',$this->getLoginUser());
        $this->assign('title','o2o团购网');
    }

    /*
     * 获取城市数据，并在选择后改变前面城市的值
     */

    public function getCity($citys){
        foreach($citys as $city){
            $city = $city->toArray();
            if($city['is_default'] == 1){
                $defaultuname =$city['uname'];
                break;
            }
        }

        $defaultuname = $defaultuname ? $defaultuname :'nanchang';

        if(session('cityuname','','o2o') && !input('get.city')){
            $cityuname = session('cityuname','','o2o');
        }else{
            $cityuname = input('get.city',$defaultuname,'trim');
            session('cityuname',$cityuname,'o2o');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();
    }

    /*
     * 获取首页分类数据
     */
    public function getRecommendCats(){
        $parentIds = $sedCatArr = $recomCats = [];
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);
        foreach($cats as $cat){
            $parentIds[] = $cat->id;
        }
        //获取二级分类数据
        $sedCats = model('Category')->getNormalCategorysByParentId($parentIds);
        foreach($sedCats as $sedCat){
            $sedCatArr[$sedCat->parent_id][] = [
                'id'=>$sedCat->id,
                'name'=>$sedCat->name
            ];
        }

        foreach($cats as $cat){
            //recomCats代表的是一级和二级数据， []第一个参数是一级分类的name，第二个参数是此一级分类下面的所有二级分类数据
            $recomCats[$cat->id] = [$cat->name,empty($sedCatArr[$cat->id])?[]:$sedCatArr[$cat->id]];
        }

        return $recomCats;


    }


    public function getLoginUser()
    {
        if(!$this->account){
            $this->account = session('o2o_user','','o2o');
        }

        return $this->account;
    }

}
