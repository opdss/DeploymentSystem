<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <?php
    if (sizeof($tplVars['lastDeploys']) <= 1)
    {
        ?>
        <br />
        <div class="notibar msgalert">
            <a class="close"></a>
            <p>项目<span style="color: #FB9337;"><?php echo $tplVars['projectInfo']['cname']?></span>部署次数少于2次,不能回滚</p>
        </div><!-- notification msgalert -->
        <?php
    }
    else
    {
        ?>
        <div class="contenttitle2">
            <h3><?php echo $tplVars['projectInfo']['cname']?>最近部署轨迹</h3>
        </div><!--contenttitle-->
        <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablecb">
            <thead>
            <tr>
                <th class="head1">部署人</th>
                <th class="head0">版本迁移</th>
                <th class="head1">部署时间</th>
                <th class="head0">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tplVars['lastDeploys'] as $k => $deploy)
            {
                ?>
                <tr>
                    <td><?php echo $deploy['username']?></td>
                    <td><?php echo $deploy['oldRevision']?> => <?php echo $deploy['newRevision']?></td>
                    <td><?php echo date('Y-m-d H:i:s', $deploy['deployTime'])?></td>
                    <td>
                        <?php
                        if ($k > 0)
                        {
                            ?>
                            <a href="<?php echo site_url('deploy/'.($tplVars['deployType'] ? 'proDiff' : 'preDiff').'/'.$deploy['projectId'])?>?revision=<?php echo $deploy['newRevision']?>" class="btn btn2 btn_cloud"><span>回滚到版本<?php echo $deploy['newRevision']?></span></a>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <br />
    <ul class="buttonlist floatright">
        <li><button class="stdbtn" onclick="location.href='<?php echo site_url('project/index/')?>?frompid=<?php echo $tplVars['projectInfo']['id']?>'">回到项目列表</button></li>
    </ul>
</div>