<?php

namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller {
    public function base() {
    
		//判断用户是否登录
		if(isset($_SESSION['name'])) {
			
			$front = D("Front");
			$Manager = D("Manager");
			$ShopList = D("Articles");	//实例化Model
			
			//获得管理员限权和管理员id，判断是否超级管理员
			$id = $_SESSION['id'];
			$role = $_SESSION['role'];
			

			//把数据assign到模板
			$fid = 1;
			$resultByFront = $front -> where('fid = '.$fid) -> find();
			$this -> assign('resultByFront', $resultByFront);
			$this -> assign('id', $_SESSION['id']);	
			$this -> assign('name', $_SESSION['name']);	
			$this -> assign('role', $_SESSION['role']);	
			
			$this -> display();
        
        } else {
			$this -> success("即将跳转到登录页面！", U('Index/login'));
        }
    }
    
   
}