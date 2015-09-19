<?php
namespace Api\Controller;
use Think\Controller;

class ArticlesController extends Controller {

    /**
     * 获取商铺列表（可根据关键词检索）
     * @param null $keyword 关键词
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_articles_list($keyword = null, $building = null) {
        $Articles = D('Articles');
        $result = $Articles -> selectArticlesList($keyword, $building);
        if(!$result) {
            $array['status'] = 1;
            $array['msg'] = "无匹配数据";
        } else {
            $array['status'] = 0;
            $array['msg'] = "成功获取商铺列表";
            $array['data'] = $result;
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 获取商铺信息
     * @param null $id 商铺id
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_articles($id = null) {
        $Articles = D('Articles');
        $result = $Articles -> selectArticles($id);
        if(!$id) {
            $array['status'] = -1;
            $array['msg'] = "id不能为空";
        } elseif(!$result) {
            $array['status'] = 1;
            $array['msg'] = "无匹配数据";
        } else {
            $array['status'] = 0;
            $array['msg'] = "成功获取商铺信息";
            $array['data'] = $result;
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * 获取商铺列表（根据建筑物标志）
     * @param null $keyword 关键词
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function get_articles_list_by_building($building = null) {
        $Articles = D('Articles');
        $result = $Articles -> selectArticlesListByBuilding($building);
        if(!$result) {
            $array['status'] = 1;
            $array['msg'] = "无匹配数据";
        } else {
            $array['status'] = 0;
            $array['msg'] = "成功获取商铺列表";
            $array['data'] = $result;
        }
        echo json_encode($array, JSON_UNESCAPED_SLASHES);
        exit;
    }
}