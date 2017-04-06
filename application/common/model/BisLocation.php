<?php
namespace app\common\model;

use think\Model;

class BisLocation extends Model
{

    protected $autoWriteTimestamp = true;
    public function add($data){
        $data['status'] = 0;
        //$data['create_time'] = time();
        $this->save($data);
        return $this->id;
    }


    public function getNormalLocationByBisId($bisId){
        $data =[
            'bis_id'=>$bisId,
            'status'=>1
        ];

        $result = $this->where($data)->order('id','desc')->select();
        return $result;
    }

    public function getNormalLocationInId($ids){
        $data = [ 'id'=>['in',$ids], 'status' => 1 ];
        return $this->where($data)->select();
    }

}