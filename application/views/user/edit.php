<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    form input[type="password"] {
        background: #fcfcfc none repeat scroll 0 0;
        border: 1px solid #ccc;
        border-radius: 2px;
        box-shadow: 0 1px 3px #ddd inset;
        color: #666;
        padding: 8px 5px;
        vertical-align: middle;
    }
</style>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>用户编辑</h3>
        </div><!--contenttitle-->
        <form class="stdform stdform2" method="post" action="<?php echo site_url('user/edit/'.$tplVars['userInfo']['id'].'/save')?>">
            <p>
                <label>用户名</label>
                <span class="field"><input type="text" name="username" class="mediuminput" value="<?php echo $tplVars['userInfo']['username']?>"/></span>
            </p>
            <p>
                <label>用户密码</label>
                <span class="field"><input type="password" name="password" class="mediuminput" value="<?php echo $tplVars['userInfo']['password']?>"/></span>
            </p>
            <p>
                <label>邮箱</label>
                <span class="field"><input type="text" name="email" class="mediuminput" value="<?php echo $tplVars['userInfo']['email']?>" /></span>
            </p>
            <p>
                <label>手机号码</label>
                <span class="field"><input type="text" name="mobile" class="mediuminput" value="<?php echo $tplVars['userInfo']['mobile']?>" /></span>
            </p>
            <p class="stdformbutton">
                <input type="submit" class="submit radius2" value="提交修改" />
                <input type="reset" class="reset radius2" value="重置修改" />
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->
