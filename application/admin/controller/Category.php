<?php
namespace app\admin\controller;
use think\Controller;
class Category extends Controller
{
    private $obj;
    public function _initialize(){
        $this->obj = model('Category');
    }
    public function index()
    {
        $parentId = input('get.parent_id',0,'intval');
        $categorys = $this->obj->getFirstCategorys($parentId);
        return $this->fetch('',['categorys'=>$categorys]);
   }

    public function add(){
        $categorys = $this->obj->getNormalFirstCategory();
        return $this->fetch('',['categorys'=>$categorys]);
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

    public function edit($id = 0){
        if(intval($id)<1){
            $this->error('参数不合法');
        }
        $category = $this->obj->get($id);
        $categorys = $this->obj->getNormalFirstCategory();
        return $this->fetch('',
            ['categorys'=>$categorys,
             'category'=>$category
            ]
        );
    }

    public function update($data){
        $res =  $this->obj->save($data,['id'=>intval($data['id'])]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
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
        $validate = validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }

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
