#一个轮盘抽奖的PHP源代码

#含前端文件
#含sql数据库文件

主框架文件
http://www.shop.com/?m=wap&ctl=index&act=test&ab=1&ac=2
m=模块
ctl=控制器
act=方法入口

http://www.shop.com/wap/index/test/?ab=1&ac=2
wap:模块
index:控制器
test:方法入口
?=*** :get参数


如果主站不是在 / 根目录下 不可以使用 pathinfo模式

{function name="to_date" v="$deal.create_time" f="Y-m-d"}
{foreach from="$deal_list" item="deal"}
{lang v="$module"}
{wap_url a="index" r="goods#index" p="cate_id=1057"}
{wap_url a="index" r="cart"}

//$page = new Page(ceil(count($list)/10), 10);
        
//$testmodel = Model('test');
//$list = $testmodel->getMemberName(array('member_id'=>array('lt',20)));
//$testLogic = Logic('test');
//$list = $testLogic->getMemberName(array('member_id'=>array('lt',20)));

{function name="formatImg" s="$list.goods_image" t="1" u="$list.store_id"}

formatImg('25_04986791499835621.jpg',1,25);

array('store_id' => $store_id,'goods_salenum'=>array('gt', 0))




