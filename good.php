<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>安网云领取红包</title>
    <script src="layui/layui.all.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="layui/css/layui.css" />
    <style type="text/css">
        #sec {
            width: 300px;
            height: 100px;
            margin: 0 auto;
            margin-top: 15%;
            border-radius: 10px;
            box-shadow: 2px 1px 20px #B3B3B3;
        }
        #sec-sec {
            width: 232px;
            height: 100px;
            margin: 0 auto;
            background: #fff;
            line-height: 100px;

        }

        #sec-sec button {
            width: 70px;
            display: inline-block;
            border-radius: 0 10px 10px 0;
            margin-left: -5px;
            margin-top: -3px;
        }

        #sec input[name='userName'] {
            width: 150px;
            display: inline-block;
            border-radius: 10px 0 0 10px;
        }
        #foot{
            width: 700px;
            height: 300px;
            margin: 0 auto;
            margin-top: 50px;
        }
        #foot-text{
            width: 580px;
            height: 300px;
            margin-left: 20%;
            color: #B3B3B3;
        }
    </style>
</head>
<body bgcolor="">
<div id="sec">
    <div id="sec-sec">
        <input type="text" name="userName"  required lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
        <button id="scbut" type="button" class="layui-btn layui-btn-normal ">领取</button>
    </div>
</div>
<div id="foot">
    <div id="foot-text">
        <span>1.输入安网云登录用户名即可获得${分享者用户名}送给你的现金红包！</span><br>
        <span>2.领取后现金红包将存入你的安网云账户预存款</span><br>
        <span>3.禁止代理IP、刷注册等作弊行为，安网云不定期进行人工检查。</span><br>
        <span>4.作弊者将直接禁封账户以及账户下所有产品不予退款。</span><br>
        <span>5.本活动解释权归安网云所有</span>
    </div>
</div>
</body>
<script>
    ;
    ! function() {
        var form = layui.form,
            layer = layui.layer;
    };
    $("#scbut").click(function(){
        var val=$("input[name='userName']").val();
        if(val==""){
            layer.msg("用户名不许为空呦~");
        }

    })
</script>
</html>