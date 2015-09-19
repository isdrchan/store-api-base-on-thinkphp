<?php
namespace Api\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index() {
        $this->success("跳转到Admin模块" , "admin.php");
    }
}