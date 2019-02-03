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
function isAccept($username,$log){//判断某用户是否已经领取过红包 返回true即是可领取
    //数据储存格式  用户|用户|用户|
    //开头没有| 以|结尾 我需要share表中log
    $return = true;
    $charline = null;
    for($i = 0 ; $i < strlen($log) ; $i++){
        if($log[$i] == '|'){
            if($charline == $username){//用户已经领取
                $return = false;
                break;
            }else{
                $charline = '';
            }
        }else{
            $charline .= $log[$i];
        }
    }
    return $return;
}
function registerTimeAcceptable($Utime,$Dtime){//Utime为邀请人注册时间,Dtine为被邀请人注册时间，要求Dtime > Utime
    $return = false;
    if($Dtime > $Utime){
        $return = true;
    }
    return $return;
}
function handleT($handle,$username){
    global $return;//使用到了$return全局变量
    $uid = null;
    $conn = new DBS();
    $sql = "SELECT * FROM `用户` WHERE `用户名` = '$username'";
    if(($response = $conn->query($sql)->fetch_assoc())!=null){//用户存在的情况下
        $Dtime = $response['注册时间'];
        $uid = $response['uid'];
        $uuid = $uid * 2019 - 5;//处理得到uuid
        if($handle == 'new'){//添加邀请链接操作
            $sql = "SELECT * FROM `share` WHERE username='$username'";
            if(($share = $conn->query($sql)->fetch_assoc())!=null){//用户已经生成红包
                $return['msg'] = '你已经生成了分享链接';
            }else{//用户第一次操作
                //写数据库操作
                $sql = "INSERT INTO `share`(`uid`, `username`, `uuid`, `successn`,`mtime`,`log`) VALUES ($uid,'$username',$uuid,0,now(),'')";
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
                    $Utime = $response['mtime'];
                    //确保领取人不是自己
                    if(isAccept($username,$response['log'])){//判断是否已经领取过了
                        if(registerTimeAcceptable($Utime,$Dtime)){
                            $Dusername = $response['username'];//取出uuid对应的用户的用户名
                            $selectMoney = "SELECT `预存款` FROM `用户` WHERE `用户名` = '$username'";//小心被邀请人已经充值再领红包的情况
                            $Dmoney = $conn->query($selectMoney)->fetch_assoc()['预存款'];//取出存款
                            if($Dusername != $username){
                                //开始一波骚操作
                                $Dsuccessn = $response['successn'] + 1;
                                $Dmoney += 5;
                                $newLog = $response['log'].$username.'|';
                                $addP = "UPDATE `share` SET `successn` = $Dsuccessn ,`log` = '$newLog' WHERE `uuid` = $share";
                                $addM = "UPDATE `用户` SET `预存款` = $Dmoney WHERE `用户名` = '$username'";
                                if($conn->query($addP) && $conn->query($addM)){//积分+100成功 并且 被邀请人加款成功
                                    $return['code'] = 1;
                                    $return['msg'] = '领取成功';
                                }else{//数据库执行失败
                                    $return['msg'] = '数据库错误 code:0x00';
                                }
                            }else{
                            $return['msg'] = '领取人不能是自己';
                            }
                        }else{
                            //如果是自己领取
                            $return['msg'] = '用户身份信息不符合要求';
                        }
                    }else{
                        $return['msg'] = '你已经领取过了哦~';
                    }
                }else{//如果没有这个uuid
                    $return['msg'] = '邀请码错误 code:0x00';
                }
            }else{//如果没有给出分享着uuid
               $return['msg'] = '邀请码错误 code:0x01';
            }
        }else if($handle == 'query'){
            $sql = "SELECT * FROM `share` WHERE `username` = '$username'";
            if(($response = $conn->query($sql)->fetch_assoc())!=null){
                $Dsuccess = $response['successn'];
                $return['code'] =1;
                $return['msg'] = '获取成功';
                $return['fxsj'] = $Dsuccess;
                $return['jfsj'] = $Dsuccess * 100;
            }else{
                $return['msg'] = '数据查询失败 请检查用户名是否正确或联系客服';
            }
        }else if($handle = 'change'){//兑换操作
            $sql = "SELECT * FROM `share` WHERE `username` = '$username'";
            if(($response = $conn->query($sql)->fetch_assoc())!=null){
                $Dsuccess = $response['successn'];//用户的积分/100
                if($Dsuccess >= 5){
                    $sql = "UPDATE `share` SET `successn` = 0 WHERE `username` = '$username'";//将用户的积分置0
                    $conn -> query($sql);
                    $selectMoney = "SELECT `预存款` FROM `用户` WHERE `用户名` = '$username'";
                    $Dmoney = $conn->query($selectMoney)->fetch_assoc()['预存款'];
                    $Dmoney += $Dsuccess;
                    //重新写回去
                    $addM = "UPDATE `用户` SET `预存款` = $Dmoney WHERE `用户名` = '$username'";
                    if($conn->query($addM)){
                        $return['code'] = 1;
                        $return['msg'] = '兑换成功';
                    }else{
                        $return['msg'] = '兑换失败 请联系管理员';
                    }
                }else{
                    $return['msg'] = '您的积分不足500';
                }
            }else{
                $return['msg'] = '数据查询失败 请检查用户名是否正确或联系客服';
            }
        }
    }else{
        $return['msg'] = '用户不存在';
    }
}
$username = isset($_POST['User'])?$_POST['User']:null;
$handle = isset($_POST['Type'])?$_POST['Type']:null;
if($username && $handle){
    if($handle == 'new' || $handle == 'old' || $handle == 'query' || $handle='change'){
        handleT($handle,$username);
    }else{
        $return['msg'] = '参数名称不正确';
    }
}else{
    $return['msg'] = '没有传入参数';
}
$return = json_encode($return);
echo $return;