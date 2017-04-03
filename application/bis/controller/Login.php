<?php
namespace app\bis\controller;
use think\Controller;
class Login extends Controller
{
    public function index()
    {
        if(request()->isPost()){

            //处理登陆逻辑
            $data = input('post.');
            //获取英用户名并作严格判断，判断待写

            $ret = model('BisAccount')->get(['username'=>$data['username']]);
            if(!$ret || $ret['status']!=1){
                $this->error('该用户不存在，或者未被审核通过');
            }

            if($ret->password!=md5($data['password'].$ret->code)){
                $this->error('密码不正确');
            }

            model('BisAccount')->updateById(['last_login_time'=>time()],$ret->id);

            //保存用户信息，bis是作用域
            session('bisAccount',$ret,'bis');
            return $this->success('登陆成功',url('index/index'));


        }else{

            $account = session('bisAccount','','bis');
            if($account && $account->id){
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }

    public function logout(){
        //清除SESSION
        session(null,'bis');
        //跳转
        $this->redirect(url('login/index'));
    }
}