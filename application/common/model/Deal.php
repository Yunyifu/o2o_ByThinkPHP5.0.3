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
    /*
     * 根据商户ID取数据
     */
    public function getNormalDealByBisId($bisId,$limit=10){
        $data = [
            'bis_id' => $bisId,
            'status' => 1,
        ];

        $order = ['listorder'=>'desc','id' => 'desc',];

        $result = $this->where($data)->order($order);
        if($limit){
            $result->limit($limit);
        }

        return $result->select();
    }

    public function getDealByCondition($data=[],$orders){
        if(!empty($orders['order_sales'])){
            $order['buy_count'] = 'desc';
        }
        if(!empty($orders['order_price'])){
            $order['current_price'] = 'desc';
        }
        if(!empty($orders['order_time'])){
            $order['create_time'] = 'desc';
        }
        $order['id'] = 'desc';
        $datas[] = "end_time >".time();
        if(!empty($data['se_category_id'])){
            $datas[] = " find_in_set(".$data['se_category_id']. ",se_category_id)";
        }
        if(!empty($data['category_id'])){
            $datas[] = 'category_id = '.$data['category_id'];
        }
        if(!empty($data['city_id'])){
            $datas[] = 'city_id = '.$data['city_id'];
        }
        $datas[] = 'status = 1';

        $result =$this->where(implode(' AND ', $datas))->order($order)->paginate();
        return $result;
    }


}