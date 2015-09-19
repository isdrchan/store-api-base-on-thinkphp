<?php 

namespace Api\Model;
use Think\Model;

class TypeModel extends Model {

	public function typeIdToTypeName($tid) {
        $result = $this -> getBytid($tid);
        return $result ? $result['typename'] : null;
	}

}