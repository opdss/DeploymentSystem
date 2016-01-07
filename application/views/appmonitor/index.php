<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>应用监控 - 我的监控列表</h3>
    </div><!--contenttitle-->
    <h5>可以对您提交的URL进行检测，当它们出现访问异常时用邮件报警通知您。</h5>
    <br>
    <div class="tableoptions">
        <button class="radius3" id="add_button">添加新的监控</button> &nbsp;
    </div><!--tableoptions-->
    <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
        <thead>
        <tr>
            <th class="head1">项目id</th>
            <th class="head0">项目名</th>
            <th class="head1 center">检查间隔</th>
            <th class="head0 center">邮件列表</th>
            <th class="head0 center">管理操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($tplVars['recordsArray'] as $value) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($value['id']); ?></td>
                <td><?php echo htmlspecialchars($value['name']); ?></td>
                <td><?php echo $value['checkinterval']?></td>
                <td><?php echo $value['maillist']?></td>
                <td class="center">
                    <a href="<?php echo site_url('appmonitor/editItem/'.$value['id'])?>" class="title">修改</a> &nbsp;
                    <a href="<?php echo site_url('appmonitor/manageTarget/'.$value['id'])?>" class="title">管理监控地址</a> &nbsp;
                    <a onclick="javascript:return delConfirm()" href="<?php echo site_url('appmonitor/delItem/'.$value['id'])?>" id="delete_href" class="title">删除</a>&nbsp;
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div><!--contentwrapper-->
<script>

    function delConfirm() {
        var res = confirm('确认要删除？（会附带删除该项目的所有监控地址，请谨慎操作！）');
        return res;
    }

    jQuery(function($) {
        $('#add_button').click(function() {
            window.location.href='<?php echo site_url('appmonitor/addItem')?>'
        });
    });
</script>
