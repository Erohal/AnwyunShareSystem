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
function handleT($handle,$username){
    global $return;//使用到了$return全局变量
    $uid = null;
    $conn = new DBS();
    $sql = "SELECT * FROM `用户` WHERE `用户名` = '$username'";
    if(($response = $conn->query($sql)->fetch_assoc())!=null){//用户存在的情况下
        $uid = $response['uid'];
        $uuid = $uid * 2019 - 5;//处理得到uuid
        if($handle == 'new'){//添加邀请链接操作
            $sql = "SELECT * FROM `share` WHERE username='$username'";
            if(($share = $conn->query($sql)->fetch_assoc())!=null){//用户已经生成红包
                $return['msg'] = '你已经生成了分享链接';
            }else{//用户第一次操作
                //写数据库操作
                $sql = "INSERT INTO `share`(`uid`, `username`, `uuid`, `successn`,`mtime`) VALUES ($uid,'$username',$uuid,0,now())";
                if($conn->query($sql)){//如果sql命令执行成功
                    $url=dirname('http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);//这里可能会出错，到时候再来调试
                    $return['code'] = 1;
                    $return['msg'] = $url . '/good.php?uuid=' . $uuid;//返回生成好的地址
                }else{//执行失败的话
                    $return['msg'] = '执行数据库任务失败';
                }
            }
        }else if($handle == 'old'){//设置被分享用户的骚操作
            $share = isset($_POST['Share'])?$_POST['Share']:null;
            if($share){//uuid存在的情况下
                $sql = "SELECT * FROM `share` WHERE `uuid` = '$share'";
                if(($response = $conn->query($sql)->fetch_assoc())!=null){//确保记录存在
                    //确保领取人不是自己
                    $Dusername = $response['username'];//取出uuid对应的用户的用户名
                    if($Dusername != $username){

                    }else{
                        //如果是自己领取
                        $return['msg'] = '领取人不能是自己';
                    }
                }else{//如果没有这个uuid
                    $return['msg'] = '没有这个邀请码';
                }
            }else{//如果没有给出分享着uuid
               $return['msg'] = '没有邀请码';
            }
        }
    }else{
        $return['msg'] = '用户不存在';
    }
}
$username = isset($_POST['User'])?$_POST['User']:null;
$handle = isset($_POST['Type'])?$_POST['Type']:null;
if($username && $handle){
    if($handle == 'new' || $handle == 'old'){
        handleT($handle,$username);
    }else{
        $return['msg'] = '参数名称不正确';
    }
}else{
    $return['msg'] = '没有传入参数';
}
$return = json_encode($return);
echo $return;