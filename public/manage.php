<?php
/**
 * 后台入口
 * Created by PhpStorm.
 * User: appleimac
 * Date: 19/3/19
 * Time: 5:18
 */

define('APP_PATH', __DIR__ . '/../application/');

require __DIR__ . '/../thinkphp/start.php';

var_dump($request->module());die();