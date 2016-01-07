<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>项目信息</h3>
        </div><!--contenttitle-->
        <form action="" method="post" class="stdform stdform2">
            <p>
                <label>项目英文名</label>
                <span class="field"><?php echo $tplVars['projectInfo']['name']?></span>
            </p>
            <p>
                <label>项目中文名</label>
                <span class="field"><?php echo $tplVars['projectInfo']['cname']?></span>
            </p>
            <p>
                <label>本地部署路径</label>
                <span class="field"><?php echo $tplVars['projectInfo']['deployPath']?></span>
            </p>
            <p>
                <label>生产部署路径</label>
                <span class="field"><?php echo $tplVars['projectInfo']['prodPath']?></span>
            </p>
            <p>
                <label>代码仓库URL</label>
                <span class="field"><?php echo $tplVars['projectInfo']['svnUrl']?></span>
            </p>
            <p>
                <label>代码推送账号</label>
                <span class="field">&nbsp;<?php echo $tplVars['projectInfo']['rsyncUser']?></span>
            </p>
            <p>
                <label>发布前执行命令</label>
                <span class="field">&nbsp;<?php echo $tplVars['projectInfo']['beforeExec']?></span>
            </p>
            <p>
                <label>发布后执行命令</label>
                <span class="field">&nbsp;<?php echo $tplVars['projectInfo']['afterExec']?></span>
            </p>
            <p>
                <label>增加时间</label>
                <span class="field"><?php echo date('Y-m-d H:i:s', $tplVars['projectInfo']['createTime'])?></span>
            </p>
            <p>
                <label>绑定主机列表</label>
                <span class="field">&nbsp;
                    <?php
                    foreach ($tplVars['projectInfo']['bindHosts'] as $host)
                    {
                        echo $host['ip'] . '<br />&nbsp;&nbsp;';
                    }
                    ?>
                </span>
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
