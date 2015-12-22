<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
        <h3>版本比较</h3>
    </div><!--contenttitle-->
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
        <thead>
        <tr>
            <th class="head0" colspan="2" class="center">旧版本属性</th>
            <th class="head1" colspan="2" class="center">新版本属性</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>仓库地址</td>
            <td><?php echo $tplVars['baseSvnInfo']['url'] ?></td>
            <td>仓库地址</td>
            <td ><?php echo $tplVars['headSvnInfo']['url'] ?></td>
        </tr>
        <tr>
            <td>版本号</td>
            <td><?php echo $tplVars['baseSvnInfo']['revision'] ?></td>
            <td>版本号</td>
            <td><?php echo $tplVars['headSvnInfo']['revision'] ?></td>
        </tr>
        <tr>
            <td>最后修改人</td>
            <td><?php echo $tplVars['baseSvnInfo']['modifier'] ?></td>
            <td>最后修改人</td>
            <td><?php echo $tplVars['headSvnInfo']['modifier'] ?></td>
        </tr>
        <tr>
            <td>最后修改时间</td>
            <td><?php echo $tplVars['baseSvnInfo']['modifyDate'] ?></td>
            <td>最后修改时间</td>
            <td><?php echo $tplVars['headSvnInfo']['modifyDate'] ?></td>
        </tr>
        </tbody>
    </table>
    <?php
    if ($tplVars['baseSvnInfo']['revision'] == $tplVars['headSvnInfo']['revision'])
    {
        ?>
        <br />
        <div class="notibar msgalert">
            <a class="close"></a>
            <p>发布目录的代码跟线上代码版本一致,请确认是否要进行代码推送。</p>
        </div><!-- notification msgalert -->
        <?php
    }
    else
    {
        if (sizeof($tplVars['diffInfo']) == 0)
        {
            ?>
            <br />
            <div class="notibar msgalert">
                <a class="close"></a>
                <p>发布目录的代码跟线上代码内容一致,请确认是否要进行代码推送。</p>
            </div><!-- notification msgalert -->
            <?php
        }
        else
        {
            ?>
            <div class="widgetbox">
                <div class="title"><h3>代码比较</h3></div>
                <div class="widgetcontent">
                    <div id="accordion" class="accordion">
                        <?php foreach ($tplVars['diffInfo'] as $file => $lines): ?>
                            <h3><a href="#"><?php echo htmlspecialchars($file); ?></a></h3>
                            <div>
                    <pre>
<?php foreach ($lines as $line): ?>
    <?php $v = substr($line, 0, 1); ?>
    <span class="code<?php if ($v == '+'): ?> gi<?php elseif ($v == '-'): ?> gd<?php endif; ?>"><?php echo htmlspecialchars($line); ?></span>
<?php endforeach; ?>
                    </pre>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div> <!--widgetcontent-->
            </div><!--widgetbox-->
            <?php
        }
    }
    ?>
    <ul class="buttonlist floatright">
        <li><input type="checkbox" id="delete_option" name="delete" /><span style="font-size:15px;">&nbsp; 是否删除服务端特有的文件？（如果不确定请忽略！）</span></li>
        <li><button class="stdbtn" onclick="location.href='<?php echo site_url('project/index')?>?frompid=<?php echo $tplVars['projectInfo']['id']?>&clearlock=me'">回到项目列表</button></li>
        <?php if($tplVars['hasPredeploy']){?>
            <li><button class="stdbtn btn_orange" onclick="commit('predeploy')">推送到线测机</button></li>
            <?php
        }
        elseif($tplVars['hasDeploy'])
        {
            ?>
            <li><button type="submit" class="stdbtn btn_red" onclick="commit('deploy')">推送到生产机</button></li>
            <?php
        }
        ?>
    </ul>
</div>
<script language="javascript">
    function commit(step)
    {
        var deleteopt =     document.getElementById("delete_option");
        var url = "<?php echo site_url('deploy/confirm/'.$tplVars['projectInfo']['id']);?>?step="+step+"&referer=diff&delete=" + deleteopt.checked;
        window.location.href=url;
    }
    jQuery(document).ready(function(){
        jQuery('#accordion').accordion({autoHeight:  false});
    });
</script>
<style type="text/css">
    pre, code {
        font-family: Consolas,"Liberation Mono",Courier,monospace;
        font-size: 12px;
        padding-left: 10px;
        background-color: #F8F8FF;
    }
    .ui-accordion-header{
        text-transform: none;
    }
    .gi {background-color: #ddffdd;}
    .gd {background-color: #ffdddd;}
</style>
