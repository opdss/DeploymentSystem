<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
<div class="centercontent">
    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <h3>日志列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions">
            <div class="overviewhead">
                开始日期: <input type="text" id="datepickfrom" value="<?php echo $tplVars['queryArr']['startDate']?>"> &nbsp; &nbsp;
                结束日期: <input type="text" id="datepickto" value="<?php echo $tplVars['queryArr']['endDate']?>"> &nbsp; &nbsp;
                选择项目:
                <select name="projectId" id="project_id">
                    <option value="0"></option>
                    <?php foreach($tplVars['projectIdNames'] as $project ) {
                        echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
                    }
                    ?>
                </select> &nbsp; &nbsp;
                选择用户:
                <select class="user_id" id="user_id">
                    <option value="0"></option>
                    <?php foreach($tplVars['userIdNames'] as $user ) {
                        echo '<option value="'.$user['id'].'">'.$user['username'].'</option>';
                    }
                    ?>
                </select> &nbsp; &nbsp;
                <button class="redius" id="querylog_button">查询日志</button>
            </div>
        </div><!--tableoptions-->
        <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
            <thead>
            <tr>
                <th class="head1">日志ID</th>
                <th class="head0">用户名</th>
                <th class="head1">项目名</th>
                <th class="head0">项目中文名</th>
                <th class="head1">旧版本号</th>
                <th class="head0">新版本号</th>
                <th class="head0">部署类型</th>
                <th class="head1">部署时间</th>
                <th class="head0">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tplVars['list'] as $value) { ?>
                <tr>
                    <td><?php echo $value['id']?></td>
                    <td><?php echo htmlspecialchars($value['username']); ?></td>
                    <td><?php echo htmlspecialchars($value['projectEname']); ?></td>
                    <td><?php echo htmlspecialchars($value['projectCname']); ?></td>
                    <td><?php echo htmlspecialchars($value['oldRevision']); ?></td>
                    <td><?php echo htmlspecialchars($value['newRevision']); ?></td>
                    <td><?php echo ($value['deployType'] ? '正式部署' : '预部署'); ?></td>
                    <td class="center"><?php echo date('Y-m-d H:i:s',$value['deployTime'])?></td>
                    <td class="center">
                        <a href="<?php echo site_url('logs/show/'.$value['id'])?>">查看详情</a> &nbsp;
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->
    <?php echo $tplVars['pageBar']?>
    <script>
        function delConfirm() {
            return confirm('确认要删除？');
        }

        jQuery(function($) {
            $('#add_button').click(function() {
                window.location.href='/index.php?mod=user&op=dispatchAdd'
            });

            $('#querylog_button').click(function() {
                var fromDate = $('#datepickfrom').attr('value');
                var toDate =$('#datepickto').attr('value');
                var user_id = $('#user_id').attr('value');
                var project_id = $('#project_id').attr('value');
                window.location.href="<?php echo site_url('logs/index')?>?startDate="+fromDate+"&endDate="+toDate+"&userId="+user_id+"&projectId="+project_id;
            });
        });

        jQuery(document).ready(function(){
            jQuery('#datepickfrom, #datepickto').datepicker({dateFormat: 'yy-mm-dd'});
        });

        var _projectId= "<?php echo $tplVars['queryArr']['projectId']?>";
        var _userId= "<?php echo $tplVars['queryArr']['userId']?>";
        jQuery(function($) {
            $("#project_id").find("option[value=" + _projectId + "]").prop("selected",true);
            $("#user_id").find("option[value=" + _userId + "]").prop("selected",true);
        });

    </script>
</div>
    <script type="text/javascript" src="<?php echo base_url('source/js/custom/elements.js')?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/custom/list.js')?>"></script>
<?php
    include(VIEWPATH.'public/footer.php');
?>
