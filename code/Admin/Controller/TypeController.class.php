<?php

namespace Admin\Controller;
use Think\Controller;

class TypeController extends Controller {

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:base");
    }

	public function index() {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Type = D("Type");
			
			$result = $Type -> order('tid asc') -> select();

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
//			$Type = new \Model\TypeModel();
			$Type = D("Type");
			if(!empty($_POST)) {
				$z = $Type -> create();
				if(!$z){
					$this -> error($Type -> getError().'！', 'javascript:history.back();');
				} else {
					$z = $Type -> add();
					if($z) {
						$this -> success("添加类别成功！",U('index'));
					} else {
						$this -> error("添加类别失败！",U('index'));
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
	
	public function upd($tid = -1) {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Type = D("Type");
			if(!empty($_POST)) {
				$Type -> create();
				$z = $Type -> save();
				if($z) {
					$this -> success("修改类别成功！",U('index'));
				} else {
					$this -> error("修改类别失败！",U('index'));
				}
			} else {
				if(!empty($_GET)) {
					$result = $Type -> where('tid = '.$tid) -> find();
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
	
	public function del($tid = -1) {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$Type = D("Type");
			if(!empty($_POST)) {
				$Type -> create();
				$z = $Type -> delete();
				if($z) {
					$this -> success("删除类别成功！", U('index'));
				} else {
					$this -> error("删除类别失败！", U('index'));
				}
			} else {
				if(!empty($_GET)) {
					$result = $Type -> where('tid = '.$tid) -> find();
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