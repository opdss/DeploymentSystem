<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    #add_button{
        width: auto; margin: 0; font-weight: bold; color: #eee; background: #FB9337; border: 1px solid #F0882C; padding: 7px 10px;
        -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; cursor: pointer; -moz-border-radius: 2px; -webkit-border-radius: 2px;
        border-radius: 2px;
    }
    #add_project:hover { background: #485B79; border: 1px solid #3f526f; }
</style>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>新增监控地址</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" id="project_form" action="<?php echo site_url('appmonitor/addTarget/'.$tplVars['appMonitorItemId'].'/save')?>">
            <p>
                <label>监控地址</label>
                <span class="field"><input type="text" name="target" class="mediuminput" value=""/>
                <input type="hidden" name="appMonitorItemId" value="<?php echo $tplVars['appMonitorItemId'] ?>" /></span>
            </p>
            <p>
                <label>别名</label>
                <span class="field"><input type="text" name="name" class="mediuminput" value=""/>   选填 </span>
            </p>
            <p class="stdformbutton">
                <input type="button" id="add_button" class="submit radius2" value="提交" />
                <input type="reset" class="reset radius2" value="重置" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript">
    jQuery(function($) {
        $("#add_button").click(function() {
            //todo check empty
            //todo check project and path
            //then submit()
            $("#project_form").submit();
        });

    });

</script>

