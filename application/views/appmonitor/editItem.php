<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>应用监控编辑</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('monitor/editItem/'.$tplVars['appMonitorInfo']['id'].'/save');?>">
            <p>
                <label>项目名</label>
                <span class="field"><input type="text" name="name" class="mediuminput" value="<?php echo $tplVars['appMonitorInfo']['name']?>" />
                                                <input type="hidden" name="id" value="<?php echo $tplVars['appMonitorInfo']['id']?>" /></span>
            </p>
            <p>
                <label>检测周期</label>
                <!--          <span class="field"><input type="text" name="check_interval" class="mediuminput" value="" /></span> -->
                <span class="field">
                    <select class="radius3" name="checkinterval" id="checkinterval">
                        <option value="10">10分钟</option>
                        <option value="30">30分钟</option>
                        <option value="60">60分钟</option>
                        <option value="120">120分钟</option>
                    </select>
                </span>
            </p>
            <p>
                <label>报警邮件地址</label>
                <span class="field"><input type="text" name="maillist" class="mediuminput" value="<?php echo $tplVars['appMonitorInfo']['maillist'] ?>" />邮件地址间用逗号分隔</span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="提交修改" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript">
    var _checkinterval = "<?php echo htmlspecialchars($tplVars['appMonitorInfo']['checkinterval'])?>";
    jQuery(function($) {
        $("#checkinterval").find("option[value=" + _checkinterval + "]").prop("selected",true);
    });
</script>