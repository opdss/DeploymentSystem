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
            <h3>新增监控</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" id="project_form" action="<?php echo site_url('monitor/addItem/save');?>">
            <p>
                <label>项目名</label>
                <span class="field"><input type="text" name="name" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>检测周期</label>
                <!--          <span class="field"><input type="text" name="check_interval" class="mediuminput" value="" /></span> -->
                <span class="field">
                    <select class="radius3" name="checkinterval">
                        <option value="10">10分钟</option>
                        <option value="30">30分钟</option>
                        <option value="60">60分钟</option>
                        <option value="120">120分钟</option>
                    </select>
                </span>
            </p>
            <p>
                <label>报警邮件地址</label>
                <span class="field"><input type="text" name="maillist" class="mediuminput" value="<?php echo $userInfo['email'],','; ?>" />邮件地址间用逗号分隔</span>
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
