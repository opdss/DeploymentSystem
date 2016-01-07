<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/15 下午1:56
 * @copyright 7659.com
 */
class Shell{

    static private $userAuth = ' --username=wuxin --password=12345678' ;

    //检查一个主机是否跟部署机联通
    static function checkPing($ip){
        $return = shell_exec('ping -c 3 -w 1 ' . $ip);
        if (strpos($return, '100% packet loss') !== False) {
            return False;
        }
        return True;
    }

    static function svnCheckOut($svnUrl,$checkPath){
        $cmd = 'svn checkout ' .$svnUrl . ' ' . escapeshellarg($checkPath) . static::$userAuth . ' 2>&1';
        $cmdOutput = array();
        $cmdReturn = 0;
        exec($cmd, $cmdOutput, $cmdReturn);
        log_message('error',$cmd);
        return $cmdReturn != 0 ? false : $cmdOutput;

    }
    // 获取某个工作拷贝的某个版本的SVN信息
    static function getSvnInfo($deployPath, $revision = 'HEAD'){
        $infoCmd = 'svn info -r ' . $revision . ' ' . escapeshellarg($deployPath) . static::$userAuth . ' 2>&1';
        $info = array();
        $return = 0;
        exec($infoCmd, $info, $return);
        log_message('error',$infoCmd);
        if ($return != 0 || sizeof($info) != 10) {
            log_message('error',$infoCmd);
            return '获取发布目录当前'.$revision.'SVN信息失败:'. implode("<br />", $info);
        }
        $svnInfo = array();
        $svnInfo['url'] = substr($info[1], strpos($info[1],'URL: ')+strlen('URL: '));
        $svnInfo['revision'] = substr($info[4], strpos($info[4],'Revision: ')+strlen('Revision: '));
        $svnInfo['modifier'] = substr($info[6], strpos($info[6],'Last Changed Author: ')+strlen('Last Changed Author: '));
        $svnInfo['modifyDate'] = substr($info[8], strpos($info[8],'Last Changed Date: ')+strlen('Last Changed Date: '));
        return $svnInfo;
    }

    // 获取工作拷贝的两个版本的DIFF信息
    static function getSvnDiffInfo($deployPath, $revision1 = 'BASE', $revison2 = 'HEAD'){
        // 已经检出过,diff 当前版本与上一个版本
        $diffCmd = 'svn diff -r ' . $revision1 . ':' . $revison2 . ' ' . escapeshellarg($deployPath) . static::$userAuth . ' 2>&1';
        $diffOutput = array();
        $diffReturn = 0;
        exec($diffCmd, $diffOutput, $diffReturn);
        log_message('error',$diffCmd);
        if ($diffReturn != 0) {
            return 'Diff当前版本与上一版本失败:'. implode("<br />", $diffOutput);
        }
        // 格式化diff信息
        $formatDiff = array();
        foreach ($diffOutput as $line) {
            $prefix = substr($line, 0, 4);
            if (in_array($prefix, array('--- ', '+++ '))) {
                $line = str_replace($deployPath, '', $line);
                if($prefix == '--- ') {
                    $script = strstr(substr($line, 4), "\t", true);
                    //exec("svn cat -r {$revison2} {$deployPath}{$script} | php -l 2>&1", $message, $error);
                }
            }

            if (isset($script)) {
                $formatDiff[$script][] = $line;
            }
        }
        return $formatDiff;
    }

    // 将工作拷贝更新到某个版本
    static function svnUpdate($deployPath, $revision = "HEAD"){
        $updateStatus = array(
            'A' => 'Added',
            'D' => 'Deleted',
            'U' => 'Updated',
            'C' => 'Conflict',
            'G' => 'Modified and Merged',
            'M' => 'Modified',
            'R' => 'Replaced',
            'I' => 'Ignored'
        );
        $updateCmd = 'svn update -r ' . $revision . ' ' . escapeshellarg($deployPath) . static::$userAuth . ' 2>&1';

        $updateOutput = array();
        $updateReturn = 0;
        exec($updateCmd, $updateOutput, $updateReturn);
        log_message('error',$updateCmd);

        if ($updateReturn != 0) {
            //renderError2('Diff当前版本与上一版本失败', implode("<br />", $updateOutput));
            return 'Diff当前版本与上一版本失败:'. implode("<br />", $updateOutput);
        }
        $updateArray = array();
        $revisionMatch = array();
        foreach ($updateOutput as $line) {
            if (($slashPos = strpos($line,$deployPath)) !== False) {
                $script = trim(substr($line,$slashPos + strlen($deployPath)));
                $status = trim(substr($line,0,2));
                isset($updateStatus[$status]) and $updateArray[$status][] = $script;
            } elseif (preg_match('/(\d+)/', $line, $revisionMatch)) {
                $revision = $revisionMatch[1];
            }
        }
        return array($revision => $updateArray);
    }
    // 同步代码到远端机
    static function pushCodeToHosts($projectInfo, $servers, $delete = false){
        $deployPath = $projectInfo['deployPath'];
        $productionPath = $projectInfo['prodPath'];
        $beforeExec = trim($projectInfo['beforeExec']);
        $afterExec = trim($projectInfo['afterExec']);
        $rsyncUser = trim($projectInfo['rsyncUser']) == '' ? DEFAULT_RSYNC_USER : trim($projectInfo['rsyncUser']);
        $rsyncList = array();
        $beforeList = array();
        $afterList = array();
        $result = array();

        // tidy命令
        if (strlen($beforeExec)>0) {
            $execArray = explode("\n", $beforeExec);
            $cmdArray  = array();
            foreach ($execArray as $cmd) {
                $cmd = trim($cmd," ;");
                if (strlen($cmd)>0) {
                    $cmdArray[] = $cmd;
                }
            }
            if (sizeof($cmdArray) == 0) {
                $beforeExec = '';
            } else {
                $beforeExec = trim(implode(";", $cmdArray)," ;");
            }
        }

        if (strlen($afterExec)>0) {
            $execArray = explode("\n", $afterExec);
            $cmdArray  = array();
            foreach ($execArray as $cmd) {
                $cmd = trim($cmd," ;");
                if (strlen($cmd)>0) {
                    $cmdArray[] = $cmd;
                }
            }
            if (sizeof($cmdArray) == 0) {
                $afterExec = '';
            } else {
                $afterExec = trim(implode(";", $cmdArray)," ;");
            }
        }

        // 增加目录分隔符,否则RSYNC会...
        if (substr($deployPath, -1) != DIRECTORY_SEPARATOR) {
            $deployPath .= DIRECTORY_SEPARATOR;
        }
        if (substr($productionPath, -1) != DIRECTORY_SEPARATOR) {
            $productionPath .= DIRECTORY_SEPARATOR;
        }

        // 支持非open用户
        $prefixCmd = 'cd ~;';
        //    if ($rsyncUser != DEFAULT_RSYNC_USER)
        //    {
        //        $prefixCmd = 'sudo -s;su ' . $rsyncUser . ';cd ~;';
        //    }

        $deleteopt = '';
        if (!$delete) {
            $deleteopt = '';
        } else {
            $deleteopt = "--delete-after";
        }
        foreach ($servers as $server) {
            $addshell = $addaftershell = "";
            if(!strstr($server['idc'], '22')) {
                $addshell = "-e 'ssh -p 22'";
                $addaftershell = "-p 22";
            }
            if($beforeExec) {
                $beforeList[] = $prefixCmd . "ssh ".$addaftershell." {$rsyncUser}@{$server['ip']} \"{$beforeExec}\"";
            }
            $rsyncList[] = $prefixCmd . "rsync -avz ".$addshell." --timeout=30 --delay-updates $deleteopt --exclude '*svn' --include '*' {$deployPath} {$rsyncUser}@{$server['ip']}:{$productionPath}";
            if($afterExec) {
                $afterList[] = $prefixCmd . "ssh ".$addaftershell." {$rsyncUser}@{$server['ip']} \"{$afterExec}\"";
            }
            file_put_contents('/tmp/test_rsync_cmd.log', $rsyncList[0],FILE_APPEND);
        }
        if($beforeExec != '') {
            $result['before'] = static::multiExec($beforeList, 30, 60);
        }
        $result['rsync'] = static::multiExec($rsyncList, 30, 60);
        if($afterExec != '') {
            $result['after'] = static::multiExec($afterList, 30, 60);
        }
        return $result;
    }

    /**
     * @desc   并发执行多个Linux命令
     * @param  array   $cmdList    需要执行的Linux命令行,可以为字符串或数组。
     * @param  integer $maxProcess 最大并行执行进程数
     * @param  integer $timeout    执行总超时设置。
     * @return array   $result     命令的执行结果
     *
     * 使用实例:
     *
     *  $cmdList = array('echo "cmd1"; sleep 3;', 'echo "cmd2"; sleep 1;', 'sleep 3;echo "cmd3";', 'sleep 16;echo "cmd4";');
     *  $return  = multiExec($cmd, 2, 2);
     *  var_dump($return);
     *
     */

    static function multiExec($cmdList, $maxProcess = 24, $timeout = 15)
    {
        array_map(function($a){log_message('error',$a);},$cmdList);
        if(empty($cmdList))
        {
            return False;
        }

        $cmdList = (array)$cmdList;
        $startTime = time();

        $processList = array();
        $result = array();
        for($i = 0; $i < $maxProcess && !empty($cmdList); $i++)
        {
            $handle = proc_open(array_shift($cmdList), array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
            stream_set_blocking($pipes[1], 0);
            $processList[] = array('handle' => $handle, 'pipes' => $pipes);
            $result[] = '';
        }

        while(true)
        {
            foreach($processList as $key => $process)
            {
                if(!is_resource($process['handle']) || feof($process['pipes'][1]))
                {
                    proc_close($process['handle']);
                    unset($processList[$key]);
                    if(!empty($cmdList))
                    {
                        $handle = proc_open(array_shift($cmdList), array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w")), $pipes);
                        stream_set_blocking($pipes[1], 0);

                        $processList[] = array('handle' => $handle, 'pipes' => $pipes);
                        $result[] = '';
                    }
                    continue;
                }
                $result[$key] .= fgets($process['pipes'][1], 1024);
            }

            if(empty($processList) || ((time() - $startTime > $timeout) && $timeout > 0))
            {
                break;
            }
        }

        foreach($processList as $key => $process)
        {
            $status = proc_get_status($process['handle']);
            posix_kill($status['pid'], 9);
            proc_close($process['handle']);
        }
        return $result;
    }
}