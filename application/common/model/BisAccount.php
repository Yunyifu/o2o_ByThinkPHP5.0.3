<?php
namespace app\common\model;
use think\Model;
class BisAccount extends Model
{

    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        $data['status'] = 0;
        //$data['create_time'] = time();
        $this->save($data);
        return $this->id;
    }

    public function updateById($data, $id)
    {
        //allowField过滤非数据表中的数据
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }

}