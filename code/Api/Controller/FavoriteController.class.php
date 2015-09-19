<?php
namespace Api\Controller;
use Think\Controller;

class FavoriteController extends Controller {

    /**
     * 添加商铺收藏
     * @param null $token 令牌
     * @param null $id  商铺id
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function add($token = null, $id = null) {
        $User = D('User');
        $Articles = D('Articles');

        $result = $User -> checkTokenAndEchoInfo($token);
        if(!$Articles -> isArticles($id)) {
            $array['status'] = -1;
            $array['msg'] = "商铺不存在";
        } else {
            $User -> addFavorite($result['uid'], $id);
            $array['status'] = 0;
            $array['msg'] = "收藏商铺成功";
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 删除商铺收藏
     * @param null $token   令牌
     * @param null $id  商铺id
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function del($token = null, $id = null) {
        $User = D('User');
        $Articles = D('Articles');

        $result = $User -> checkTokenAndEchoInfo($token);
        if(!($User -> checkFavorite($result['uid'], $id))) {
            $array['status'] = -1;
            $array['msg'] = "商铺不存在于用户收藏";
        } else {
            $User -> delFavorite($result['uid'], $id);
            $array['status'] = 0;
            $array['msg'] = "取消收藏商铺成功";
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 获取用户收藏商铺列表
     * @param null $token 令牌
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_list($token = null) {
        $User = D('User');

        $result = $User -> checkTokenAndEchoInfo($token);
        $array['status'] = 0;
        $array['msg'] = "获取用户商铺收藏列表成功";
        $array['data'] = $User -> getFavorite($result['uid']);
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * 查询用户是否收藏该商铺
     * @param null $token 令牌
     * @param null $id  商铺id
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function check_favorite($token = null, $id = null) {
        $User = D('User');
        $Articles = D('Articles');

        $result = $User -> checkTokenAndEchoInfo($token);
        $result = $User -> checkTokenAndEchoInfo($token);
        if(!($User -> checkFavorite($result['uid'], $id))) {
            $array['status'] = -1;
            $array['msg'] = "商铺不存在于用户收藏";
        } else {
            $array['status'] = 0;
            $array['msg'] = "商铺存在于用户收藏";
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
}