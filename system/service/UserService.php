<?php
/**
 * Created by PhpStorm.
 * User: alan.luo
 * Date: 2017/10/19
 * Time: 19:18
 */

class UserService
{
    public function insert($userModel){
        if($GLOBALS['db']->autoExecute("user",$userModel)){
            return $GLOBALS['db']->insert_id();
        }
    }

    public function getRow($condition){
        $sql = "select * from ".DB_PREFIX."user where 1 and ";
        $sql .= $condition;
        $data = $GLOBALS['db']->getRow($sql);
        return $data;
    }

}