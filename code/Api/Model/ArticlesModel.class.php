<?php

namespace Api\Model;
use Think\Model;

define("FILE_URL", "http://".$_SERVER['HTTP_HOST']."/public/".C('__PUBLIC__'));

class ArticlesModel extends Model {

    public function getShopnameByid($id) {
        $result = $this -> getByid($id);
        return $result ? $result['shopname'] : null;
    }

    /**
     * 获取商铺列表(可根据关键词select或者指定的建筑物标志building)
     * @param null $keyword
     * @return mixed
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function selectArticlesList($keyword = null, $building = null) {
        $Type = D('Type');

        $data['shopname'] = array('like','%'.$keyword.'%');
        if($building) $data['building'] = $building;    //有building传入才加入搜索条件
        $result = $this -> where($data) -> field("id,shopname,shopid,phone,introduce,logo,typeid,comment_count") -> order('id desc') -> select();
        foreach($result as $key=>$value) {
            $result[$key]['logo'] = FILE_URL.$result[$key]['logo'];
            $result[$key]['type'] = $Type -> typeIdToTypeName($result[$key]['typeid']);
            $result[$key]['introduce'] = mb_substr($result[$key]['introduce'], 0, 30, 'utf-8');
            unset($result[$key]['typeid']);
        }
        return $result;
    }
    
    /**
     * 获取商铺列表(根据建筑物标志)
     * @param null $keyword
     * @return mixed
     * Created by Dr.Chan<cynmsg@gmail.com>
     */
    public function selectArticlesListByBuilding($building = null) {
        $Type = D('Type');

        $data['building'] = $building;
        $result = $this -> where($data) -> field("id,shopname,shopid,phone,introduce,logo,typeid,comment_count,px,py") -> order('id desc') -> select();        
        foreach($result as $key=>$value) {
            $result[$key]['logo'] = FILE_URL.$result[$key]['logo'];
            $result[$key]['type'] = $Type -> typeIdToTypeName($result[$key]['typeid']);
            $result[$key]['introduce'] = mb_substr($result[$key]['introduce'], 0, 30, 'utf-8');
            unset($result[$key]['typeid']);
        }
        return $result;
    }

    public function selectArticles($id = null) {
        $Type = D('Type');

        $result = $this -> getByid($id);
        if(!$result) return;
        $result['logo'] = FILE_URL.$result['logo'];
        $result['img'] = FILE_URL.$result['img'];
        $result['type'] = $Type -> typeIdToTypeName($result['typeid']);
        unset($result['typeid']);
        return $result;
    }

    public function isArticles($id) {
        $result = $this -> getByid($id);
        return $result ? true : false;
    }

}