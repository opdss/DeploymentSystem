<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>应用监控地址编辑</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('monitor/editTarget/'.$tplVars['appTargetInfo']['appMonitorItemId'].'/'.$tplVars['appTargetInfo']['id'].'/save');?>">
            <p>
                <label>监控地址</label>
                <span class="field"><input type="text" name=target class="mediuminput" value="<?php echo $tplVars['appTargetInfo']['target']?>" /></span>
            </p>
            <p>
                <label>别名</label>
                <span class="field"><input type="text" name="name" class="mediuminput" value="<?php echo $tplVars['appTargetInfo']['name']?>" />
                                                <input type="hidden" name="app_monitor_target_id" value="<?php echo $tplVars['appTargetInfo']['id']?>" />
                                                <input type="hidden" name="app_monitor_item_id" value="<?php echo $tplVars['appTargetInfo']['appMonitorItemId']?>" /></span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="提交修改" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->