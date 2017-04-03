<?php
namespace app\admin\controller;
use think\Controller;
class Base extends Controller
{
    public function status(){
        //获取值
        $data = input('get.');
        //利用TP5 validate校验id和status，待添加
        if(empty($data['id'])){
            $this->error('id不合法');
        }
        if(!is_numeric($data['status'])){
            $this->error('状态不合法');
        }

        //获取控制器
        $model = request()->controller();

        $res = model($model)->save(
            ['status'=>$data['status']],
            ['id'=>$data['id']]
        );
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }
}
