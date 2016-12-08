<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="<?php echo base_url('source/css/style.default.css');?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('source/css/jquery-ui.css');?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url('source/css/jquery-ui-timepicker-addon.css');?>" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery-1.7.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery-ui-1.8.16.custom.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery.cookie.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery.dataTables.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/colorpicker.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery.alerts.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery.uniform.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery.validate.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery-ui-timepicker-addon.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/plugins/jquery-ui-sliderAccess.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('source/js/custom/general.js');?>"></script>
    <!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url('source/css/style.ie9.css');?>"/>
    <![endif]-->
    <!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url('source/css/style.ie8.css');?>"/>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <![endif]-->
</head>
<body class="withvernav">
<div class="bodywrapper">
    <div class="topheader">
        <div class="left">
            <!--<h1 class="logo"><a href="/">7659<span>Deploy</span>(测试环境)</a></h1>-->
            <h1 class="logo"><a href="<?php echo site_url();?>"><?php echo $this->_G['site_conf']['sitename'];?></a></h1>
            <br clear="all" />
        </div>
        <div class="right">
            <div class="notification"><!--可以放置消息泡--></div>
            <div class="userinfo">
                <img src="<?php echo base_url('source/images/thumbs/avatar.png');?>" alt="" />
                <span><?php echo $userInfo['username']; ?></span>
            </div>
            <div class="userinfodrop">
                <div class="avatar">
                    <a href=""><img src="<?php echo base_url('source/images/thumbs/avatarbig.png');?>" alt="" /></a>
                    <div class="changetheme">
                        <a class="default"></a>
                        <a class="blueline"></a>
                        <a class="greenline"></a>
                        <a class="contrast"></a>
                        <a class="custombg"></a>
                    </div>
                </div><!--avatar end-->
                <div class="userdata">
                    <h4><?php echo $userInfo['username']; ?></h4>
                    <span class="email"><?php echo $userInfo['email']; ?></span>
                    <ul>
                        <li><a href="<?=site_url('project/index')?>">我的项目</a></li>
                        <li><a href="<?=site_url('user/edit')?>">修改资料</a></li>
                        <li><a href="http://www.istimer.com" target="_blank">系统帮助</a></li>
                        <li><a href="<?=site_url('login/logout')?>">退出系统</a></li>
                    </ul>
                </div><!--userdata end-->
            </div><!--userinfodrop end-->
        </div><!--right end-->
    </div><!--topheader end-->
    <div class="vernav2 iconmenu">
        <ul>
            <?php
                foreach($menu as $key=>$val){
                    $value = '<a href="'.site_url($key).'" class="widgets">'.$val['_all'].'</a>';
                    if (1) {
                        echo '<li class="current">' .$value. '</li>';
                    }
                    else
                    {
                        echo '<li>' .$value. '</li>';
                    }
                }
            ?>
        </ul>
        <a class="togglemenu"></a>
        <br /><br />
    </div><!--leftmenu-->
