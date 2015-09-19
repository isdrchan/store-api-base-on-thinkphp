<?php 

namespace Model;
use Think\Model;

class ManagerModel extends Model {
	public function checkNamePassword($name, $password) {
		$result = $this -> getByname($name);
		
		if($result != NULL) {
			if($result['password'] != $password) {
				return false;
			} else {
				return $result;
			}
			
		} else {
			return false;
		}
	}
	
	protected $_validate	=	array(
		//用户名必须填写
		array('name', 'require', '用户名不能为空'),
		array('password', 'require', '密码不能为空'),
		array('password0', 'require', '确认密码不能为空'),
		array('password0', 'password', '密码与确认密码不一致', 0, 'confirm'),
	);
//`		protected $patchValidate	=	ture;
	
	
}