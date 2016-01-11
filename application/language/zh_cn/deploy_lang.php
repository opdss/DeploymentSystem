<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/21 上午12:39
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['username'] = '用户名';
$lang['password'] = '密码';
$lang['email'] = '邮箱';
$lang['mobile'] = '手机号';

$lang['cname'] = '项目中文名';
$lang['name'] = '项目名称';
$lang['deployPath'] = '预发布地址';
$lang['prodPath'] = '部署路径';
$lang['svnUrl'] = 'svn地址';
$lang['rsyncUser'] = '发布用户';

$lang['field_empty'] = '字段不应为空';

$lang['db_insert_error'] = '数据库插入出错';

$lang['user_login_please'] = '请登录';
$lang['user_login_password_error'] = '用户密码错误';


//user
$lang['user_auth_no'] = '您没有权限操作';
$lang['user_id_error'] = '用户ID错误';
$lang['user_email_isuse'] = '用户Email已经存在';
$lang['user_info_no'] = '没有用户的信息记录';
$lang['user_not_del_self'] = '用户不能删除自己';

$lang['user_add_success'] = '用户添加成功';
$lang['user_add_error'] = '用户添加失败';
$lang['user_del_success'] = '用户删除成功';
$lang['user_del_error'] = '用户删除失败';
$lang['user_edit_success'] = '用户信息修改成功';
$lang['user_edit_error'] = '用户信息修改失败';
$lang['user_privilege_no'] = '没有为用户赋予任何操作权限';
$lang['user_privilege_success'] = '给用户赋予操作权限成功';
$lang['user_del_bind_project_success'] = '为用户删除所有部署项目权限成功';
$lang['user_add_bind_project_success'] = '给用户赋予部署项目权限成功';

//project
$lang['project_id_error'] = '项目ID错误';
$lang['project_info_no'] = '没有相关项目信息';
$lang['user_host_no'] = '没有绑定主机,项目必须绑定主机以分发代码';
$lang['project_bind_success'] = '主机绑定成功';

$lang['project_add_success'] = '项目添加成功';
$lang['project_add_error'] = '项目添加失败';
$lang['project_edit_success'] = '项目修改成功';
$lang['project_edit_error'] = '项目修改失败';
$lang['project_del_success'] = '项目删除成功';
$lang['project_del_error'] = '项目删除失败';

$lang['project_name_already_exists'] = '项目名已经存在';
$lang['project_deploy_path_already_exists'] = '部署路径已经存在';
$lang['project_line_path_already_exists'] = '线上代码路径已经存在';
$lang['project_deploy_dir_already_exists'] = '该部署目录在部署服务器上已存在';

//host
$lang['host_id_error'] = '主机ID错误';
$lang['host_info_no'] = '没有相关主机信息';
$lang['host_bind_success'] = '主机绑定成功';

$lang['hostname'] = '主机名称';
$lang['idc'] = '机房';
$lang['ip'] = '主机IP';
$lang['status'] = '主机状态';
$lang['predeploy'] = '是否预发布机';

$lang['host_add_success'] = '主机添加成功';
$lang['host_add_error'] = '主机添加失败';
$lang['host_edit_success'] = '主机修改成功';
$lang['host_edit_error'] = '主机修改失败';
$lang['host_del_success'] = '主机删除成功';
$lang['host_del_error'] = '主机删除失败';
$lang['host_offline_success'] = '主机下线成功';
$lang['host_offline_error'] = '主机下线失败';

$lang['host_ip_already_exists'] = '主机IP已经存在';
$lang['host_ping_ip_error'] = '你输入的主机IP不可用,不能ping通';


$lang['host_idc_error'] = '机房信息输入有误';
$lang['host_ip_error'] = 'IP地址格式不正确';
$lang['host_status_error'] = '主机状态输入不正确';
$lang['host_predeploy_error'] = '预发布机状态输入不正确';


//deploy
$lang['deploy_auth_no'] = '你没有该项目的部署权限';
$lang['deploy_init_success'] = '项目初始化成功';
$lang['deploy_bind_host_no'] = '此项目没有绑定任何主机';
$lang['deploy_unlawful'] = '非法的上下文环境,版本号信息缺失';
$lang['deploy_lock_error'] = '您尚未持有部署锁或者已经失效';
$lang['deploy_project_user_already_exists'] = '探测到有多个同学进入该项目的部署流程,请进行口头沟通选择本次部署人员';
$lang['deploy_mkdir_deploypath_error'] = '创建发布目录{$deployPath}失败,请检查权限';
$lang['deploy_svn_checkout_error'] = '检出上线SVN{$svnUrl}失败,请检查权限';
$lang['deploy_init_no'] = '项目尚未初始化,发布目录{$deployPath}不存在';
$lang['deploy_svn_init_no'] = '项目尚未初始化,代码仓库{$svnUrl}未检出到发布目录';
