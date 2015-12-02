<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/12/2 下午7:24
 * @copyright 7659.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller{

    function __construct(){
        parent::__construct();
    }

    function index(){
        echo 'home page';
    }
}