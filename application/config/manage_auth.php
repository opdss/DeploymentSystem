<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/3 下午2:34
 * @copyright 7659.com
 */
$config['notAuth'] = array(
    'login', //登陆模块
);

$config['manageAuth'] = array(
    'project' => array(
        '_all' => '项目管理',
        'index' => '项目列表',
        'add' => '增加项目',
        'del' => '删除项目',
        'edit' => '修改项目',
        'show' => '查看项目',
        'bindHost' => '绑定主机',
    ),
    'host' => array(
        '_all' => '主机管理',
        'index' => '主机列表',
        'add' => '增加主机',
        'del' => '删除主机',
        'edit' => '修改主机',
        'show' => '主机详情',
        'offline' => '主机下线',
    ),
    'user' => array(
        '_all' => '用户管理',
        'index' => '用户列表',
        'add' => '增加用户',
        'del' => '删除用户',
        'edit' => '修改用户',
        'show' => '用户详情',
        'privilege' => '操作权限',
        'bindProject' => '管理主机',
    ),
    'logs' => array(
        '_all' => '部署日志',
        'index' => '日志列表',
        'show' => '部署日志',
        'rsyncLog' => '推送日志',
    ),
    'deploy' => array(
        '_all' => '部署行为',
        'init' => '初始化',
        'preDiff' => '测试环境-diff检查',
        'preConfirm' => '测试环境-hosts确认',
        'preCcommit' => '测试环境-推送更新',
        'preRollBack' => '测试环境-回滚',
        'proDiff' => '生产环境-diff检查',
        'proConfirm' => '生产环境-hosts确认',
        'proCommit' => '生产环境-推送更新',
        'proRollBack' => '生产环境-回滚',
    ),
    'appMonitor' => array(
        '_all' => '应用监控',
        'index' => '监控',
        'addItem' => '监控',
        'editItem' => '监控',
        'delItem' => '监控',
        'manageTarget' => '监控',
        'addTarget' => '监控',
        'editTarget' => '监控',
        'delTarget' => '监控',
    ),
    'front_monitor' => array(
        '_all' => '服务监控',
    ),
);

$config['manageMenu'] = array(
    'project' => array(
        '_all' => '项目管理',
    ),
    'host' => array(
        '_all' => '主机管理',
    ),
    'user' => array(
        '_all' => '用户管理',
    ),
    'logs' => array(
        '_all' => '部署日志',
    ),
    'appMonitor' => array(
        '_all' => '应用监控',
    ),
);
/*
$arr = array(
    'project' => '<a href="'.site_url('project').'" class="widgets">项目管理</a>',
    'host' => '<a href="'.site_url('host').'" class="widgets">主机管理</a>',
    'user' => '<a href="'.site_url('user').'" class="widgets">用户管理</a>',
    'deploy_log'  => '<a href="'.site_url('deploylog').'" class="widgets">部署日志</a>',
//    'monitor' => '<a href="index.php?mod=monitor" class="widgets">服务监控</a>',
//    'app_monitor' => '<a href="index.php?mod=app_monitor" class="widgets">应用监控</a>',
//    '其他监控小工具' => array
//                                    (
//                                         'front_monitor' => '<li><a href="index.php?mod=front_monitor">前端性能监控</a>',
//                                         'url_available_monitor' => '<li><a href="index.php?mod=url_monitor">URL可用性监控</a>',
//                                    ),
);
*/