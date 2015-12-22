<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>用户列表</h3>
    </div><!--contenttitle-->
    <div class="tableoptions">
        <button class="radius3" id="add_button">添加用户</button> &nbsp;
    </div><!--tableoptions-->
    <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
        <thead>
        <tr>
            <th class="head0">用户名</th>
            <th class="head1">邮箱</th>
            <th class="head0">手机号码</th>
            <th class="head1">添加时间</th>
            <th class="head0">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tplVars['list'] as $value) { ?>
            <tr>
                <td><?php echo $value['username']; ?></td>
                <td><?php echo $value['email']; ?></td>
                <td><?php echo $value['mobile']; ?></td>
                <td class="center"><?php echo $value['createTime']; ?></td>
                <td class="center">
                    <?php
                    //$urls['view'] = '详情';
                    $urls['edit'] = '编辑';
                    $urls['bindProject'] = '部署项目授权';
                    $urls['bindMonitorProject'] = '监控项目授权';
                    $urls['privilege'] = '管理授权';
                    $urls['del'] = '删除';
                    foreach ($urls as $op => $opName) {
                        //if (checkOpPrivilege($tplVars['currentUser']['id'], 'user', $op)) {
                            ?>
                            <a href="<?php echo site_url('user/'.$op.'/'.$value['id'])?>" <?php //if ($op == 'del') echo 'id="delete_href" onclick="javascript:return delConfirm()"';?> ><?php echo $opName?></a> &nbsp;
                            <?php
                        //}
                    } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div><!--contentwrapper-->
<?php
echo $tplVars['pageBar'];
?>
<script language="javascript">
    function delConfirm() {
        return confirm('确认要删除？');
    }

    jQuery(function($) {
        $('#add_button').click(function() {
            window.location.href='<?php echo site_url('user/add')?>'
        });
    });
</script>

