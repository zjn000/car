<?php
namespace Home\Model;
use Common\Model\BaseModel;
/**
 * ModelName
 */
class UsersModel extends BaseModel{
   public function getList()
   {
   		return $this->select();
   }
}
