<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{


    public function add($data=[]){
        if(!is_array($data)){
            $this->error('传递的不是数组');
        }
        $data['status'] = 1;
        //$data['create_time'] = time();
        return $this->data($data)->allowField(true)->save();

    }

    //获取用户数据
    public function getUserByStatus($status = 1){

        $order = ['id' => 'desc'];
        $result = $this->where($status)->order($order)->paginate(5);
        return $result;
    }

    //获取删除商家数据
    public function getDelUserByStatus(){

        $order = ['id' => 'desc'];
        $status = ['status' => -1];
        $result = $this->where($status)->order($order)->paginate(5);
        return $result;
    }





}