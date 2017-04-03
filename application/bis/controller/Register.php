<?php
namespace app\bis\controller;
use think\Controller;
class Register extends Controller
{
    public function index()
    {
        //获取一级城市数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        return $this->fetch('',[
            'citys'=>$citys,
            'categorys'=>$categorys

        ]);
    }

    public function add(){
        if(!request()->isPost()){
            $this->error('请求错误');
        }
        //获取表单的值
        $data = input('post.','','htmlentities');
        //校验数据
        $validate = validate('Bis');
        if(!$validate->scene('add')->check($data)){
            $this->error($validate->getError());
        }

        //获取经纬度
        $lnglat =  \Map::getLntLat($data['address']);
        if(empty($lnglat) || $lnglat['status'] !=0 || $lnglat['result']['precise'] != 1){
            $this->error('无法获取地址,或者匹配的地址不精确，请重新填写');
        }

        //判断用户是否已存在
        $accountResult = model('BisAccount')->get(['username'=>$data['username']]);
        if($accountResult){
            $this->error('用户已存在，请重新输入');
        }



        //商户信息入库
        $bisData = [
            'name' => $data['name'],
            'city_id'=> $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => empty($data['description'])?'':$data['description'],
            'bank_info' => $data['bank_info'],
            'bank_user' => $data['bank_user'],
            'bank_name' => $data['bank_name'],
            'faren' => $data['faren'],
            'faren_tel' => $data['faren_tel'],
            'email' => $data['email'],
        ];
        $bisId = model('Bis')->add($bisData);echo $bisId;

        //总店信息校验...待写

        //账户相关嘻嘻校验...待写

        //总店信息入库
        $data['cat']  = '';
        if(!empty($data['se_category_id'])){
            $data['cat'] = implode('|',$data['se_category_id']);
        }

        $locationData = [
            'bis_id' => $bisId,
            'logo' => $data['logo'],
            'name' => $data['name'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'category_id' => $data['category_id'],
            'category_path' => $data['category_id'].','.$data['cat'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'api_address' => $data['address'],
            'open_time' => $data['open_time'],
            'content' => empty($data['content'])? '':$data['content'],
            'is_main' => 1,
            'xpoint' => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
            'ypoint' => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],

        ];

        $locationId =  model('BisLocation')->add($locationData);
        //用户相关信息
        //自动生成密码加盐字符串
        $data['code'] = mt_rand(100,10000);
        $accountData = [
            'bis_id' => $bisId,
            'username' => $data['username'],
            'code' => $data['code'],
            'password' => md5($data['password'].$data['code']),
            'is_main' => 1,//默认室总店管理员
        ];

        $accountId = model('BisAccount')->add($accountData);

        if(!$accountId){
            $this->error('申请失败');
        }

        //发送邮件给用户
        $url = request()->domain().url('bis/register/waiting',['id'=>$bisId]);
        $title = 'o2o入住申请通知';
        $content = "您提交的入住申请需等待平台放审核，您可以通过点击链接'<a href='".$url."' target='_blank'>查看链接</a>'查看审核状态";
        \phpmailer\Email::sent($data['email'],$title,$content);
        $this->success('申请成功',url('Register/waiting',['id'=>$bisId]));
    }

        public function waiting($id){
            if(empty($id)){
                $this->error('审核数据不存在');

            }

            $detail = model('Bis')->get($id);
            return $this->fetch('',[
                'detail' => $detail,
            ]);
        }
}