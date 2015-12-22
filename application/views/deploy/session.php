<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
    include(VIEWPATH.'public/header.php');
?>
        <div class="centercontent">
            <div id="contentwrapper" class="contentwrapper lineheight21">
                <div class="notibar msgalert">
                    <a class="close"></a>
                    <p><?php echo $message?></p>
                </div>
                <div class="contenttitle2">
                    <h3>部署冲突</h3>
                </div><!--contenttitle-->
                <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                    <thead>
                    <tr>
                        <th class="head0">用户ID</th>
                        <th class="head1">用户名</th>
                        <th class="head0">当前版本</th>
                        <th class="head1">部署版本</th>
                        <th class="head0">进入时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($tplVars['sessions'] as $session)
                    {
                        ?>
                        <tr>
                            <td><?php echo $session['userId']?></td>
                            <td><?php echo $session['username']?></td>
                            <td><?php echo $session['oldRevision']?></td>
                            <td><?php echo $session['newRevision']?></td>
                            <td><?php echo date('Y-m-d H:i:s',$session['createTime'])?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <br />
                <div class="contenttitle2">
                    <h3>口头沟通</h3>
                </div><!--contenttitle-->
                <ul class="buttonlist">
                    <li><button class="stdbtn"  onclick="history.back()">放弃部署</button></li>
                    <li><button class="stdbtn btn_red" onclick="location.href='<?php echo site_url('deploy/diff/'.$tplVars['projectId'])?>?step=diff&clearlock=true'">踢出其他同学</button></li>
                </ul>
            </div>
        </div>
<?php
    include(VIEWPATH.'public/footer.php');
?>