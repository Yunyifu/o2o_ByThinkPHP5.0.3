<?php
namespace app\index\controller;

use think\Controller;

class Lists extends Base
{

    public function index()
    {
        return $this->fetch();
    }
}
