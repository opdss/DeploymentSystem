<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>项目绑定主机</h3>
        </div><!--contenttitle-->
        <form action="<?php echo site_url('project/bindHost/'.$tplVars['projectInfo']['id'].'/save')?>" method="post" class="stdform stdform2" id="bindHostForm" onsubmit="return false;">
            <p>
                <label>项目英文名</label>
                <span class="field"><?php echo $tplVars['projectInfo']['name']?></span>
            </p>
            <p>
                <label>项目中文名</label>
                <span class="field"><input type="hidden" name="id" value="<?php echo $tplVars['projectInfo']['id']?>" /> <?php echo $tplVars['projectInfo']['cname']?></span>
            </p>
            <p>
                <label>绑定主机IP</label>
                <span class="field">
                <span class="dualselect" id="dualselect">
                    <select size="10" multiple="multiple" name="select3[]" class="uniformselect">
                        <?php foreach($tplVars['notBindHosts'] as $notBindHost) { ?>
                            <option value="<?php echo $notBindHost['id']?>"><?php echo $notBindHost['ip']; echo $notBindHost['hostname'];?></option>
                        <?php } ?>
                    </select>
                    <span class="ds_arrow">
                        <span class="arrow ds_prev">«</span>
                        <span class="arrow ds_next">»</span>
                    </span>
                    <select size="10" multiple="multiple" name="newBindHosts[]">
                        <?php foreach($tplVars['haveBindHosts'] as $haveBindHost) { ?>
                            <option value="<?php echo $haveBindHost['id']?>"><?php echo $haveBindHost['ip']; echo $haveBindHost['hostname'];?></option>
                        <?php } ?>
                    </select>
                </span>
                </span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" onclick = "allSelectedFun();"  value="提交绑定IP" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript" src="<?php echo base_url('source/js/custom/forms.js')?>"></script>
<script type="text/javascript">
    function allSelectedFun() {
        jQuery("#dualselect select:last-child option").each(function() {
            jQuery(this).attr('selected', 'true');
        });
        jQuery("#bindHostForm").submit();
    }
</script>
