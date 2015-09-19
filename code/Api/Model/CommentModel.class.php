<?php 

namespace Api\Model;
use Think\Model;

class CommentModel extends Model {

    public function addComment($uid, $aid, $content) {
        $Articles = D('Api/Articles');
        
        $data['uid'] = $uid;
        $data['aid'] = $aid;
        $data['content'] = $content;
        $data['time'] = date('Y-m-d H:i:s',time());
        $data['ip'] = get_client_ip();
        
        //添加评论后，需要在Articles表aid相应的商铺的comment_count字段+1，统计评论数
        $Articles -> where('id='.$aid ) -> setInc('comment_count');
        
        return $this -> add($data);
    }

    public function getCommentByKeyword($keyword) {
        $User = D('Api/User');
        $Articles = D('Api/Articles');

        $data['content'] = array('like','%'.$keyword.'%');
        $result = $this -> where($data) -> select();
        foreach($result as $key=>$value) {
            $result[$key]['username'] = $User -> getUsernameByUid($result[$key]['uid']);
            $result[$key]['shopname'] = $Articles -> getShopnameByid($result[$key]['aid']);
        }
        return $result;
    }

    public function getCommentByAid($aid) {
        $User = D('User');
        $Articles = D('Articles');

        $result = $this -> where("aid=".$aid) -> select();
        foreach($result as $key=>$value) {
            $result[$key]['username'] = $User -> getUsernameByUid($result[$key]['uid']);
//            $result[$key]['shopname'] = $Articles -> getShopnameByid($result[$key]['aid']);
        }
        return $result;
    }

    public function getCommentByUid($uid) {
        $User = D('User');
        $Articles = D('Articles');

        $result = $this -> where("uid=".$uid) -> select();
        foreach($result as $key=>$value) {
//            $result[$key]['username'] = $User -> getUsernameByUid($result[$key]['uid']);
            $result[$key]['shopname'] = $Articles -> getShopnameByid($result[$key]['aid']);
        }
        return $result;
    }
}