<?php
namespace app\admin\controller;
use think\Controller;
class City extends Controller
{
    private $obj;
    public function _initialize(){
        $this->obj = model('City');
    }
    public function index()
    {
        $parentId = input('get.parent_id',0,'intval');
        $citys = $this->obj->getNormalCitys($parentId);
        return $this->fetch('',['citys'=>$citys]);
   }

    public function add(){
        $citys = $this->obj->getNormalCitysByParentId();
        return $this->fetch('',['citys'=>$citys]);
    }

    public function save(){
        $data = input('post.');
        $validate = validate('Category');
        if(!$validate->scene('add')->check($data)){
            $this->error($validate->getError());
        }
        if(!empty($data['id'])){
            return $this->update($data);
        }
        $res = $this->obj->add($data);
        if($res){
            $this->success('插入成功');
        }else{
            $this->error('新增失败');
        }
    }



    public function listorder($id,$listorder){
       /*echo $id."<br />";
        echo $listorder;*/

         $res = $this->obj->save([
            'listorder'=>$listorder],
            ['id'=>$id]);
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');
        }
    }

    //修改状态
    public function status(){
        $data = input('get.');
        /*$validate = validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }*/

        $res = $this->obj->save([
            'status'=>$data['status']],
            ['id'=>$data['id']]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }
}
