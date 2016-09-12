<?php
/**
 * Created by PhpStorm.
 * User: wdmzj
 * Date: 2016/9/12
 * Time: 12:12
 */

if(!isset($_POST['studentNumber']) && !isset($_POST['password'])){
    echo '登录失败';
}else{
    session_start();
    $_SESSION['studentNumber'] = htmlspecialchars(trim($_POST['studentNumber']));
    $_SESSION['password'] = htmlspecialchars(trim($_POST['password']));
    header("Location: mobileIndex.php");
    echo '登录成功';
}