<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    public $city = '';
    public $account = '';
    public function _initialize()
    {
        $citys = model('City')->getNormalCitys();
        $this->getCity($citys);
        $this->assign('citys',$citys);
        $this->assign('city',$this->city);
        $this->assign('user',$this->getLoginUser());
    }

    /*
     * 获取城市，并在选择后改变前面城市的值
     */
    public function getCity($citys){
        foreach($citys as $city){
            $city = $city->toArray();
            if($city['is_default'] == 1){
                $defaultuname =$city['uname'];
                break;
            }
        }

        $defaultuname = $defaultuname ? $defaultuname :'nanchang';

        if(session('cityuname','','o2o') && !input('get.city')){
            $cityuname = session('cityuname','','o2o');
        }else{
            $cityuname = input('get.city',$defaultuname,'trim');
            session('cityuname',$cityuname,'o2o');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();
    }

    public function getLoginUser()
    {
        if(!$this->account){
            $this->account = session('o2o_user','','o2o');
        }

        return $this->account;
    }

}
