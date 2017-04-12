<?php
namespace app\common\model;

use think\Model;

class Bis extends Model
{

    protected $autoWriteTimestamp = true;
    public function add($data){
        $data['status'] = 0;
        //$data['create_time'] = time();
        $this->save($data);
        return $this->id;
    }

    //获取商家数据
    public function getBisByStatus($status = 0){

        $order = ['id' => 'desc'];
        $result = $this->where($status)->order($order)->paginate(5);
        return $result;
    }

    //获取删除商家数据
    public function getDelBisByStatus(){

        $order = ['id' => 'desc'];
        $status = ['status' => -1];
        $result = $this->where($status)->order($order)->paginate(5);
        return $result;
    }





}