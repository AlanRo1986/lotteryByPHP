#һ�����̳齱��PHPԴ����

#��ǰ���ļ�
#��sql���ݿ��ļ�

������ļ�
http://www.shop.com/?m=wap&ctl=index&act=test&ab=1&ac=2
m=ģ��
ctl=������
act=�������

http://www.shop.com/wap/index/test/?ab=1&ac=2
wap:ģ��
index:������
test:�������
?=*** :get����


�����վ������ / ��Ŀ¼�� ������ʹ�� pathinfoģʽ

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




