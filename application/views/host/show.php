<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>主机信息</h3>
        </div><!--contenttitle-->
        <form action="" method="post" class="stdform stdform2">
            <p>
                <label>主机名称</label>
                <span class="field"><input type="text" name="hostname" class="mediuminput" value="<?php echo $tplVars['recordDetail']['hostname']?>" /></span>
            </p>
            <p>
                <label>idc</label>
                <span class="field"><input type="text" name="idc" class="mediuminput" value="<?php echo $tplVars['recordDetail']['idc']?>" /></span>
            </p>
            <p>
                <label>主机ip</label>
                <span class="field"><input type="text" name="ip" class="mediuminput" value="<?php echo htmlspecialchars($tplVars['recordDetail']['ip'])?>" /></span>
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
                </span>
            </p>
            <p>
                <label>部署项目列表</label>
                <span class="field">
                <?php
                foreach($tplVars['recordDetail']['bindProjectArr'] as $project) {
                    echo $project['name'] . '<br/>';
                }
                ?>
                </span>
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript">
    var _predeploy = "<?php echo htmlspecialchars($tplVars['recordDetail']['predeploy'])?>";
    var _status = "<?php echo htmlspecialchars($tplVars['recordDetail']['status'])?>";
    jQuery(function($) {
        $("#status").find("option[value=" + _status + "]").prop("selected",true);
        $("#predeploy").find("option[value=" + _predeploy + "]").prop("selected",true);
        $('form').find('input,select').attr("disabled" ,true);
    });
</script>
