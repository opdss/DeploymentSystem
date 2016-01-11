<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
<div class="centercontent">
    <div id="contentwrapper" class="contentwrapper">
        <div id="basicform" class="subcontent">
            <div class="contenttitle2">
                <h3>日志详细信息</h3>
            </div><!--contenttitle-->
            <form action="" method="post" class="stdform stdform2">
                <p>
                    <label>部署人</label>
                    <span class="field"><?php echo $tplVars['deployLogInfo']['username']?></span>
                </p>
                <p>
                    <label>项目名</label>
                    <span class="field"><?php echo $tplVars['deployLogInfo']['projectEname']?></span>
                </p>
                <p>
                    <label>项目原版本</label>
                    <span class="field"><?php echo $tplVars['deployLogInfo']['oldRevision']?></span>
                </p>
                <p>
                    <label>项目新版本</label>
                    <span class="field"><?php echo $tplVars['deployLogInfo']['newRevision']?></span>
                </p>
                <p>
                    <label>部署类型</label>
                    <span class="field"><?php echo $tplVars['deployLogInfo']['deployType'] ? '正式部署' : '预部署'?></span>
                </p>
                <p>
                    <label>部署时间</label>
                    <span class="field"><?php echo date('Y-m-d H:i:s', $tplVars['deployLogInfo']['deployTime']) ?></span>
                </p>
                <p>
                    <label>部署主机IP列表及状态</label>
                    <span class="field">
    <?php
    if(isset($tplVars['deployHosts']) && is_array($tplVars['deployHosts']) && count($tplVars['deployHosts']) > 0) {
        foreach ($tplVars['deployHosts'] as $host)
        {
            echo $host['hostIp'];
            if ($host['status'] == '1') {
                echo  ' | 部署成功 | ';
            } else {
                echo ' | 部署失败 | ';
            }
            ?>
            <a href="<?php echo site_url('logs/rsyncLog/'.$host['id'])?>">Rsync日志详情</a> &nbsp; <br />
            <?php
        }
    }
    ?>
                    </span>
                </p>
            </form>
            <br />
        </div>
    </div><!--contentwrapper-->
</div>
<script type="text/javascript" src="<?php echo base_url('source/js/custom/elements.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('source/js/custom/list.js')?>"></script>
<?php
    include(VIEWPATH.'public/footer.php');
?>

