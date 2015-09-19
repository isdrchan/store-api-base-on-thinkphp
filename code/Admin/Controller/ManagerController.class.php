<?php

namespace Admin\Controller;
use Think\Controller;

class ManagerController extends Controller {

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:base");
    }

	public function index() {
		//只有超级管理员才能管理所有用户
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Manager = D("Manager");
			
			//搜索功能核心代码，检测有没搜索表单的提交，有则再加条件select
			if(!empty($_POST)) {
				$name = $_POST['name'];
				$condition['name'] = array('like','%'.$name.'%');
                $result = $Manager -> where($condition) -> select();
			} else {
				$result = $Manager -> select();
			}
			
			foreach ($result as $key => $value) {
				if($value['role'] == 1) { $result[$key]['new'] = '超级管理员';}
				else { $result[$key]['new'] = '普通管理员';}
			}
			$this -> assign('id', $_SESSION['id']);	
			$this -> assign('name', $_SESSION['name']);	
			$this -> assign('role', $_SESSION['role']);	
			
			$front = D("Front");
			$fid = 1;
			$resultByFront = $front -> where('fid = '.$fid) -> find();
			$this -> assign('resultByFront', $resultByFront);
			
			$this -> assign('result', $result);	
			$this -> display();
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function add() {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Manager = new \Model\ManagerModel();
			if(!empty($_POST)) {
				$z = $Manager -> create();
				if(!$z){
					$this -> error($Manager -> getError().'！', 'javascript:history.back();');
				} else {
					$z = $Manager -> add();
					if($z) {
						$this -> success("添加管理员成功！",U('index'));
					} else {
						$this -> error("添加管理员失败！",U('index'));
					}
				}
			} else {
			
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$front = D("Front");
				$fid = 1;
				$resultByFront = $front -> where('fid = '.$fid) -> find();
				$this -> assign('resultByFront', $resultByFront);
				
				$this -> display();
			}
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function upd($id = -1) {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
//			$Manager = new \Model\ManagerModel();
			$Manager = D("Manager");
			if(!empty($_POST)) {
                //修改了密码要先将密码加密
                if($_POST['password']) { $_POST['password'] = MD5($_POST['password']);}
				//将没修改的（value为空元素）的unset掉
				foreach( $_POST as $key => $value){
					if( !$value)  
					unset( $_POST[$key] ); 
				}   
				$Manager -> create();
				$z = $Manager -> save();
				if($z) {
					$this -> success("修改管理员信息成功！",U('index'));
				} else {
					$this -> error("修改管理员信息失败！",U('index'));
				}
			} else {
				if(!empty($_GET)) {
					$result = $Manager -> where('id = '.$id) -> find();
					if(!empty($result)) {
						$this -> assign('result', $result);	//把数据assign到模板
						if($_SESSION['role'] != 1) $this -> error('非法访问！错误代码：1', 'javascript:history.back();');	
						
						$this -> assign('id', $_SESSION['id']);	
						$this -> assign('name', $_SESSION['name']);	
						$this -> assign('role', $_SESSION['role']);	
						$front = D("Front");
						$fid = 1;
						$resultByFront = $front -> where('fid = '.$fid) -> find();
						$this -> assign('resultByFront', $resultByFront);
						
						
						$this -> display();
					} else {
						$this -> error('非法访问！错误代码：2', 'javascript:history.back();');
					}
				} else {
					$this -> error('非法访问！错误代码：3', 'javascript:history.back();');
				}
			}
		}
	}
	
	public function del($id = -1) {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Manager = D("Manager");
			if(!empty($_POST)) {
				$Manager -> create();
				$z = $Manager -> delete();
				if($z) {
					$this -> success("删除管理员信息成功！", U('index'));
				} else {
					$this -> error("删除管理员信息失败！", U('index'));
				}
			} else {
				if(!empty($_GET)) {
					$result = $Manager -> where('id = '.$id) -> find();
					if(!empty($result)) {
						$this -> assign('result', $result);	//把数据assign到模板
						if($_SESSION['role'] != 1 ) $this -> error('非法访问！', 'javascript:history.back();');	//判断字段author的值是否等于当前登录用户的id，防止非法访问
						
						$this -> assign('id', $_SESSION['id']);	
						$this -> assign('name', $_SESSION['name']);	
						$this -> assign('role', $_SESSION['role']);	
						$front = D("Front");
						$fid = 1;
						$resultByFront = $front -> where('fid = '.$fid) -> find();
						$this -> assign('resultByFront', $resultByFront);
						
						
						$this -> display();
					} else {
						$this -> error('非法访问！错误代码：1', 'javascript:history.back();');
					}
				} else {
					$this -> error('非法访问！错误代码：2', 'javascript:history.back();');
				}
			}
		} else {
			$this -> error('非法访问！错误代码：3', 'javascript:history.back();');
		}
	}
	
}