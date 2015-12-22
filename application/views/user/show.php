<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
        <div class="contenttitle2">
            <h3>用户信息</h3>
        </div><!--contenttitle-->
        <form action="" method="post" class="stdform stdform2">
            <p>
                <label>用户名</label>
                <span class="field"><?php echo $tplVars['userInfo']['username']?></span>
            </p>
            <p>
                <label>邮箱</label>
                <span class="field"><?php echo $tplVars['userInfo']['email']?></span>
            </p>
            <p>
                <label>手机号</label>
                <span class="field"><?php echo $tplVars['userInfo']['mobile']?></span>
            </p>
            <p>
                <label>增加时间</label>
                <span class="field"><?php echo $tplVars['userInfo']['createTime']?></span>
            </p>
            <p>
                <label>有权限的项目列表</label>
                <span class="field">
                <?php
                foreach ($tplVars['userInfo']['bindProjects'] as $project)
                {
                    echo $project['name'] . '<br />';
                }
                ?>
                </span>
            </p>
        </form>
        <br />
    </div>
</div><!--contentwrapper-->

