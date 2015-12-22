<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>用户绑定项目</h3>
        </div><!--contenttitle-->
        <form action="<?php echo site_url('user/bindProject/'.$tplVars['userInfo']['id'].'/save')?>" method="post" class="stdform stdform2" id="bindProjectForm" onsubmit="return false;">
            <p>
                <label>用户名</label>
                <span class="field"><?php echo $tplVars['userInfo']['username']?></span>
            </p>
            <p>
                <label>邮箱</label>
                <span class="field"><input type="hidden" name="id" value="<?php echo $tplVars['userInfo']['id']?>" /> <?php echo $tplVars['userInfo']['email']?></span>
            </p>
            <p>
                <label>手机</label>
                <span class="field"><?php echo $tplVars['userInfo']['mobile']?></span>
            </p>
            <p>
                <label>赋予项目权限</label>
                <span class="field">
                <span class="dualselect" id="dualselect">
                    <select size="10" multiple="multiple" name="select3[]" class="uniformselect">
                        <?php foreach($tplVars['notBindProjects'] as $notBindProject) { ?>
                            <option value="<?php echo $notBindProject['id']?>"><?php echo $notBindProject['name']?></option>
                        <?php } ?>
                    </select>
                    <span class="ds_arrow">
                        <span class="arrow ds_prev">«</span>
                        <span class="arrow ds_next">»</span>
                    </span>
                    <select size="10" multiple="multiple" name="newBindProjects[]">
                        <?php foreach($tplVars['userInfo']['bindProjects'] as $haveBindProject) { ?>
                            <option value="<?php echo $haveBindProject['id']?>"><?php echo $haveBindProject['name']?></option>
                        <?php } ?>
                    </select>
                </span>
                </span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" onclick = "allSelectedFun();"  value="提交绑定项目" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript" src="<?php echo base_url('source/js/custom/forms.js');?>"></script>
<script type="text/javascript">
    function allSelectedFun() {
        jQuery("#dualselect select:last-child option").each(function() {
            jQuery(this).attr('selected', 'true');
        });
        jQuery("#bindProjectForm").submit();
    }
</script>
