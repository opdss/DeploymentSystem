<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>为用户赋予操作权限</h3>
        </div><!--contenttitle-->
        <form action="<?php echo site_url('user/privilege/'.$tplVars['userInfo']['id'].'/save')?>" method="post" class="stdform stdform2" id="addPrivilegeForm" onsubmit="return false;">
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
                <label>操作授权</label>
                <span class="field">
                <span class="dualselect" id="dualselect">
                    <select size="10" multiple="multiple" name="select3[]" class="uniformselect">
                        <?php foreach($tplVars['notPermitOperators'] as $key => $value) { ?>
                            <option value="<?php echo $key ?>"><?php echo $value?></option>
                        <?php } ?>
                    </select>
                    <span class="ds_arrow">
                        <span class="arrow ds_prev">«</span>
                        <span class="arrow ds_next">»</span>
                    </span>
                    <select size="10" multiple="multiple" name="newPermitOps[]">
                        <?php
                        ksort($tplVars['permitOperators']);
                        foreach($tplVars['permitOperators'] as $key => $value)
                        {
                            ?>
                            <option value="<?php echo $key ?>"><?php echo $value?></option>
                            <?php
                        }
                        ?>
                    </select>
                </span>
                </span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" onclick = "allSelectedFun();"  value="操作授权" />
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
        jQuery("#addPrivilegeForm").submit();
    }
</script>
