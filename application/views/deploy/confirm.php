<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>要部署的主机列表确认</h3>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
        <thead>
        <tr>
            <th class="head0" class="center">主机ip</th>
            <th class="head1" class="center">主机名</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tplVars['deployHosts'] as $host) { ?>
            <tr>
                <td><?php echo $host['ip'] ?></td>
                <td ><?php echo $host['hostname'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <ul class="buttonlist floatright">
        <li><button class="stdbtn" onclick="location.href='<?php echo site_url('deploy/index/'.$tplVars['projectId'])?>?clearlock=me'">回到项目列表</button></li>
        <?php if( 'predeploy' == $tplVars['step'] ){?>
            <li><button class="stdbtn btn_orange" onclick="commit('predeploy')">确认推送到线测机</button></li>
            <?php
        }
        elseif( 'deploy' == $tplVars['step'])
        {
            ?>
            <li><button type="submit" class="stdbtn btn_red" onclick="commit('deploy')">确认推送到生产机</button></li>
            <?php
        }
        ?>
    </ul>
</div>
<script language="javascript">
    function commit(step)
    {
        var deleteopt =     document.getElementById("delete_option");
        var url = "<?php echo site_url('deploy/commit/'.$tplVars['projectId'])?>?step="+step+"&referer=<?php echo $tplVars['referer']?>&delete=<?php echo $tplVars['delete']?>";
        window.location.href=url;
    }
</script>
