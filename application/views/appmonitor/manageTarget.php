<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>监控地址列表</h3>
    </div><!--contenttitle-->
    <h5>可以对您提交的URL进行检测，当它们出现访问异常时用邮件报警通知您。</h5>
    <br>
    <div class="tableoptions">
        <button class="radius3" id="add_button">添加新的监控地址</button> &nbsp;
    </div><!--tableoptions-->
    <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
        <thead>
        <tr>
            <th class="head0">监控地址</th>
            <th class="head1 center">别名</th>
            <th class="head0 center">管理操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($tplVars['urlResult'] as $value) {
            ?>
            <tr>
                <td><?php echo $value['target']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td class="center">
                    <a href="<?php echo site_url('appmonitor/editTarget/'.$tplVars['id'].'/'.$value['id'])?>" class="title">修改</a> &nbsp;
                    <a onclick="javascript:return delConfirm()" href="<?php echo site_url('appmonitor/delTarget/'.$tplVars['id'].'/'.$value['id']);?>" id="delete_href" class="title">删除</a>&nbsp;
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div><!--contentwrapper-->
<script>

    function delConfirm() {
        var res = confirm('确认要删除？');
        return res;
    }

    jQuery(function($) {
        $('#add_button').click(function() {
            window.location.href='<?php echo site_url('appmonitor/addTarget/'.$tplVars['id'])?>'
        });
    });
</script>

