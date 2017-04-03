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


}