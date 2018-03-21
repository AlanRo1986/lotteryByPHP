<?php echo $this->fetch('inc/header.html'); ?>

<?php if ($this->_var['password']): ?>
<div class="table-responsive" >
    <h2>中奖名单</h2>
    <div style="    padding: 20px;">
        <form action="/user?password=<?php echo $this->_var['password']; ?>" method="get">
            <div class="form-inline">
                关键词:<input class="form-control" style="width: 200px" name="keywords" value="<?php echo $this->_var['keywords']; ?>" placeholder="可输入邮箱/手机/姓名">
                <input type="button" value="查询" onclick="queryByKeywords()" class="btn btn-default">

            </div>
        </form>
    </div>

    <?php if ($this->_var['data'] == false): ?>
    <div style="margin: 100px;color: #999999;text-align: center">Nothing...</div>
    <?php endif; ?>

    <table class="table table-bordered <?php if ($this->_var['data'] == false): ?>hide<?php endif; ?>">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>User Mobile</th>
                <th>Prize Name</th>
                <th>Prized Time</th>
                <th>Client IP</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_var['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
            <tr>
                <th scope="row"><?php echo $this->_var['item']['id']; ?></th>
                <td><?php echo $this->_var['item']['firstName']; ?> <?php echo $this->_var['item']['lastName']; ?></td>
                <td><?php echo $this->_var['item']['email']; ?></td>
                <td><?php echo $this->_var['item']['mobile']; ?></td>
                <td><?php echo $this->_var['item']['goodsName']; ?></td>
                <td><?php 
$k = array (
  'name' => 'to_date',
  'p' => $this->_var['item']['createTime'],
);
echo $k['name']($k['p']);
?></td>
                <td><?php echo $this->_var['item']['ipAddr']; ?></td>
                <td>
                    <?php if ($this->_var['item']['status'] == 0): ?>
                    <span class="label label-warning">new</span>
                    <?php else: ?>
                    <span></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($this->_var['item']['status'] == 0): ?>
                    <a href="#" onclick="onRead(<?php echo $this->_var['item']['id']; ?>)">get</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </tbody>
    </table>
    <div class="<?php if ($this->_var['data'] == false): ?>hide<?php endif; ?>">
        <div style="float: left;margin: 20px 0;">
            共 <span class="label label-info"><?php echo $this->_var['count']; ?></span> 条数据,
            <span class="label label-info"><?php echo $this->_var['pageCount']; ?></span> 页
        </div>
        <div style="float: right">
            <?php echo $this->_var['pages']; ?>
        </div>
    </div>

</div>
<?php else: ?>
<div style="margin-top: 20%;text-align: center;">
    <h2 style="margin-left: -80px;">请输入密码</h2>
    <form action="/user" method="get" id="queryForm">
        <div class="form-inline">
            <input type="password" name="password" class="form-control" value="" placeholder="请输入管理密码">
            <input type="submit" value="查询" class="btn btn-primary">
        </div>
    </form>
</div>
<?php endif; ?>

<script>
    function onRead(id) {
        app.ajax("/user/read",{id:id},function (e) {
            if(e.code == 1){
                location.reload();
            }
        }).post();
    }
    function queryByKeywords() {
        var a = $("input[name='keywords']").val();
        location.href = "/user?keywords="+a+"&password=<?php echo $this->_var['password']; ?>";
    }
</script>
<?php echo $this->fetch('inc/footer.html'); ?>