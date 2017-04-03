<?php
namespace app\admin\controller;
use think\Controller;
class Featured extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model('Featured');
    }

    public function add(){
        if(request()->isPost()){
            //入库逻辑
            $data = input('post.');
            //数据校验，待添加
            $id = model('Featured')->add($data);
            if($id){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
        //获取推荐位类别
            $types = config('featured.featured_type');

            return $this->fetch('',[
                'types' => $types,
             ]);
        }
    }

    public function index(){

        $types = config('featured.featured_type');
        $type = input('get.type',0,'intval');
        $results = $this->obj->getFeaturesByType($type);
        return $this->fetch('',[
            'types' => $types,
            'results'=>$results
        ]);
    }

    /*public function status(){
        //获取值
        $data = input('get.');
        $res = $this->obj->save(
            ['status'=>$data['status']],
            ['id'=>$data['id']]
        );
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }*/

}
