<?php
namespace app\admin\controller;
use think\Controller;
class User extends Controller
{
    private $obj;
    public function _initialize(){
        $this->obj = model('User');
    }




    public function index(){
        $user =  $this->obj->getUserByStatus();
        return $this->fetch('',
            ['user' => $user]
        );
    }



    public function delete(){
        $user =  $this->obj->getDelUserByStatus();
        return $this->fetch('',
            ['user' => $user]
        );
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
