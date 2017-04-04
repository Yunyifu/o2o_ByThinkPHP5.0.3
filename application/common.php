<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status){
    if($status == 1){
        $str = "<span class='label label-success radius'>正常</span>";
    }elseif($status == 0){
        $str = "<span class='label label-danger radius'>待审</span>";
    }else{
        $str = "<span class='label label-success radius'>删除</span>";
    }
    return $str;
}

function doCurl($url,$type=0,$data=[]){
    $ch = curl_init(); //初始化
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);

    if($type == 1){
        //post
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    //执行并获取内容
    $output = curl_exec($ch);
    //释放CURL
    curl_close($ch);
    return $output;


}

function bisRegister($status){
    if($status == 1){
        $str = '入住申请成功';
    }else if($status ==0){
        $str = '待审核，审核后逸夫放会发送邮件通知，请关注';
    }else if($status ==2){
        $str = '非常抱歉，您提交的材料不符合申请条件，请重新提交';
    }else {
        $str = '改申请已被删除';
    }
    return $str;
}

function pagination($obj){
    if(!$obj){
        return '';
    }

    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->render().'</div>';
}

function getSeCityName($path){
    if(empty($path)){
        return '';
    }

    if(preg_match('/,/',$path)){
        $city_path = explode(',',$path);
        $cityId = $city_path[1];
    }else{
        $cityId = $path;
    }

    $city = model('City')->get($cityId);
    return $city->name;
}

//前端页面“多店通用”函数
function countLocation($ids){
    if(!$ids){
        return 1;
    }

    if(preg_match('/,/',$ids)){
        $arr = explode(',',$ids);

        return count($arr);
    }
}