<?php
// +----------------------------------------------------------------------
// |  lanxinFrame
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.lanxinbase.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Alan(341455770@qq.com)
// +----------------------------------------------------------------------
if (!defined('APP_Alan')) {
	exit ( 'Access Denied!' );
}


class Page  {
    // 起始行数
    public $firstRow	;
    // 列表每页显示行数
    public $listRows	;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    //protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页栏每页显示的页数
    protected $rollPage   ;
	// 分页显示定制
    protected $config  =	array('prev'=>'&laquo;','next'=>'&raquo;','first'=>'第一页','last'=>'最后一页','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalPages  总页数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalPages,$listRows,$parameter='') {
        $this->parameter = $parameter;
        $this->rollPage = 3;
        $this->listRows = !empty($listRows)?$listRows:10;
        $this->totalPages = $totalPages;//ceil($this->totalRows/$this->listRows); = 总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = intval($_GET['page'])?intval($_GET['page']):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

   private function get_page_link($url,$page)
    {

    		if(substr($url,-1)=="?")
	    	$url.="page=".$page;
	    	elseif(strpos($url,'?')&&substr($url,-1)!="&")
	    	$url.="&page=".$page;
	    	elseif(strpos($url,'?')&&substr($url,-1)!="?")
	    	$url.="page=".$page;
	    	elseif(!strpos($url,'?'))
	    	$url.="?page=".$page;
	    	else
	    	$url.="?&page=".$page;

    	return $url;
    }
    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalPages || 1==$this->totalPages) return '';
        $p = 'page';
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;

        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }

       // if(app_conf("URL_MODEL")==1)$url = $GLOBALS['current_url'];
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<nav><ul class='pagination'><li><a href='".$this->get_page_link($url,$upRow)."' class='Previous'><span aria-hidden=\"true\">".$this->config['prev']."</span></a></li>";
        }else{
            $upPage="<nav><ul class='pagination'><li><a href='' class='disabled' aria-label=\"Previous\"><span aria-hidden=\"true\">".$this->config['prev']."</span></a></li>";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<li><a href='".$this->get_page_link($url,$downRow)."' class='Previous'><span aria-hidden=\"true\">".$this->config['next']."</span></a></li></ul>";
        }else{
            $downPage="<li><a href='' class='disabled' aria-label=\"Previous\"><span aria-hidden=\"true\">".$this->config['next']."</span></a></li></ul>";
        }

        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "";// "<a href='".$this->get_page_link($url,$preRow)."' class='btn_next'>上".$this->rollPage."页</a>";
            $theFirst = "";// "<a href='".$this->get_page_link($url,1)."' class='btn_first'>".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            if($nextRow>$this->totalPages)$nextRow = $this->totalPages;
            $theEndRow = $this->totalPages;
            $nextPage = "";// "<a href='".$this->get_page_link($url,$nextRow)."' class='btn_new'>下".$this->rollPage."页</a>";
            $theEnd = "";// "<a href='".$this->get_page_link($url,$theEndRow)."' class='btn_last'>".$this->config['last']."</a>";
        }
        // 1 2 3 4 5

        $linkPage = '';
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<li><a href='".$this->get_page_link($url,$page)."'>&nbsp;".$page."&nbsp;</a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<li class='active'><a>".$page."</a></li>";
                }
            }
        }

        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$theFirst,$upPage,$prePage,$linkPage,$nextPage,$downPage,$theEnd),$this->config['theme']);
       	    return $pageStr;
    }

}
?>