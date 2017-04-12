<?php
namespace app\bis\controller;
use think\Controller;
class Deal extends Base
{
    public function index(){

        $bisId = $this->getLoginUser()->bis_id;
        $result = model('Deal')->getNormalDealByBisId($bisId,10);
        $result = collection($result)->toArray();
        //halt($result[0]['create_time']);
        return $this->fetch('',['result'=>$result]);
    }

    public function add(){

        $bisId = $this->getLoginUser()->bis_id;
        if(request()->isPost()){
            //数据插入
            $data = input('post.');
            //print_r($data);exit;
            //数据校验，待写
            $location = model('BisLocation')->get($data['location_ids'][0]);
            $deals = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'se_category_id' => empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
                'city_id' => $data['city_id'],
                'location_ids' => empty($data['location_ids'])?'':implode(',',$data['location_ids']),
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'total_count' => $data['total_count'],
                'origin_price' => $data['origin_price'],
                'current_price' => $data['current_price'],
                'coupons_begin_time' => strtotime($data['coupons_begin_time']),
                'coupons_end_time' => strtotime($data['coupons_end_time']),
                'notes'=>$data['notes'],
                'description' => $data['description'],
                'bis_account_id' => $this->getLoginUser()->id,
                'xpoint' => $location->xpoint,
                'ypoint' => $location->ypoint,
                 ];
            $id =model('deal')->add($deals);
            if($id){
                $this->success('商品添加成功',url('deal/index'));
            }else{
                $this->error('商品添加失败');
            }

        }else{

        //获取一级城市数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys,
            'bisLocations'=>model('BisLocation')->getNormalLocationByBisId($bisId),

        ]);
        }

    }


}
