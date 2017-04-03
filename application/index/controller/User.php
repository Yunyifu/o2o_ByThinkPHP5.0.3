<?php
namespace app\index\controller;

use think\Controller;

class User extends Controller
{
    public function login()
    {
        $user = session('o2o_user','','o2o');
        if($user && $user->id){
            $this->redirect(url('index/index'));
        }
       return $this->fetch();

    }
    public function register()
    {
        if(request()->isPost()){
            $data = input('post.');
            if(!captcha_check($data['verifycode'])){
                $this->error('验证码错误');
            }
            //校验用户名和邮箱格式是否正确 代填写
            if($data['password'] != $data['repassword']){
                $this->error('两次输入密码不一致');
            }
            //生成密码加密盐
            $data['code'] = mt_rand(100,10000);
            $data['password'] = md5($data['password'].$data['code']);
            try{$res = model('User')->add($data);}catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if($res){
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');
            }


        }else{
        return $this->fetch();
        }
    }

    public function logincheck(){
        if(!request()->isPost()){
            $this->error('提交不合法');
        }
        $data = input('post.');
        $user = model('User')->getUserByUsername($data['username']);
        if(!$user || $user->status != 1){
            $this->error('该用户不存在');
        }
        //判断密码是否正确
        if(md5($data['password'].$user->code) != $user->password){
            $this->error('密码不正确');
        }

        //登陆成功
        model('User')->updateById(['last_login_time'=>time()],$user->id);

        session('o2o_user',$user,'o2o');
        $this->success('登陆成功',url('index/index'));

    }

    public function logout(){
        session(null,'o2o');
        $this->redirect(url('user/login'));
    }
}
