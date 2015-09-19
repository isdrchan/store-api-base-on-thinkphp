<?php
namespace Api\Controller;
use Think\Controller;

class UserController extends Controller {

    /**
     * 用户登录验证并返回
     * @param null $email 邮箱
     * @param null $password 密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function login($email = null, $password = null) {

        $User = D('User');

        if($email == null || $password == null) {
            $array['status'] = -1;
            $array['msg'] = "邮箱地址或密码不能为空";
            echo json_encode($array, JSON_UNESCAPED_SLASHES);	//JSON_UNESCAPED_SLASHES使url不转义
            exit;
        }
        if($result = $User -> checkEmailPassword($email, $password)) {
            //更新登录时间和ip
            $data['loginip'] = get_client_ip();
            $data['logintime'] = date('Y-m-d H:i:s',time());
            $User -> where('uid='.$result['uid']) -> save($data);

            $token = $User -> createToken($result); //创建token
            $array['status'] = 0;
            $array['msg'] = "登录成功";
            $array['token'] = $token;
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            $array['status'] = -2;
            $array['msg'] = "邮箱地址或密码错误";
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            exit;
        }

    }

    /**
     * 用户注册
     * @param null $username 用户名
     * @param null $email   邮箱
     * @param null $password 密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function register($username = null, $email = null, $password = null) {
		$User = D('User');
		//$User -> create()，空参数不能收集get请求的键值对，所以要判断传过来的值的请求类型再作处理
        $result = isset($_GET['username']) ? $User -> create($_GET) : $User -> create();
        
        if (!$result){
            $array['status'] = -1;
            $array['msg'] = $User->getError();
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            $User -> loginip = get_client_ip();
            $User -> regtime = date('Y-m-d H:i:s',time());
            $data['uid'] = $User -> add();  //注册成功返回主键
            $data['logintime'] = date('Y-m-d H:i:s',time());
            
            $token = $User -> createToken($data); //创建token

            $array['status'] = 0;
            $array['msg'] = "注册成功";
            $array['token'] = $token;
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    /**
     * 获取用户信息
     * @param null $token
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_user_info($token = null) {

        $User = D('User');
        $result = $User -> checkTokenAndEchoInfo($token);
        $array['status'] = 0;
        $array['msg'] = "获取用户信息成功";
        $array['data'] = $result;
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * 用户注册
     * @param null $token 令牌
     * @param null $old_password   原密码
     * @param null $password 新密码
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function modify_password($token = null, $old_password = null, $password = null) {
        
        $User = D('User');
        $resultByCheckToken = $User -> checkTokenAndEchoInfo($token);
        $uid = $resultByCheckToken['uid'];
        if($password == null) {
            $array['status'] = -1;
            $array['msg'] = "原密码或新密码不能为空";
        } else {
            $resultByMidifyPassword = $User -> modifyPassword($uid, $old_password, $password);
            if($resultByMidifyPassword) {
                $array['status'] = 0;
                $array['msg'] = "修改密码成功";
            } else {
                $array['status'] = -2;
                $array['msg'] = "原密码错误或原密码与新密码相同";
            }
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
}