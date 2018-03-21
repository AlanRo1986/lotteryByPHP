<?php
/**
 * Created by PhpStorm.
 * User: alan.luo
 * Date: 2017/10/19
 * Time: 19:18
 */

class LotteryService
{
    public function insert($arr){
        if($GLOBALS['db']->autoExecute("lottery",$arr)){
            return $GLOBALS['db']->insert_id();
        }
    }

    public function getRow($condition){
        $sql = "select * from ".DB_PREFIX."lottery where 1 and ";
        $sql .= $condition . " order by id desc limit 1";
        $data = $GLOBALS['db']->getRow($sql);
        return $data;
    }

    public function getAll($condition,$page){
        $sql = "select a.*,b.firstName,b.lastName,b.email,b.mobile from "
            .DB_PREFIX."lottery a left join ".DB_PREFIX."user b on a.userId = b.id where 1 ";
        if ($condition != null){
            $sql .= "and " . $condition;
        }
        $sql .= " order by a.id desc limit ".(($page - 1) * app_conf("PAGE_SIZE")) . "," . app_conf("PAGE_SIZE");
        $data = $GLOBALS['db']->getAll($sql);
        return $data;
    }

    public function getAllWinUser($condition,$page){
        $sql = "select b.firstName,b.lastName,b.email,b.mobile,a.goodsName from ".DB_PREFIX."user b left join ".DB_PREFIX."lottery a on b.id=a.userId where a.goodsName != '继续努力' ";
        if ($condition != null){
            $sql .= "and " . $condition;
        }
        $sql .= " order by b.id desc limit 10";
        $data = $GLOBALS['db']->getAll($sql);
        return $data;
    }

    public function getCount($condition){
        $sql = "select count(1) from ".DB_PREFIX."lottery where 1 ";
        if ($condition != null){
            $sql .= "and " . $condition;
        }
        $data = $GLOBALS['db']->getOne($sql);
        return $data;
    }

    public function update($sql)
    {
        return $GLOBALS['db']->query($sql);
    }

}