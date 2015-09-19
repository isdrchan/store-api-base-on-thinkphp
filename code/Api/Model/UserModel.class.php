<?php 

namespace Api\Model;
use Think\Model;

define("ENCODE_KEY", "wifi!@#$"); //加密密钥

class UserModel extends Model {

    protected $_validate = array(
        array('username','require','用户名不能为空'),
        array('email','require','邮箱地址不能为空'),
        array('password','require','密码不能为空'),
        array('username','','用户名已经存在', 0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('email','email','邮箱地址不合法'), // 在新增的时候验证email字段是否合法和唯一
        array('email','','邮箱地址已经存在',0,'unique',1)
    );

    protected $_auto = array(
        array('password','md5', 3, 'function')    //对password字段在新增和编辑的时候使md5函数处理
    );

    public function getUsernameByUid($uid) {
        $result = $this -> getByuid($uid);
        return $result ? $result['username'] : null;
    }

	public function checkEmailPassword($email, $password) {
		$result = $this -> getByemail($email);

		if($result != NULL) {
			if($result['password'] != md5($password)) {
				return false;
			} else {
				return $result;
			}
			
		} else {
			return false;
		}
	}

    public function createToken($result) {
        $array['uid'] = $result['uid'];
        $array['logintime'] = $result['logintime'];
        $token = $this -> encrypt($array);
        $this -> where('uid='.$result['uid']) -> setField('token', $token);
        return $token;
    }

    public function checkToken($str) {
        $result = $this -> field("uid,username,email,regtime,logintime,loginip") -> getBytoken($str);
        return $result == NULL ? false : $result;
    }

    public function checkTokenAndEchoInfo($str) {
        if($str == null) {
            $array['status'] = -1;
            $array['msg'] = "token不能为空";
            echo json_encode($array, JSON_UNESCAPED_SLASHES);	//JSON_UNESCAPED_SLASHES使url不转义
            exit;
        }
        $result = $this -> checkToken($str);
        if(!$result) {
            $array['status'] = -2;
            $array['msg'] = "token过期或错误，请重新登录获取";
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            return $result;
        }
    }
    
    public function modifyPassword($uid, $old_password, $password) {
        $result = $this -> field('password') -> getByuid($uid);
        if($result['password'] != md5($old_password)) {
            return false;
        } else {
            $data['password'] = md5($password);
            $result = $this -> where('uid='.$uid) -> save($data);
            return $result;
        }
    }

    public function addFavorite($uid, $aid) {
        $result = $this -> field('favorite') -> getByuid($uid);
        $array = json_decode($result['favorite']);
        $array[] = $aid;
        $array = array_unique($array);   //去除重复值
        $data['favorite'] = json_encode($array);
        $this -> where('uid='.$uid) -> save($data);
    }

    public function checkFavorite($uid, $aid) {
        $result = $this -> field('favorite') -> getByuid($uid);
        $array = json_decode($result['favorite']);
        return in_array($aid, $array);
    }

    public function delFavorite($uid, $aid) {
        $result = $this -> field('favorite') -> getByuid($uid);
        $array = json_decode($result['favorite'], true);
        $key = array_search($aid, $array);
        unset($array[$key]);

        //防止转义的json字符串有键值对
        foreach($array as $key=>$value) {
            $new_array[] = $array[$key];
        }
        $data['favorite'] = json_encode($new_array, true);
        $this -> where('uid='.$uid) -> save($data);
    }

    public function getFavorite($uid) {
        $Articles = D('Articles');
        $result = $this -> field('favorite') -> getByuid($uid);
        $array = json_decode($result['favorite'], true);
        //根据user表中favorite字段的商铺id，select Articles表的相关信息
        foreach($array as $key=>$value) {
            $articles_array[] = $Articles -> selectArticles($array[$key]);
        }
        return $articles_array;
    }

    public function encrypt($data) {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, ENCODE_KEY, $prep_code, MCRYPT_MODE_ECB);
        return base64_encode($encrypt);
    }

    public function decrypt($str) {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, ENCODE_KEY, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }
}