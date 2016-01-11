<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>项目列表</h3>
    </div><!--contenttitle-->
    <div class="tableoptions">
        <button class="radius3" id="add_button">添加项目</button> &nbsp;
    </div><!--tableoptions-->
    <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
        <thead>
        <tr>
            <th class="head0">项目名</th>
            <th class="head1">生产机路径</th>
            <th class="head0">添加时间</th>
            <th class="head1 center">管理操作</th>
            <th class="head0 center">日常操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($tplVars['list'] as $value) {
            ?>
            <tr>
                <td><a href="<?php echo site_url('project/show/'.$value['id']);?>" class="title"><?php echo $value['cname']; ?></a></td>
                <td><?php echo $value['prodPath']; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$value['createTime']);?></td>
                <td class="center">
                    <a href="<?php echo site_url('project/edit/'.$value['id']);?>">修改</a> &nbsp;
                    <a href="<?php echo site_url('project/bindHost/'.$value['id']);?>">绑定主机</a> &nbsp;
                    <a href="<?php echo site_url('deploy/init/'.$value['id']);?>">初始化</a> &nbsp;
                    <a onclick="javascript:return delConfirm()" href="<?php echo site_url('project/del/'.$value['id']);?>" id="delete_href">删除</a>&nbsp;
                </td>
                <td class="center">
                    <?php if(in_array('deploy.preDiff',$userInfo['privilege'])){ ?>
                    <a href="<?php echo site_url('deploy/preDiff/'.$value['id']);?>" class="btn btn2 btn_archive"><span>预部署</span></a>&nbsp;
                    <?php }?>
                    <?php if(in_array('deploy.proDiff',$userInfo['privilege'])){ ?>
                    <a href="<?php echo site_url('deploy/proDiff/'.$value['id']);?>" class="btn btn2 btn_archive"><span>生产部署</span></a>&nbsp;
                    <?php }?>
                    <?php if(in_array('deploy.preRollBack',$userInfo['privilege'])){ ?>
                    <a href="<?php echo site_url('deploy/preRollBack/'.$value['id']);?>" class="btn btn2 btn_cloud"><span>预回滚</span></a>&nbsp;
                    <?php }?>
                    <?php if(in_array('deploy.proRollBack',$userInfo['privilege'])){ ?>
                    <a href="<?php echo site_url('deploy/proRollBack/'.$value['id']);?>" class="btn btn2 btn_cloud"><span>生产回滚</span></a>&nbsp;
                    <?php }?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div><!--contentwrapper-->
<?php echo $tplVars['pageBar'];?>
<script>

    function delConfirm() {
        var res = confirm('确认要删除？');
        return res;
    }

    jQuery(function($) {
        $('#add_button').click(function() {
            window.location.href='<?php echo site_url('project/add');?>'
        });
    });
</script>
