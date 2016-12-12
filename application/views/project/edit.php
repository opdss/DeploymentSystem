<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>项目编辑</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('project/edit/'.$tplVars['projectInfo']['id'].'/save')?>">
            <p>
                <label>项目英文名</label>
                <span class="field"><input type="text" name="name" class="mediuminput" value="<?php echo $tplVars['projectInfo']['name']?>" readonly /></span>
            </p>
            <p>
                <label>项目中文名</label>
                <span class="field"><input type="hidden" name="id" value="<?php echo $tplVars['projectInfo']['id']?>" />
                                                <input type="text" name="cname" class="mediuminput" value="<?php echo $tplVars['projectInfo']['cname']?>" /></span>
            </p>
            <p>
                <label>本地部署路径</label>
                <span class="field"><input type="text" name="deployPath" class="mediuminput" value="<?php echo $tplVars['projectInfo']['deployPath']?>" /></span>
            </p>
            <p>
                <label>生产部署路径</label>
                <span class="field"><input type="text" name="prodPath" class="mediuminput" value="<?php echo $tplVars['projectInfo']['prodPath']?>" /></span>
            </p>
            <p>
                <label>代码仓库URL</label>
                <span class="field"><input type="text" name="svnUrl" class="mediuminput" value="<?php echo $tplVars['projectInfo']['svnUrl']?>" /></span>
            </p>
            <p>
                <label>代码推送账号</label>
                <span class="field"><input type="text" name="rsyncUser" class="mediuminput" value="<?php echo $tplVars['projectInfo']['rsyncUser']?>" /></span>
            </p>
            <p>
                <label>发布前本地仓库执行命令(宿主机)</label>
                <span class="field"><input type="text" name="localExec" class="longinput" value="<?php echo $tplVars['projectInfo']['localExec']?>" /></span>
            </p>
            <p>
                <label>发布前执行命令(目标机)</label>
                <span class="field"><input type="text" name="beforeExec" class="longinput" value="<?php echo $tplVars['projectInfo']['beforeExec']?>" /></span>
            </p>
            <p>
                <label>发布后执行命令(目标机)</label>
                <span class="field"><input type="text" name="afterExec" class="longinput" value="<?php echo $tplVars['projectInfo']['afterExec']?>" /></span>
            </p>
            <!--
            <p>
                <label>Graphite地址</label>
                <span class="field"><input type="text" name="graphiteMetric" class="longinput" value="<?php echo $tplVars['projectInfo']['graphiteMetric']?>" /></span>
            </p>
            <p>
                <label>LogAnalyzer地址</label>
                <span class="field"><input type="text" name="loganalyzerDir" class="longinput" value="<?php echo $tplVars['projectInfo']['loganalyzerDir']?>" /></span>
            </p>
            -->
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="提交修改" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
