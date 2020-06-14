<?php

use yii\helpers\Url; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <link rel="stylesheet" href="/public/admin/ui/css/layui.css">
    <link rel="stylesheet" href="/public/admin/css/custom.css">
</head>
<body class="dark">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">等待提货</div>
                <div class="layui-card-body">
                    <table id="myOrder" lay-filter="myOrder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="orderBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="items">进行提货操作</button>
                        </div>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= Url::to('@web/public/admin/ui/layui.js',true)?>"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'watingpick']);
</script>
</body>
</html>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluids">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <table class="items" id="items" lay-filter="items"></table>
        </div>
        <!-- 顶部工具栏 -->
        <script type="text/html" id="showItemsBar">
    </script>
    </div>
</div>

<!-- 子订单清单 -->
<div style="display:none" id="showOrderItems" class="layui-fluids">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <table class="items" id="items" lay-filter="items"></table>
        </div>
        <!-- 顶部工具栏 -->
        <script type="text/html" id="showOrderItemsBar">
    </script>
    </div>
</div>
