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
        //根据id查询商品数据
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status !=1){
            $this->error('改商品不存在');
        }
        //获取分类信息
        $category = model('Category')->get($deal->category_id);
        //获取商户ID
        $bisId = $deal->bis_id;
        //获取分店信息
        $locations = model('BisLocation')->getNormalLocationInId($deal->location_ids);

        $flag = 0;
        if($deal->start_time>time()){
            $flag = 1;
            $dtime = $deal->start_time-time();
            $timedata = '';
            $d = floor($dtime/(3600*24));
            if($d){
                $timedata .= $d."天";
            }
            $h = floor($dtime%(3600*24)/3600);
            if($h){
                $timedata .= $h."小时";
            }
            $m = floor($dtime%(3600*24)%3600/60);
            if($m){
                $timedata .= $m."分钟";
            }
            $this->assign('timedata',$timedata);

        }

        return $this->fetch('',[
                'title'=>$deal->name,
                'category'=>$category,
                'locations'=>$locations,
                'deal'=>$deal,
                'overplus'=>$deal->total_count-$deal->buy_count,
                'flag' =>$flag,
                'mapstr'=>$locations[0]['xpoint'].','.$locations[0]['ypoint']
            ]
            );
    }

}
