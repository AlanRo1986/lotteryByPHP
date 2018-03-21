<?php echo $this->fetch('inc/header.html'); ?>

<?php if ($this->_var['password']): ?>
<div>
    <h2>系统配置</h2>

    <div class="table-responsive">
        <form method="post" action="#" enctype="multipart/form-data" id="confForm">
            <table class="table table-bordered">
                <tbody>
                <?php $_from = $this->_var['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                <tr>
                    <td style="width: 300px;text-align: right"> <?php echo $this->_var['item']['tip']; ?>： </td>
                    <td>
                        <?php if ($this->_var['item']['input_type'] == 0): ?>
                        <input type="text" name="<?php echo $this->_var['item']['name']; ?>" class="form-control" value="<?php echo $this->_var['item']['content']; ?>">
                        <?php elseif ($this->_var['item']['input_type'] == 1): ?>
                        <?php if ($this->_var['item']['name'] == 'password'): ?>
                            <input type="password" name="<?php echo $this->_var['item']['name']; ?>" class="form-control" value="" placeholder="不修改管理密码请留空,密码长度必须大于5">
                        <?php else: ?>
                            <input type="password" name="<?php echo $this->_var['item']['name']; ?>" class="form-control" value="<?php echo $this->_var['item']['content']; ?>">
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <tr>
                    <td class="textr"></td>
                    <td><button type="submit" class="btn btn-primary" style="text-align: center;width: 200px">保存</button></td>
                </tr>
                </tbody>
            </table>

        </form>
    </div>
</div>
<script>
    $(function () {
        app.event("#confForm","submit",function () {
            app.ajax("/conf/save",$("#confForm").serialize(),function (e) {
                alert(e.info);
            }).post();
            return false;
        })
    })
</script>


<?php else: ?>
<div style="margin-top: 20%;text-align: center;">
    <h2 style="margin-left: -80px;">请输入密码</h2>
    <form action="/conf" method="get" id="queryForm">
        <div class="form-inline">
            <input type="password" name="password" class="form-control" value="" placeholder="请输入管理密码">
            <input type="submit" value="查询" class="btn btn-primary">
        </div>
    </form>
</div>
<?php endif; ?>

<?php echo $this->fetch('inc/footer.html'); ?>