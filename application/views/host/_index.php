<?php
include(VIEWPATH.'public/header.php');
?>
<div class="centercontent">

    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <h3>主机列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions">
            <div class="overviewhead">
                <button class="radius3" id="add_button">添加主机</button> &nbsp;
                <input type="text" id="search_key" name="keyword" value="<?=($tplVars['searchArr']['keyword'])?>"> &nbsp; &nbsp;
                <select class="radius3" name="idc" id="search_idc">
                    <option value="">全部机房</option>
                    <?php
                    foreach($tplVars['idcs'] as $value) {
                        echo '<option value="' .$value. '">'.$value.'</option>';
                    }
                    ?>
                </select> &nbsp;&nbsp;
                <button class="redius" id="searchHost_button">过滤主机</button> &nbsp; &nbsp;
            </div> <!--overviewhead-->
        </div><!--tableoptions-->
        <table cellpadding="0" cellspacing="0" border="0" id="table2" class="stdtable stdtablecb">
            <thead>
            <tr>
                <th class="head0">主机名</th>
                <th class="head1">所在机房</th>
                <th class="head0">IP地址</th>
                <th class="head1">是否预发布机</th>
                <th class="head0">状态</th>
                <th class="head1">添加时间</th>
                <th class="head0">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tplVars['list'] as $value) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($value['hostname']); ?></td>
                    <td><?php echo htmlspecialchars($value['idc']); ?></td>
                    <td><?php echo htmlspecialchars($value['ip']); ?></td>
                    <td class="center"><?php echo $value['predeploy'] ? '是' : '否'?></td>
                    <td class="center"><?php echo $value['status'] ? '可用' : '不可用'?></td>
                    <td class="center"><?php echo $value['createTime']?></td>
                    <td class="center">
                        <a href="<?php echo site_url('host/show/'.$value['hid'])?>">详情</a>&nbsp;
                        <a href="<?php echo site_url('host/edit/'.$value['hid'])?>">编辑</a>&nbsp;
                        <a href="<?php echo site_url('host/del/'.$value['hid'])?>" onclick="javascript:return delConfirm()">删除</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->

    <?php echo $tplVars['pageBar']?>
    <script type="text/javascript">
        function delConfirm() {
            return confirm('确认删除吗？');
        }

        jQuery(function($) {
            $('#add_button').click(function() {
                window.location.href= "<?php echo site_url('host/add')?>";
            });
            $('#searchHost_button').click(function() {
                var keyword = $('#search_key').attr('value');
                var idc = $('#search_idc').attr('value');
                window.location.href="<?php echo site_url('host/index')?>?keyword="+keyword+"&idc="+idc;
            });

        });

        var _idc= "<?php echo htmlspecialchars($tplVars['searchArr']['idc'])?>";
        jQuery(function($) {
            $("#search_idc").find("option[value=" + _idc  + "]").prop("selected",true);
        });
    </script>

</div>
    <script type="text/javascript" src="source/js/custom/elements.js"></script>
    <script type="text/javascript" src="source/js/custom/list.js"></script>
<?php
    include(VIEWPATH.'public/footer.php');
?>