<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
    <TITLE>管理登录界面</TITLE>
    <style>
        BODY{PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: "Arial"; BACKGROUND: #3c3c3c; COLOR: #222; FONT-SIZE: 13px; PADDING-TOP: 0px}
        .hide{DISPLAY: none}
        IMG{BORDER-BOTTOM: 0px; BORDER-LEFT: 0px; BORDER-TOP: 0px; BORDER-RIGHT: 0px}
        #logo{TEXT-ALIGN: center; PADDING-BOTTOM: 10px; MARGIN: 80px auto 0px; WIDTH: 247px}
        #login{BACKGROUND-IMAGE: url("<?php echo base_url('source/images/bg-login.png');?>"); PADDING-BOTTOM: 20px; MARGIN: 0px auto; PADDING-LEFT: 20px; WIDTH: 247px; PADDING-RIGHT: 20px; PADDING-TOP: 20px; border-radius: 4px 4px 4px 4px}
        #login FORM{PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; PADDING-TOP: 0px}
        #login P{MARGIN: 10px 0px}
        .control-group{MARGIN-BOTTOM: 10px}
        .control-group .icon-user{BORDER-BOTTOM: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; PADDING-BOTTOM: 4px; LINE-HEIGHT: 20px; PADDING-LEFT: 5px; WIDTH: 16px; PADDING-RIGHT: 5px; DISPLAY: block; BACKGROUND: url('/source/images/bg-loginicons.png') -1px 0px; FLOAT: left; HEIGHT: 20px; OVERFLOW: hidden; BORDER-TOP: #cccccc 1px solid; MARGIN-RIGHT: -1px; BORDER-RIGHT: #cccccc 1px solid; PADDING-TOP: 4px; border-radius: 4px 0 0 4px}
        .control-group .icon-lock{BORDER-BOTTOM: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; PADDING-BOTTOM: 4px; LINE-HEIGHT: 20px; PADDING-LEFT: 5px; WIDTH: 16px; PADDING-RIGHT: 5px; DISPLAY: block; BACKGROUND: url('/source/images/bg-loginicons.png') -1px 0px; FLOAT: left; HEIGHT: 20px; OVERFLOW: hidden; BORDER-TOP: #cccccc 1px solid; MARGIN-RIGHT: -1px; BORDER-RIGHT: #cccccc 1px solid; PADDING-TOP: 4px; border-radius: 4px 0 0 4px}
        .control-group .icon-mail{BORDER-BOTTOM: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; PADDING-BOTTOM: 4px; LINE-HEIGHT: 20px; PADDING-LEFT: 5px; WIDTH: 16px; PADDING-RIGHT: 5px; DISPLAY: block; BACKGROUND: url('/source/images/bg-loginicons.png') -1px 0px; FLOAT: left; HEIGHT: 20px; OVERFLOW: hidden; BORDER-TOP: #cccccc 1px solid; MARGIN-RIGHT: -1px; BORDER-RIGHT: #cccccc 1px solid; PADDING-TOP: 4px; border-radius: 4px 0 0 4px}
        .control-group .icon-lock{BACKGROUND-POSITION: -30px 0px}
        .control-group .icon-mail{BACKGROUND-POSITION: -58px 0px}
        .control-group INPUT{BORDER-BOTTOM: #cccccc 1px solid; BORDER-LEFT: #cccccc 1px solid; PADDING-BOTTOM: 4px; LINE-HEIGHT: 20px; BACKGROUND-COLOR: #ffffff; PADDING-LEFT: 6px; WIDTH: 206px; PADDING-RIGHT: 6px; DISPLAY: inline-block; FONT-FAMILY: "Helvetica Neue", Helvetica, Arial, sans-serif; MARGIN-BOTTOM: 10px; HEIGHT: 30px; COLOR: #555555; MARGIN-LEFT: 0px; FONT-SIZE: 14px; VERTICAL-ALIGN: middle; BORDER-TOP: #cccccc 1px solid; BORDER-RIGHT: #cccccc 1px solid; PADDING-TOP: 4px; border-radius: 0 4px 4px 0; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset; transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s}
        .control-group INPUT:focus{Z-INDEX: 2; OUTLINE-STYLE: dotted; OUTLINE-COLOR: invert; OUTLINE-WIDTH: thin; box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6); -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6); -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6)}
        .remember-me{MARGIN-TOP: 0px; DISPLAY: block; MARGIN-BOTTOM: 25px}
        .remember-me INPUT{VERTICAL-ALIGN: middle; CURSOR: pointer}
        .remember-me LABEL{FONT-SIZE: 13px; VERTICAL-ALIGN: middle; CURSOR: pointer}
        .remember-me A{FLOAT: right; COLOR: #333; TEXT-DECORATION: none; PADDING-TOP: 1px}
        .remember-me A:hover{BORDER-BOTTOM: #999 1px dashed; COLOR: #999; TEXT-DECORATION: none}
        .login-btn INPUT{BORDER-BOTTOM: 1px solid; BORDER-LEFT: 1px solid; PADDING-BOTTOM: 4px; LINE-HEIGHT: 20px; BACKGROUND-COLOR: #f56c06; MARGIN: 0px auto; PADDING-LEFT: 12px; WIDTH: 80px; PADDING-RIGHT: 12px; DISPLAY: block; BACKGROUND-REPEAT: repeat-x; COLOR: #ffffff; FONT-SIZE: 13px; BORDER-TOP: 1px solid; CURSOR: pointer; BORDER-RIGHT: 1px solid; PADDING-TOP: 4px; border-radius: 4px 4px 4px 4px; box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25)}
        .login-btn INPUT:hover{BACKGROUND-COLOR: #fa7716}
        .forget-btn{PADDING-TOP: 10px}
        #login-copyright{TEXT-ALIGN: center; PADDING-BOTTOM: 0px; MARGIN: 0px auto; PADDING-LEFT: 10px; WIDTH: 250px; PADDING-RIGHT: 10px; COLOR: #999999; FONT-SIZE: 11px; PADDING-TOP: 10px}
        #login-copyright A{COLOR: #999999; TEXT-DECORATION: none}
        #login-copyright A:hover{BORDER-BOTTOM: #fff 1px dashed; COLOR: #fff; TEXT-DECORATION: none}
    </style>
</HEAD>
<BODY>
<DIV id=logo><IMG alt=HongCMS src="<?php echo base_url('source/images/logo-login.png')?>"></DIV>
<DIV id=login>
    <FORM id=loginform method=post action=<?php echo site_url('login/verify')?>>
        <P id=info>请输入用户名和密码</P>
        <DIV class=control-group><SPAN class=icon-user></SPAN><INPUT type=text name=username placeholder="Username"></DIV>
        <DIV class=control-group><SPAN class=icon-lock></SPAN><INPUT type=password name=password placeholder="Password"> </DIV>
        <DIV class=login-btn><INPUT id=login-btn value="登 录" type=submit name=submit></DIV></FORM>
</DIV>
<DIV id=login-copyright> kuaiyong.net </DIV>
<SCRIPT type=text/javascript src="<?php echo base_url('source/ajaxjs/jquery-1.6.2.min.js')?>"></SCRIPT>
<SCRIPT>$(document).ready(function() { $("#logo").css("margin-top", ($(window).height()-460)/2+"px");$("input[name='username']").focus();$("#forget-password").click(function (e) {$("#loginform").hide();$("#forgotform").show(200);e.preventDefault();});$("#forget-btn").click(function (e) {	$("#loginform").slideDown(200);$("#forgotform").slideUp(200);e.preventDefault();});});</SCRIPT>
</BODY></HTML>