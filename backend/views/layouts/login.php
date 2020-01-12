<?php
/**
 * Created by PhpStorm.
 * Date: 2016/7/18
 * Time: 19:17
 */
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title><?=Yii::$app->params['projectTitle']?></title>


    <link rel="stylesheet" href="/public/admin/ui/css/layui.css">
    <link rel="stylesheet" href="/public/admin/css/custom.css">
    <link href="https://fonts.font.im/css?family=Open+Sans:300,400,700" rel="stylesheet">

    <script type="text/javascript">
        window.jQuery || document.write("<script src='/public/assets/js/jquery.min.js'>" + "<" + "/script>");
    </script>

</head>
<body class="login-layout light-login">

<div class="login-page">
    <div class="layui-container">
        <div class="layui-row flexbox">
            <div class="layui-col-md5">
                <div class="layui-card">
                    <div class="layui-card-header">Order Erp System</div>
                    <div class="layui-card-body">
                        <div class="title">
                            <h3>Sign In</h3>
                            <p>Enter your username and password to access admin panel.</p>
                        </div>
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row flexbox powerby">
            <p>Power by Daniel & Sean. Version 1.0</p>
        </div>
    </div>
</div>

</body>
</html>
