<?php

use yii\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <link rel="stylesheet" href="<?= Url::to('@web/public/admin/ui/css/layui.css')?>">
    <link rel="stylesheet" href="<?= Url::to('@web/public/admin/css/custom.css')?>">
</head>
<body class="dark">

<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm12">

            <div class="layui-card">
                <div class="layui-card-header">
                    Waiting For Quote
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="myOrder" lay-filter="myOrder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="items">Products List</button>
                        </div>
                    </script>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="<?= Url::to('@web/public/admin/ui/layui.js')?>"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'quotation']);
</script>
</body>
</html>

<div class="layui-fluid" id="showItems" style="display:none">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm12">
            <table id="items" lay-filter="items"></table>
        </div>
    </div>
</div>
