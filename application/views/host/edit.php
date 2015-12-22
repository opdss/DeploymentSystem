<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>主机编辑</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('host/edit/'.$tplVars['recordDetail']['id'].'/save')?>">
            <p>
                <label>主机名称</label>
                <span class="field"><input type="hidden" name="id" value="<?php echo $tplVars['recordDetail']['id']?>" /><input type="text" name="hostname" class="mediuminput" value="<?php echo $tplVars['recordDetail']['hostname']?>" /></span>
            </p>
            <p>
                <label>所在机房</label>
                <span class="field">
                    <select class="radius3" name="idc" id="idc">
                        <?php
                        foreach ($tplVars['idcs'] as $idc)
                        {
                            ?>
                            <option value="<?php echo $idc?>"><?php echo $idc?></option>
                            <?php
                        }
                        ?>
                    </select>
                </span>
            </p>
            <p>
                <label>IP地址</label>
                <span class="field"><input type="text" name="ip" class="mediuminput" value="<?php echo $tplVars['recordDetail']['ip']?>" /></span>
            </p>
            <p>
                <label>是否预发布机</label>
                <span class="field">
                <select class="radius3" name="predeploy" id="predeploy">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
                </span>
            </p>
            <p>
                <label>是否可用</label>
                <span class="field">
                <select class="radius3" name="status" id="status">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
                <span>
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
    var _predeploy = "<?php echo $tplVars['recordDetail']['predeploy']?>";
    var _status = "<?php echo $tplVars['recordDetail']['status']?>";
    var _idc = "<?php echo $tplVars['recordDetail']['idc']?>";
    jQuery(function($) {
        $("#status").find("option[value=" + _status + "]").prop("selected",true);
        $("#predeploy").find("option[value=" + _predeploy + "]").prop("selected",true);
        $("#idc").find("option[value=" + _idc + "]").prop("selected",true);
    });
</script>
