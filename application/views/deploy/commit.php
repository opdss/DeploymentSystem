<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$updateStatus = array('A' => '新增', 'D' => '删除', 'U' => '更新', 'C' => '冲突', 'G' => '修改合并', 'M' => '修改', 'R' => '替换', 'I' => '忽略');
?>
<div id="contentwrapper" class="contentwrapper">
    <?php
    if ($tplVars['doUpdate'] && sizeof($tplVars['updateInfo'])>0)
    {
        ?>
        <div class="contenttitle2">
            <h3>变更列表</h3>
        </div><!--contenttitle-->
        <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
            <thead>
            <tr>
                <th class="head0" class="center" width='200'>变更类型</th>
                <th class="head1" class="center">文件(夹)路径</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($tplVars['updateInfo'] as $revision => $modifyFiles)
            {
                foreach ($modifyFiles as $type => $files)
                {
                    ?>
                    <tr>
                        <td><?php echo $updateStatus[$type] ?></td>
                        <td>
                            <?php
                            foreach ($files as $file)
                            {
                                echo $file . '<br />';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <br />
    <div class="notibar msgsuccess">
        <a class="close"></a>
        <p>代码更新推送完成,成功<font color='red'><?php echo $tplVars['successNum'] ?></font>台,失败<font color='red'><?php echo $tplVars['failNum']?></font>台,最新版本是<?php echo $tplVars['toRevision']?></p>
    </div>
    <div class="contenttitle2">
        <h3>部署日志</h3>
    </div>
    <br />
    <div class="stdform stdform2">
        <?php
        foreach ($tplVars['pushInfo']['rsync'] as $k => $log)
        {
            ?>
            <div class="par">
                <label><?php echo $tplVars['deployHosts'][$k]['ip'] . '(' . $tplVars['deployHosts'][$k]['idc'] . ')'?></label>
                <div class="field">
                    before:<?php echo isset($tplVars['pushInfo']['before']) ? nl2br($tplVars['pushInfo']['before'][$k]) : ''?><br/>
                    rsync:<?php echo nl2br($log)?><br/>
                    after:<?php echo isset($tplVars['pushInfo']['after']) ? nl2br($tplVars['pushInfo']['after'][$k]) : ''?><br/>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <br />
    <ul class="buttonlist floatright">
        <li><button class="stdbtn" onclick="location.href='<?php echo site_url('project/index');?>'">回到项目列表</button></li>

        <li><button class="stdbtn" onclick="location.href='<?php echo site_url('check_monitor/GetMonitorData/'.$projectInfo['id'])?>'">查看数据曲线和日志输出</button></li>

    </ul>
</div>