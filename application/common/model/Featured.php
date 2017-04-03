<?php
namespace app\common\model;
use think\model;

class Featured extends BaseModel
{
    //根据类型获取列表数据
    public function getFeaturesByType($type){
        $data = [
            'type' => $type,
            'status' => ['neq',-1],
        ];
        $order = ['id' =>'desc'];

        $result = $this->where($data)->order($order)->paginate();
        return $result;
    }




}