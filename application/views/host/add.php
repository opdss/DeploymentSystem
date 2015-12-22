<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>添加主机</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('host/add/save')?>">
            <p>
                <label>主机名称</label>
                <span class="field"><input type="text" name="hostname" class="mediuminput" value="" /></span>
            </p>

            <p>
                <label>IP地址</label>
                <span class="field"><input type="text" name="ip" class="mediuminput" value="" /></span>
            </p>
            <p>
                <label>所在机房</label>
                <span class="field">
                <select name="idc" class="radius3">
                    <?php
                    foreach ($tplVars['idcs'] as $idc)
                    {
                        ?>
                        <option value="<?php echo $idc?>"><?php echo $idc?></option>
                        <?php
                    }
                    ?>
                </select>&nbsp;
                </span>
            </p>
            <p>
                <label>是否预发布机</label>
                <input type="hidden" name="status" value="1" /></span>
                <span class="field">
                <select class="radius3" name="predeploy">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select> &nbsp;
                </span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="提交" />
                <input type="reset" class="reset radius2" value="重置" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
