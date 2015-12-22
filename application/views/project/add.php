<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    #add_project {
        width: auto; margin: 0; font-weight: bold; color: #eee; background: #FB9337; border: 1px solid #F0882C; padding: 7px 10px;
        -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none; cursor: pointer; -moz-border-radius: 2px; -webkit-border-radius: 2px;
        border-radius: 2px;
    }
    #add_project:hover { background: #485B79; border: 1px solid #3f526f; }
</style>
<div id="contentwrapper" class="contentwrapper">
    <div id="validation" class="subcontent" style="">
        <div class="contenttitle2">
            <h3>项目增加</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" id="project_form" action="<?php echo site_url('project/add/save')?>" novalidate="novalidate">
            <p>
                <label>项目中文名</label>
                <span class="field"><input id="cname" type="text" name="cname" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>项目英文名</label>
                <span class="field"><input id="name" type="text" name="name" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>发布机存储路径</label>
                <span class="field"><input id="deploy_path" type="text" name="deployPath" class="mediuminput" value="" /> 例如：/var/deploy/project_name</span>
            </p>
            <p>
                <label>生产机部署路径</label>
                <span class="field"><input id="prod_path" type="text" name="prodPath" class="mediuminput" value="" /></span>
            </p>
            <p>
                <label>代码仓库URL</label>
                <span class="field"><input id="svn_url" type="text" name="svnUrl" class="mediuminput" value="" /></span>
            </p>
            <p>
                <label>代码推送账号</label>
                <span class="field"><input id="rsync_user" type="text" name="rsyncUser" class="mediuminput" value="<?php echo DEFAULT_RSYNC_USER?>" /></span>
            </p>
            <p>
                <label>发布前执行命令</label>
                <span class="field"><input type="text" name="beforeExec" class="longinput" value="" /></span>
            </p>
            <p>
                <label>发布后执行命令</label>
                <span class="field"><input type="text" name="afterExec" class="longinput" value="" /></span>
            </p>
            <p class="stdformbutton">
                <input type="submit" id="add_project" class="submit radius2" value="增加项目" />
                <input type="reset" class="reset radius2" value="重置" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
<script type="text/javascript">
    jQuery(function($) {
//    $("#add_project").click(function() {
        jQuery("#project_form").validate({
            rules: {
                cname:"required",
                name:"required",
                deploy_path:"required",
                prod_path:"required",
                svn_url:"required",
                rsync_user:"required"
            },
            messages: {
                cname:"请输入项目中文名",
                name:"请输入项目英文名",
                deploy_path:"请输入发布机存储路径",
                prod_path:"请输入生产机部署路径",
                svn_url:"请输入代码仓库url",
                rsync_user:"请输入代码推送账号open或sync360"
            }
        });
        /*
         alert('success');
         var cname_value = $("#cname").attr("value");
         var name_value = $("#name").attr("value");
         var deploy_path_value = $("#deploy_path").attr("value");
         var prod_path_value = $("#prod_path").attr("value");
         var svn_url_value = $("#svn_url").attr("value");
         var url= "/index.php?mod=project&op=ajaxCheckProjectAdd";
         var postData = "{cname:cname_value, name:name_value, deploy_path:deploy_path_value, prod_path:prod_path_value, svn_url:svn_url_value}";
         $.post(url, postData, function(data) {
         if (data.status == '0') {
         alert(data.message);
         } else {
         $("#project_form").submit();
         }
         }, "json"); */
//  });
    });
</script>
