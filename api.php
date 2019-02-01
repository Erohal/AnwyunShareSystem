<?php
/**
 * Created by PhpStorm.
 * User: Lahore
 * Date: 2019/2/1
 * Time: 19:22
 * 安网云分享接口
 */
require_once ('cron/dbs.class.php');
function getIp()//获取用户的IP地址，也有可能是unknown
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";

    return $ip;
}
function newT($username){//添加一个分享链接
    $conn = new DBS();
    $sql = "SELECT uid FROM `用户` WHERE `用户名` = '$username'";
if(($respose = $conn->query($sql))!=null){
    $uid = $respose->fetch_assoc()['uid'];
}
    $uuid = $uid * 2019 - 5;//用户唯一推广链接uuid
    $sql = "";
}
function oldT($username){//这边不知道是干嘛的.....
    $conn = new DBS();
    $sql = "SELECT uid FROM `用户` WHERE `用户名` = '$username'";
    $conn->query($sql);
}
$return = array(
    'code' => 1,
    'msg' => '内部错误'
);
if(!($handle = isset($_POST['handle'])?$_POST['handle']:null) && !($handle = isset($_GET['handle'])?$_GET['handle']:null)){
    $return['msg'] = '没有选择任何操作';
}else{
    if(!($$username = isset($_POST['$username'])?$_POST['$username']:null) && !($$username = isset($_GET['$username'])?$_GET['$username']:null)){
        $return['msg'] = '用户ID信息丢失';
    }else{
        if($handle == 'new'){
            newT($username);
        }else if($handle == 'old'){
            oldT($username);
        }else{
            $return['msg'] = '未知的操作类型';
        }
    }
}
$return = json_encode($return);
echo $return;