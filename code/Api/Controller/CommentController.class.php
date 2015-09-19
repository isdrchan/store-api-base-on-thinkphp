<?php
namespace Api\Controller;
use Think\Controller;

class CommentController extends Controller {

    /**
     * 添加评论
     * @param null $token 令牌
     * @param null $id  商铺id
     * @param null $content 评论内容
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function add($token = null, $id = null, $content = null) {
        $User = D('User');
        $Comment = D('Comment');

        $user_result = $User -> checkTokenAndEchoInfo($token);
        if($content) {
            $Comment -> addComment($user_result['uid'], $id, $content);
            $array['status'] = 0;
            $array['msg'] = "评论成功";
        } else {
            $array['status'] = -1;
            $array['msg'] = "评论内容为空";
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 删除评论
     * @param null $token   令牌
     * @param null $id  评论id
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function del($token = null, $id = null) {
        $User = D('User');
        $Comment = D('Comment');

        $user_result = $User -> checkTokenAndEchoInfo($token);
        $comment_result = $Comment -> getBycid($id);
        print_r($comment_result);
        if($user_result['uid'] != $comment_result['uid']) {
            $array['status'] = -1;
            $array['msg'] = "只能删除自己的评论";
        } else {
            $Comment -> delComment($id);
            $array['status'] = 0;
            $array['msg'] = "删除评论成功";
            
            //删除评论后，需要在Articles表aid相应的商铺的comment_count字段-1，统计评论数
            $Articles -> where('id='.$aid ) -> setDec('comment_count');
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 获取评论列表（通过商铺id）
     * @param null $aid
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_list_by_aid($aid = null) {
        $Comment = D('Comment');

        $result = $Comment -> getCommentByAid($aid);
        $array['status'] = 0;
        $array['msg'] = "成功获取评论列表";
        $array['data'] = $result;
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 获取评论列表（通过用户令牌）
     * @param null $token   令牌
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_list_by_token($token = null) {
        $User = D('User');
        $Comment = D('Comment');

        $user_result = $User -> checkTokenAndEchoInfo($token);
        $comment_result = $Comment -> where('uid='.$user_result['uid']) -> select();
        $array['status'] = 0;
        $array['msg'] = "成功获取评论列表";
        $array['data'] = $comment_result;
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
}