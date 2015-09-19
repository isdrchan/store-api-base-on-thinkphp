<?php

namespace Admin\Controller;
use Think\Controller;

class CommentController extends Controller {

    public function _initialize() {
        if(isset($_SESSION['name']) && $_SESSION['role'] == 1) {
            $front = D("Front");
            $this -> assign('id', $_SESSION['id']);
            $this -> assign('name', $_SESSION['name']);
            $this -> assign('role', $_SESSION['role']);
            $fid = 1;
            $resultByFront = $front -> where('fid = '.$fid) -> find();
            $this -> assign('resultByFront', $resultByFront);
        } else {
            $this -> error("非法访问，你不是超级管理员！", U('Index/index'));
        }
    }

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:base");
    }

    public function index($keyword = "") {
        $comment = D("Api/Comment");

        $result = $comment -> getCommentByKeyword($keyword);
        $this -> assign('result', $result);
        $this -> display();
    }

    public function del($id) {
        $comment = D("Api/Comment");

        if($comment -> delete($id)) {
            $this -> success("删除评论成功！", U('index'));
        } else {
            $this -> error("删除评论失败！", 'javascript:history.back();');
        }
    }


}