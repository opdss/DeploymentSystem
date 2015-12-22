<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/14 下午5:43
 * @copyright 7659.com
 */

function getPageBar($totalRecordNum, $currentPage, $pageSize = 10,$pn = 'page') {
    $return_url = '';
    $re_str = '{$page}';
    $url_arr = parse_url($_SERVER['REQUEST_URI']);
    $url_arr['path'] = substr($url_arr['path'],0,strrpos($url_arr['path'],'.'));
    if(empty($url_arr['query'])){
        $url = site_url($url_arr['path'].'?'.$re_str);
    }else {
        $url = site_url($url_arr['path'] . '?' . preg_replace("/$pn=\d*&?/", $re_str, $url_arr['query']));
        strpos($url,$re_str) === false and $url.='&'.$re_str;
    }
    $totalPage = ceil($totalRecordNum / $pageSize);

    //如果总页数小于等于1,或者请求页大于总页数，则不显示分页块
    if ($totalPage <= 1 || $currentPage > $totalPage) {
        return '';
    }
    $return_url .= '&nbsp; &nbsp; &nbsp; &nbsp;  总共'.$totalRecordNum.'条&nbsp; &nbsp; 分为'.$totalPage.'页';
    $return_url .= '<div class="floatRightPadding20"> <ul class="pagination">';

    $previous = $currentPage - 1;
    $next = $currentPage + 1;
    if ($currentPage <= 1) {
        $return_url .= '<li class="first"><a href="javascript:void(0)" class="disable">&laquo;</a></li>';
        $return_url .= '<li class="previous"><a href="javascript:void(0)" class="disable">&lsaquo;</a></li>';
    } else {
        $return_url .= '<li class="first"><a href="'.str_replace($re_str,$pn.'=1',$url).'">&laquo;</a></li>';
        $return_url .= '<li class="previous"><a href="'.str_replace($re_str,$pn.'='.$previous,$url).'">&lsaquo;</a></li>';
    }
    for($i = 1; $i <= $totalPage; $i++) {
        if ($i == $currentPage) {
            $return_url .= '<li><a href="'.str_replace($re_str,$pn.'='.$i,$url).'" class="current">' .$i. '</a></li>';
        } else {
            $return_url .= '<li><a href="'.str_replace($re_str,$pn.'='.$i,$url).'">' .$i. '</a></li>';
        }
    }
    if ($currentPage == $totalPage) {
        $return_url .= '<li class="next"><a href="javascript:void(0)" class="disable">&rsaquo;</a></li>';
        $return_url .= '<li class="last"><a href="javascript:void(0)" class="disable">&raquo;</a></li>';
    } else {
        $return_url .= '<li class="next"><a href="'.str_replace($re_str,$pn.'='.$next,$url).'">&rsaquo;</a></li>';
        $return_url .= '<li class="last"><a href="'.str_replace($re_str,$pn.'='.$totalPage,$url).'">&raquo;</a></li>';
    }
    $return_url .= '</ul></div><!--pagination-->';
    return $return_url;
}