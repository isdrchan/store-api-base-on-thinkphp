<?php

namespace Admin\Controller;
use Think\Controller;

class IndexController extends Controller {

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:base");
    }
			
    public function index() {
    
		//判断用户是否登录
		if(isset($_SESSION['name'])) {

            //实例化Model
			$Manager = D("Manager");
			$ShopList = D("Articles");
			$Type = D("Type");


			//获得管理员限权和管理员id，判断是否超级管理员
			$id = $_SESSION['id'];
			$role = $_SESSION['role'];
			
			//搜索功能核心代码，检测有没表单的提交，有则再加条件select
			if(!empty($_POST)) {
				$shopname = $_POST['shopname'];
				if($role == 1) { $condition['shopname'] = array('like','%'.$shopname.'%'); $result = $ShopList -> where($condition) -> select();}
				else { $condition['shopname'] = array('like','%'.$shopname.'%'); $result = $ShopList -> where('author = '.$id) -> where($condition) -> select();};

			} else {
				
				if($role == 1) { $result = $ShopList -> select();}
				else { $result = $ShopList -> where('author = '.$id) -> select();};
				
			}
			
			$ManagerResult = $Manager -> field('id, name') -> select();
			$TypeResult = $Type -> field('tid, typename') -> select();
			
			//select后的结果集的author字段（用户id）与管理员表的用户名匹配
			foreach ($result as $key => $value) {
				foreach ($ManagerResult as $key0 => $value0) {
					if($value['author'] == $value0['id']) $result[$key]['new'] = $value0['name']; 
				}
			}
			
			//select后的结果集的商铺类别id字段与商铺类别表的类别名匹配
			foreach ($result as $key => $value) {
				foreach ($TypeResult as $key0 => $value0) {
					if($value['typeid'] == $value0['tid']) $result[$key]['typename'] = $value0['typename']; 
				}
				if(empty($result[$key]['typename'])) $result[$key]['typename'] = '未分类';
			}
			
			//调试用
	// 		foreach ($result as $key => $value) {
	// 			echo $value['shopname'].'<br/>';
	// 		}
	//		print_r($result);

			//把数据assign到模板
			$front = D("Front");
			$fid = 1;
			$resultByFront = $front -> where('fid = '.$fid) -> find();
			$this -> assign('resultByFront', $resultByFront);
			
			$this -> assign('result', $result);	
			$this -> assign('id', $_SESSION['id']);	
			$this -> assign('name', $_SESSION['name']);	
			$this -> assign('role', $_SESSION['role']);	

            $this -> assign('result', $result);

			$this -> display();
        
        } else {
			$this -> success("即将跳转到登录页面！", U('login'));
        }
    }
    
    public function login() {
		
		if(!empty($_POST)) {
			$user = new \Model\ManagerModel();
			$result = $user -> checkNamePassword($_POST['name'], MD5($_POST['password']));
			
			
//			print_r( $result);
			if($result == false) {
				
				$this -> error("用户名或密码错误！",'javascript:history.back();');
				
			} else {
				//创建session，保存登录信息
				session('id', $result['id']);
				session('name', $result['name']);
				session('role', $result['role']);

                //实例化一个model来将登录ip和时间写入数据库
                $saveLoginInfo = M("manager");
                $saveLoginInfo -> loginip = get_client_ip();
                $saveLoginInfo -> logintime = date('Y-m-d H:i:s',time());
                $saveLoginInfo -> where('id='.$result['id']) -> save();

				$this -> success("登录成功！欢迎你，".$_POST['name']."。" ,U('index'));
			}
		} else {
			session(null);	//到登录界面就清空所有session
			
			$front = D("Front");
			$fid = 1;
			$resultByFront = $front -> where('fid = '.$fid) -> find();
			$this -> assign('resultByFront', $resultByFront);
			
			$this -> display();
		}
    }
    
    public function add() {
    
		//判断用户是否登录
		if(isset($_SESSION['name'])) {
		
		//两个逻辑：展示表单、收集表单
		
			$ShopList = D("Articles");	//实例化Model
			if(!empty($_POST)) {
			
				//判断附件是否有上传 如果有则实例化UpLoad，把附件上传到服务器指定位置
				//然后把附件的路径名或得到，存入$_POST
				if(!empty($_FILES)) {
					$config = array(
						'rootPath'	=>	'./public/',
						'savePath'	=>	'upload/shop/', //保存路径
						'exts'	=>	array('jpg', 'gif', 'png', 'jpeg', 'bmp'),
					);
					$upload = new \Think\Upload($config);
					$upload1 = new \Think\Upload($config);
					$z = $upload -> uploadOne($_FILES['img']);
					$z1 = $upload1 -> uploadOne($_FILES['logo']);
					if(!$z) {
						$this -> error( $upload->getError(),'javascript:history.back();');
					} else {
						$img = $z['savepath'].$z['savename'];
						$_POST['img'] = $img;
					}
					if($z1) {
						$logo = $z1['savepath'].$z1['savename'];
						$_POST['logo'] = $logo;
					}
				}
				
				$_POST['author'] = $_SESSION['id'];	//把添加商铺的管理员id记录下
				
				$ShopList -> create();
	//			print_r($ShopList);
				$z = $ShopList -> add();	//$z保存添加成功与否的结果
				
				if($z) {
					$this -> success("添加店铺成功！", U('index'));
				} else {
					$this -> error("添加店铺失败！",'javascript:history.back();');
				}

			} else {
				$front = D("Front");
				$Type = D("Type");
				
				$fid = 1;
				$resultByFront = $front -> where('fid = '.$fid) -> find();
				
				$TypeResult = $Type -> select();
				
				$this -> assign('typeResult', $TypeResult);
				$this -> assign('resultByFront', $resultByFront);
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
			
				$this -> display();
			}
		
		} else {
			$this -> error("非法访问，请登录后重试！", U('login'));
		}
    }
    
    public function upd($id = -1) {
    
		//判断用户是否登录
		if(isset($_SESSION['name'])) {
		
			//两个逻辑：展示表单、收集表单
			$ShopList = D("Articles");	//实例化Model
			$Type = D("Type");
			
			if(!empty($_POST)) {
				//判断附件是否有上传 如果有则实例化UpLoad，把附件上传到服务器指定位置
				//然后把附件的路径名或得到，存入$_POST
				if(!empty($_FILES)) {
					$config = array(
						'rootPath'	=>	'./public/',
						'savePath'	=>	'upload/shop/', //保存路径
						'exts'	=>	array('jpg', 'gif', 'png', 'jpeg', 'bmp'),
					);
					$upload = new \Think\Upload($config);
					$upload1 = new \Think\Upload($config);
					$z = $upload -> uploadOne($_FILES['img']);
					$z1 = $upload1 -> uploadOne($_FILES['logo']);
					if($z) {
						$img = $z['savepath'].$z['savename'];
						$_POST['img'] = $img;
					}
					if($z1) {
						$logo = $z1['savepath'].$z1['savename'];
						$_POST['logo'] = $logo;
					}
				}			
				
				$ShopList -> create();
				$z = $ShopList -> save();	//$z保存更新成功与否的结果
				
				if($z) {
					$this -> success("保存店铺信息成功！",U('index'));
				} else {
					$this -> error("保存店铺信息失败！",U('index'));
				}
				
			} else {
				if(!empty($_GET)) {
					$result = $ShopList -> where('id = '.$id) -> find();
					$TypeResult = $Type -> select();
					if(!empty($result)) {
						//把数据assign到模板
						$this -> assign('typeResult', $TypeResult);
						$this -> assign('result', $result);	
						
						$this -> assign('id', $_SESSION['id']);	
						$this -> assign('name', $_SESSION['name']);	
						$this -> assign('role', $_SESSION['role']);	
						
						if($result['author'] != $_SESSION['id'] && $_SESSION['role'] != 1) $this -> error('非法访问！', 'javascript:history.back();');	//判断字段author的值是否等于当前登录用户的id，防止非法访问
						
						$front = D("Front");
						$fid = 1;
						$resultByFront = $front -> where('fid = '.$fid) -> find();
						$this -> assign('resultByFront', $resultByFront);
						
						$this -> display();
					} else {
						$this -> error('非法访问！', 'javascript:history.back();');
					}
				} else {
					$this -> error('非法访问！', 'javascript:history.back();');
				}
			}
			
		} else {
			$this -> error("非法访问，请登录后重试！", U('login'));
		}
    }
    
    public function del($id = -1) {
    
		//判断用户是否登录
		if(isset($_SESSION['name'])) {
			
			//实例化Model
			$ShopList = D("Articles");	
			$Type = D("Type");
			if(!empty($_POST)) {
				$ShopList -> create();
				$z = $ShopList -> delete();	//$z保存删除成功与否的结果
				
				if($z) {
					$this -> success("删除店铺信息成功！",U('index'));
				} else {
					$this -> error("删除店铺信息失败！",U('index'));
				}
			} else {
				if(!empty($_GET)) {
					$TypeResult = $Type -> field('tid, typename') -> select();
					$result = $ShopList -> where('id = '.$id) -> find();
					
					//select后的结果集的商铺类别id字段与商铺类别表的类别名匹配
					foreach ($result as $key => $value) {
						foreach ($TypeResult as $key0 => $value0) {
							if($result['typeid'] == $value0['tid']) $result['typename'] = $value0['typename']; 
						}
						if(empty($result['typename'])) $result['typename'] = '未分类';
					}
					
					if(!empty($result)) {
						$this -> assign('result', $result);	//把数据assign到模板
						
						if($result['author'] != $_SESSION['id'] && $_SESSION['role'] != 1 ) $this -> error('非法访问！', 'javascript:history.back();');	//判断字段author的值是否等于当前登录用户的id，防止非法访问
						
						$this -> assign('id', $_SESSION['id']);	
						$this -> assign('name', $_SESSION['name']);	
						$this -> assign('role', $_SESSION['role']);	
						$front = D("Front");
						$fid = 1;
						$resultByFront = $front -> where('fid = '.$fid) -> find();
						$this -> assign('resultByFront', $resultByFront);
						
						$this -> display();
					} else {
						$this -> error('非法访问！', 'javascript:history.back();');
					}
				} else {
					$this -> error('非法访问！', 'javascript:history.back();');
				}
			}
			
		} else {
			$this -> error("非法访问，请登录后重试！", U('login'));
		}
	}
	
	//这个方法应属Manager控制器的，但是为了跳转便捷拉来Index控制器了
	public function updpsd() {
		if(isset($_SESSION['name'])) {
			if(!empty($_POST)) {
				//判断密码与确认密码是否一致，不一致就提示再返回，为空也返回
				if(empty($_POST['password']) || empty($_POST['password0'])) {$this -> error('新密码不能为空！', U('index'));}
				if($_POST['password'] != $_POST['password0']) {$this -> error('密码与确认密码不一致！', U('index'));}
				
				//添加当前用户的id,name值到$_POST
				$_POST['id'] = $_SESSION['id'];
				$_POST['name'] = $_SESSION['name'];
				
				//将确认密码unset掉
				unset($_POST['password0']);

                //密码要进行MD5加密
                $_POST['password'] = MD5($_POST['password']);
				
				$Manager = new \Model\ManagerModel();
				$Manager -> create();
				$z = $Manager -> save();
				if(!$z) {
					$this -> error($Manager -> getError().'！', U('index'));
				} else {
					$this -> success("修改密码成功！",U('index'));
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
				$this -> error("非法访问，请登录后重试！", U('Login'));
		}
	}
	
	public function test() {
		echo "test";
	}
}