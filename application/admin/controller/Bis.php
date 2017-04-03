<?php
namespace app\admin\controller;
use think\Controller;
class Bis extends Controller
{
    private $obj;
    public function _initialize(){
        $this->obj = model('Bis');
    }


    //入住申请列表
    public function apply(){
        $bis =  $this->obj->getBisByStatus();
        return $this->fetch('',
            ['bis' => $bis]
            );
    }

    //正常商户列表
    public function index(){
        $bis =  $this->obj->getBisByStatus(1);
        return $this->fetch('',
            ['bis' => $bis]
        );
    }

    //商户入住资料编辑
    public function detail(){
        $id = input('get.id');
        $citys = model('City')->getNormalCitysByParentId();
        $categorys = model('Category')->getNormalCategoryByParentId();
        $bisData = model('Bis')->get($id);
        $bisLocation = model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);
        $accountData = model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys,
            'bisData'=>$bisData,
            'bisLocation'=>$bisLocation,
            'accountData'=>$accountData

        ]);


    }

    public function status(){
        $data = input('get.');
        //数据校验
        /*$validate = validate('Bis');
        if(!$validate->scene('status')->check($data)){
            $this->error($validate->getError());
        }*/

        $email = $this->obj->get(['id'=>$data['id']]);

        $res = $this->obj->save([
            'status'=>$data['status']],
            ['id'=>$data['id']]);

        $location = model('BisLocation')->save([
            'status'=>$data['status']],
            ['bis_id'=>$data['id'],'is_main'=>1]);

        $account = model('BisAccount')->save([
            'status'=>$data['status']],
            ['bis_id'=>$data['id'],'is_main'=>1]);


        if($res&&$location&&$account){
            //发送邮件
            if($data['status'] ==1){
            \phpmailer\Email::sent($email['email'],'您提交的信息已审核通过','恭喜！您提交的信息已审核通过！');
            }
            if($data['status'] ==0){
                \phpmailer\Email::sent($data['email'],'抱歉您提交的信息已审核不通过','抱歉！您提交的信息已审核不通过！');
            }
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }




}
