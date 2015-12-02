<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/6/15 下午5:55
 * @copyright 7659.com
 */
class Verify{

    /**
    匹配手机号码
    规则：
    手机号码基本格式：
    前面三位为：
    移动：134-139 147 150-152 157-159 182 187 188
    联通：130-132 155-156 185 186 170
    电信：133 153 180 189
    后面八位为：
    0-9位的数字
     */
    public function pregPhone($test){
        $rule  = "/^((13[0-9])|147|(15[0-35-9])|170|180|182|183|(18[5-9]))[0-9]{8}$/A";
        return (bool)preg_match($rule,$test);
    }
    /**
    匹配邮箱
    规则：
    邮箱基本格式是  *****@**.**
    @以前是一个 大小写的字母或者数字开头，紧跟0到多个大小写字母或者数字或 . _ - 的字符串
    @之后到.之前是 1到多个大小写字母或者数字的字符串
    .之后是 1到多个 大小写字母或者数字或者.的字符串
     */
    public function pregEmail($test){
        $rule = '/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A';
        return (bool)preg_match($rule,$test);
    }
    /**
    电话号码匹配
    电话号码规则：
    区号：3到5位，大部分都是四位，北京(010)和上海市(021)三位，西藏有部分五位，可以包裹在括号内也可以没有
    如果有区号由括号包裹，则在区号和号码之间可以有0到1个空格，如果区号没有由括号包裹，则区号和号码之间可以有两位长度的 或者-
    号码：7到8位的数字
    例如：(010) 12345678  或者 (010)12345678 或者 010  12345678 或者 010--12345678
     */
    public function pregTelphone($test){
        $rule = '/^(\(((010)|(021)|(0\d{3,4}))\)( ?)([0-9]{7,8}))|((010|021|0\d{3,4}))([- ]{1,2})([0-9]{7,8})$/A';
        return (bool)preg_match($rule,$test);
    }
    /**
    匹配url
    url规则：
    例
    协议://域名（www/tieba/baike...）.名称.后缀/文件路径/文件名
    http://zhidao.baidu.com/question/535596723.html
    协议://域名（www/tieba/baike...）.名称.后缀/文件路径/文件名?参数
    www.lhrb.com.cn/portal.php?mod=view&aid=7412
    协议://域名（www/tieba/baike...）.名称.后缀/文件路径/文件名/参数
    http://www.xugou.com.cn/yiji/erji/index.php/canshu/11

    协议：可有可无，由大小写字母组成；不写协议则不应存在://，否则必须存在://
    域名：必须存在，由大小写字母组成
    名称：必须存在，字母数字汉字
    后缀：必须存在，大小写字母和.组成
    文件路径：可有可无，由大小写字母和数字组成
    文件名：可有可无，由大小写字母和数字组成
    参数:可有可无，存在则必须由?开头，即存在?开头就必须有相应的参数信息
     */
    public function pregURL($test){
        $rule = '/^(([a-zA-Z]+)(:\/\/))?([a-zA-Z]+)\.(\w+)\.([\w.]+)(\/([\w]+)\/?)*(\/[a-zA-Z0-9]+\.(\w+))*(\/([\w]+)\/?)*(\?(\w+=?[\w]*))*((&?\w+=?[\w]*))*$/';
        return (bool)preg_match($rule,$test);
    }
    /**
    匹配身份证号
    规则：
    15位纯数字或者18位纯数字或者17位数字加一位x
     */
    public function pregIC($test){
        $rule = '/^(([0-9]{15})|([0-9]{18})|([0-9]{17}x))$/';
        return (bool)preg_match($rule,$test);
    }
    /**
    匹配ip
    规则：
     **1.**2.**3.**4
     **1可以是一位的 1-9，两位的01-99，三位的001-255
     **2和**3可以是一位的0-9，两位的00-99,三位的000-255
     **4可以是一位的 1-9，两位的01-99，三位的001-255
    四个参数必须存在
     */
    public function pregIP($test){
        $rule = '/^((([1-9])|((0[1-9])|([1-9][0-9]))|((00[1-9])|(0[1-9][0-9])|((1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))))\.)((([0-9]{1,2})|(([0-1][0-9]{2})|(2[0-4][0-9])|(25[0-5])))\.){2}(([1-9])|((0[1-9])|([1-9][0-9]))|(00[1-9])|(0[1-9][0-9])|((1[0-9]{2})|(2[0-4][0-9])|(25[0-5])))$/';
        return (bool)preg_match($rule,$test);
    }
    /**
    匹配时间
    规则：
    形式可以为：
    年-月-日 小时:分钟:秒
    年-月-日 小时:分钟
    年-月-日
    年：1或2开头的四位数
    月：1位1到9的数；0或1开头的两位数，0开头的时候个位数是1到9的数，1开头的时候个位数是1到2的数
    日：1位1到9的数；0或1或2或3开头的两位数，0开头的时候个位数是1到9的数，1或2开头的时候个位数是0到9的数，3开头的时候个位数是0或1
    小时：0到9的一位数；0或1开头的两位数，个位是0到9；2开头的两位数，个位是0-3
    分钟：0到9的一位数；0到5开头的两位数，个位是0到9；
    分钟：0到9的一位数；0到5开头的两位数，各位是0到9
     */
    public function pregTime($test){
        $rule ='/^(([1-2][0-9]{3}-)((([1-9])|(0[1-9])|(1[0-2]))-)((([1-9])|(0[1-9])|([1-2][0-9])|(3[0-1]))))( ((([0-9])|(([0-1][0-9])|(2[0-3]))):(([0-9])|([0-5][0-9]))(:(([0-9])|([0-5][0-9])))?))?$/';
        return (bool)preg_match($rule,$test);
    }
}