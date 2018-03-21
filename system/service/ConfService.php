<?php
/**
 * Created by PhpStorm.
 * User: alan.luo
 * Date: 2017/10/19
 * Time: 19:18
 */

class ConfService
{
    public function update($name,$value)
    {
        return $GLOBALS['db']->query("update ".DB_PREFIX."conf set content = '".$value."' where name='".$name."'");
    }

    public function getRow($condition)
    {
        $sql = "select * from " . DB_PREFIX . "conf where 1 ";
        if($condition){
            $sql .= " and ".$condition;
        }
        $data = $GLOBALS['db']->getRow($sql);
        return $data;
    }

    public function getAll($condition)
    {
        $sql = "select * from " . DB_PREFIX . "conf where is_effect=1 ";
        if($condition){
            $sql .= " and ".$condition;
        }
        $data = $GLOBALS['db']->getAll($sql." order by id asc");
        return $data;
    }

}