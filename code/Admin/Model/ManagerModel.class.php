<?php 

namespace Admin\Model;
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
}