<?php

namespace Admin\Controller;
use Think\Controller;

class FrontController extends Controller {
    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:base");
    }

    public function index() {
		//只有超级管理员才能管理主页
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$front = D("Front");
			
			if(!empty($_POST)) {
				
				$_POST['fid'] = 1;
				
				$front -> create();
				$z = $front -> save();
				
				if($z) {
					$this -> success("主页信息修改成功！",U('index'));
				} else {
					$this -> error("主页信息修改失败！",U('index'));
				}
			} else {
				$fid = 1;
				$result = $front -> where('fid = '.$fid) -> find();
				
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$this -> assign('result', $result);
				$this -> assign('resultByFront', $result);
				$this -> display();
			}
			
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function location() {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$front = D("Front");
			
			if(!empty($_POST)) {
				$_POST['fid'] = 1;
				
				$front -> create();
				$z = $front -> save();
				if($z) {
					$this -> success("主页信息修改成功！",U('location'));
				} else {
					$this -> error("主页信息修改失败！",U('location'));
				}
			} else {
				$fid = 1;
				$result = $front -> where('fid = '.$fid) -> find();
				
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$this -> assign('result', $result);
				$this -> assign('resultByFront', $result);
				
				$this -> display();
			}
			
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function setimg() {
				if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$front = D("Front");
			
			if(!empty($_POST)) {
				//判断附件是否有上传 如果有则实例化UpLoad，把附件上传到服务器指定位置
				//然后把附件的路径名或得到，存入$_POST
				if(!empty($_FILES)) {
					$config = array(
						'rootPath'	=>	'./public/',
						'savePath'	=>	'upload/logo/', //保存路径
						'exts'	=>	array('jpg', 'gif', 'png', 'jpeg', 'bmp'),
					);
					$upload0 = new \Think\Upload($config);
					$upload1 = new \Think\Upload($config);
					$upload2 = new \Think\Upload($config);
					$upload3 = new \Think\Upload($config);
					$upload4 = new \Think\Upload($config);
					$z0 = $upload0 -> uploadOne($_FILES['small_img']);
					$z1 = $upload1 -> uploadOne($_FILES['img1']);
					$z2 = $upload2 -> uploadOne($_FILES['img2']);
					$z3 = $upload3 -> uploadOne($_FILES['img3']);
					$z4 = $upload4 -> uploadOne($_FILES['img4']);
					if($z0) {
						$img0 = $z0['savepath'].$z0['savename'];
						$_POST['small_img'] = $small_img;
					}
					if($z1) {					
						$img1 = $z1['savepath'].$z1['savename'];
						$_POST['img1'] = $img1;
					}
					if($z2) {					
						$img2 = $z2['savepath'].$z2['savename'];
						$_POST['img2'] = $img2;
					}
					if($z3) {					
						$img3 = $z3['savepath'].$z3['savename'];
						$_POST['img3'] = $img3;
					}
					if($z4) {					
						$img4 = $z4['savepath'].$z4['savename'];
						$_POST['img4'] = $img4;
					}
				}
				
				$_POST['fid'] = 1;
				
				$front -> create();
				$z = $front -> save();
				
				if($z) {
					$this -> success("主页信息修改成功！",U('setimg'));
				} else {
					$this -> error("主页信息修改失败！",U('setimg'));
				}
			} else {
				$fid = 1;
				$result = $front -> where('fid = '.$fid) -> find();
				
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$this -> assign('result', $result);
				$this -> assign('resultByFront', $result);
				
				$this -> display();
			}
			
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function box() {
				if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$front = D("Front");
			
			if(!empty($_POST)) {
				//判断附件是否有上传 如果有则实例化UpLoad，把附件上传到服务器指定位置
				//然后把附件的路径名或得到，存入$_POST
				if(!empty($_FILES)) {
					$config = array(
						'rootPath'	=>	'./public/',
						'savePath'	=>	'upload/logo/', //保存路径
						'exts'	=>	array('jpg', 'gif', 'png', 'jpeg', 'bmp'),
					);
					$upload0 = new \Think\Upload($config);
					$upload1 = new \Think\Upload($config);
					$upload2 = new \Think\Upload($config);
					$upload3 = new \Think\Upload($config);
					$upload4 = new \Think\Upload($config);
					$z0 = $upload0 -> uploadOne($_FILES['small_img']);
					$z1 = $upload1 -> uploadOne($_FILES['img1']);
					$z2 = $upload2 -> uploadOne($_FILES['img2']);
					$z3 = $upload3 -> uploadOne($_FILES['img3']);
					$z4 = $upload4 -> uploadOne($_FILES['img4']);
					if($z0) {
						$img0 = $z0['savepath'].$z0['savename'];
						$_POST['small_img'] = $small_img;
					}
					if($z1) {					
						$img1 = $z1['savepath'].$z1['savename'];
						$_POST['img1'] = $img1;
					}
					if($z2) {					
						$img2 = $z2['savepath'].$z2['savename'];
						$_POST['img2'] = $img2;
					}
					if($z3) {					
						$img3 = $z3['savepath'].$z3['savename'];
						$_POST['img3'] = $img3;
					}
					if($z4) {					
						$img4 = $z4['savepath'].$z4['savename'];
						$_POST['img4'] = $img4;
					}
				}
				
				$_POST['fid'] = 1;
				
				$front -> create();
				$z = $front -> save();
				
				if($z) {
					$this -> success("主页信息修改成功！",U('box'));
				} else {
					$this -> error("主页信息修改失败！",U('box'));
				}
			} else {
				$fid = 1;
				$result = $front -> where('fid = '.$fid) -> find();
				
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$this -> assign('result', $result);
				$this -> assign('resultByFront', $result);
				
				$this -> display();
			}
			
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}
	
	public function theme() {
		if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
			$front = D("Front");
			
			if(!empty($_POST)) {
				$_POST['fid'] = 1;
				$front -> create();
				$z = $front -> save();
				if($z) {
					$this -> success("主页信息修改成功！",U('theme'));
				} else {
					$this -> error("主页信息未更改！",U('theme'));
				}
			} else {
                //遍历存放主题模板的目录取其文件名
                $dir = opendir('./Home/View/Index');
                $i = $j = $k= 0;
                while (($file = readdir($dir)) !== false)
                {
                    if($file=='.'||$file=='..') continue;
                    if(substr($file, 0, 6) == 'index_') {
                        $index[$i] = substr($file, 0, -5);
                        $i++;
                    }
                    else if(substr($file, 0, 9) == 'shoplist_') {
                        $shoplist[$j] = substr($file, 0, -5);
                        $j++;
                    }
                    else if(substr($file, 0, 8) == 'article_') {
                        $article[$k] = substr($file, 0, -5);
                        $k++;
                    }
                }
                closedir($dir);

				$fid = 1;
				$result = $front -> where('fid = '.$fid) -> find();
				
				$this -> assign('id', $_SESSION['id']);	
				$this -> assign('name', $_SESSION['name']);	
				$this -> assign('role', $_SESSION['role']);	
				$this -> assign('result', $result);
				$this -> assign('resultByFront', $result);

                //主题
                $this -> assign('index', $index);
                $this -> assign('shoplist', $shoplist);
                $this -> assign('article', $article);

				$this -> display();
			}
			
		} else {
			$this -> error("非法访问，你不是超级管理员！", U('Index/index'));
		}
	}

    public function edit() {
        if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
            if($_POST['file_contents']){
                $fileName = $_POST['fileName'];
                $handle = fopen('./Home/View/Index/'.$fileName.'.html', "w");
                $text  = $_POST['file_contents'];
                if(fwrite($handle, $text) == FALSE){
                    $this -> error("保存失败！", 'javascript:history.back();');
                }else{
                    $this -> success("保存成功！", 'javascript:history.back();');
                }
            } else if($_GET) {
                $fileName = $_GET['file'];
                $this -> assign('fileName', $fileName);
                $this -> display();
            } else {
                error("非法访问！", 'javascript:history.back();');
            }
        } else {
            $this -> error("非法访问，你不是超级管理员！", 'javascript:history.back();');
        }
    }
}