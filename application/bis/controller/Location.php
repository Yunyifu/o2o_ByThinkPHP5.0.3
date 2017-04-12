<?php
namespace app\bis\controller;
use think\Controller;
class Location extends Base
{
    public function index(){

        $bisId = $this->getLoginUser()->bis_id;
        $result = model('BisLocation')->getALLLocationByBisId($bisId);

        return $this->fetch('',[
            'result' => $result,
        ]);
    }

    public function add(){
        if(request()->isPost()){
            //门店入库操作
            $bisId = $this->getLoginUser()->bis_id;
            $data= input('post.');
            $data['cat'] ='';
            if(!empty($data['se_category_id'])){
                $data['cat'] = implode('|',$data['se_category_id']);
            }
            //获取经纬度
            $lnglat =  \Map::getLntLat($data['address']);
            if(empty($lnglat) || $lnglat['status'] !=0 || $lnglat['result']['precise'] != 1){
                $this->error('无法获取地址,或者匹配的地址不精确，请重新填写');
            }
            $locationData = [
                'bis_id' => $bisId,
                'logo' => $data['logo'],
                'name' => $data['name'],
                'tel' => $data['tel'],
                'contact' => $data['contact'],
                'category_id' => $data['category_id'],
                'category_path' => $data['category_id'].','.$data['cat'],
                'city_id' => $data['city_id'],
                'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
                'api_address' => $data['address'],
                'open_time' => $data['open_time'],
                'content' => empty($data['content'])? '':$data['content'],
                'is_main' => 0,
                'xpoint' => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
                'ypoint' => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],

            ];

            $locationId =  model('BisLocation')->add($locationData);

            if($locationId){
                return $this->success('门店申请成功');
            }else{
                return $this->error('门店申请失败');
            }

        }else{
            $citys = model('City')->getNormalCitysByParentId();
            $categorys = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys

        ]);}
    }

    public function status(){
        $data = input('get.');
        /*$validate = validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }*/

        $res = model('BisLocation')->save([
            'status'=>$data['status']],
            ['id'=>$data['id']]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }
}
