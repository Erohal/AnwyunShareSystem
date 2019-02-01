<?php
/**
 * Created by PhpStorm.
 * User: Lahore
 * Date: 2019/2/1
 * Time: 19:22
 * 安网云分享接口
 */
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
$return = array(
    'code' => 1,
    'msg' => '内部错误'
);
if(!($handle = isset($_POST['handle'])?$_POST['handle']:null) && !($handle = isset($_GET['handle'])?$_GET['handle']:null)){
    $return['msg'] = '没有选择任何操作';
}else{
    if(!($id = isset($_POST['id'])?$_POST['id']:null) && !($id = isset($_GET['id'])?$_GET['id']:null)){
        $return['msg'] = '用户ID信息丢失';
    }else{
        if($handle == 'new'){

        }else if($handle == 'old'){

        }else{
            $return['msg'] = '未知的操作类型';
        }
    }
}
$return = json_encode($return);
echo $return;