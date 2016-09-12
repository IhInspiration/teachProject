<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Title</title>
    <style type="text/css">
        *{padding: 0; margin: 0; font-size: 18px;}
        #loginDiv{
            width: 90%;
            padding: 5%;
            margin: auto;
        }
        #loginDiv div{
            margin: 30px auto;
        }
        #title{
            text-align: center;
            font-size: 24px;
        }
        #numLabel, #psdLabel{
            width: 20%;
            float: left;
            text-align: right;
        }
        #studentNumber, #password{
            width: 75%;
            float: left;
        }
        #loginSubmit{
            width: 92%;
            border: none;
            background-color: #3879D9;
            color: white;
            text-align: center;
            line-height: 30px;
            margin: 0 auto;
            display: block;
        }
        nav{
            margin: 0 auto;
        }
        nav a{
            width: 40vw;
            height: 40vw;
            color: white;
            text-align: center;
            text-decoration: none;
            line-height: 40vw;
            background-color: #3879d9;
            display: block;
            float: left;
            margin: 2.5vw;
            cursor: pointer;
        }
    </style>
</head>
<body style="width: 100%;">
    <div id="loginDiv">
        <?php
            session_start();
            if(!isset($_SESSION['studentNumber'])){ ?>
            <form action="login.php" method="post">
                <div>
                    <h1 id="title">教务系统登录</h1>
                </div>

                <div>
                    <label id="numLabel" for="studentNumber">学号：</label><input type="text" id="studentNumber" name="studentNumber" placeholder="请输入学号" /><br/>
                </div>
                <div>
                    <label id="psdLabel" for="password">密码：</label><input type="password" id="password" name="password" placeholder="请输入密码" /><br/>
                </div>
                <div>
                    <input type="submit" id="loginSubmit" value="登录" />
                </div>
            </form>
        <?php }else{
            echo "你好,".$_SESSION['studentNumber'];
        } ?>
        <nav>
            <a href="evaluate.php">一键评价</a>
            <a>平均绩点</a>
            <a>学位绩点</a>
            <a>课表查询</a>
            <div style="clear:both;"></div>
        </nav>
        <br/>
        <hr/>
        <p style="text-align: center;line-height: 25px;">仅供学习测试使用@jackwang</p>
    </div>

</body>
</html>