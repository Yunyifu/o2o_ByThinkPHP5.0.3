<?php
namespace app\index\controller;

use think\Controller;

class Detail extends Base
{
    public function index($id)
    {
        if(!$id){
            $this->error('ID不合法');
        }
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status !=1){
            $this->error('改商品不存在');
        }

        return $this->fetch('',['title'=>$deal->name]);
    }

}
