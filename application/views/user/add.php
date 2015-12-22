<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>用户增加</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('user/add/save')?>">
            <p>
                <label>用户名</label>
                <span class="field"><input type="text" name="username" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>用户密码</label>
                <span class="field"><input type="text" name="password" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>公司邮箱</label>
                <span class="field"><input type="text" name="email" class="mediuminput" value=""/></span>
            </p>
            <p>
                <label>手机号码</label>
                <span class="field"><input type="text" name="mobile" class="mediuminput" value="" /></span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="增加用户" />
                <input type="reset" class="reset radius2" value="重置" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
