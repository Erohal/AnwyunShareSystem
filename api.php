<?php
/**
 * Created by PhpStorm.
 * User: Lahore
 * Date: 2019/2/1
 * Time: 19:22
 * 安网云分享接口
 */
$return = array(
    'code' => 0
    ,'msg' => '内部错误'
);
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
function handleT($handle,$username){
    global $return;//使用到了$return全局变量
    $uid = null;
    $conn = new DBS();
    $sql = "SELECT uid FROM `用户` WHERE `用户名` = '$username'";
    if(($response = $conn->query($sql))!=null){//用户存在的情况下
        $uid = $response->fetch_assoc()['uid'];
        $uuid = $uid * 2019 - 5;//处理得到uuid
        if($handle == 'new'){

        }else if($handle == 'old'){

        }
    }else{
        $return['msg'] = '用户不存在';
    }
}
$username = isset($_POST['username'])?$_POST['username']:null;
$handle = isset($_POST['handle'])?$_POST['handle']:null;
if($username && $handle){
    if($handle == 'new' && $handle == 'old'){
        handleT($handle,$username);
    }else{
        $return['msg'] = '参数名称不正确';
    }
}else{
    $return['msg'] = '没有传入参数';
}
$return = json_encode($return);
echo $return;