<?php
namespace app\common\model;
use think\model;

class Deal extends BaseModel
{

    public function getNormalDeals($data = []){

        $dada['status'] = 1;
        $order = ['id' => 'desc'];
        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }

    /*根据分类和城市获取商品数据
     * $id为分类ID，cityid为城市ID，默认10条
     */
    public function getNormalDealByCategoryCityId($id,$cityId,$limit=10){
        $data = [
            'end_time' => ['gt',time()],
            'category_id' => $id,
            'city_id' => $cityId,
            'status' => 1,
        ];

        $order = ['listorder'=>'desc','id' => 'desc',];

        $result = $this->where($data)->order($order);
        if($limit){
            $result->limit($limit);
        }

        return $result->select();
    }


}