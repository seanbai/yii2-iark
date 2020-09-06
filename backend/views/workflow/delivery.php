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
                <div class="layui-card-header">已提货</div>
                <div class="layui-card-body">
                    <table id="myOrder" lay-filter="myOrder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="orderBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm " lay-event="items">产品数据</button>
                            <button class="layui-btn layui-btn-sm" lay-event="add">填写运输信息</button>
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
    }).use(['index', 'delivery']);
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

<!-- 填写物流信息 -->
<div style="display:none" id="information" class="layui-fluids">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane">
                <div class="layui-form-item">
                    <label class="layui-form-label">运输信息</label>
                    <div class="layui-input-block">
                        <input type="text"  id="transport" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item"  data-role="pay-deposit">
                    <label class="layui-form-label" >预期到港时间</label>
                    <div class="layui-input-block">
                        <input type="text" value="2020-01-01"  id="port-time" class="layui-input" >
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">服务费用</label>
                        <div class="layui-input-block">
                            <input type="hidden" name="att" id="att">
                            <button type="button" class="layui-btn layui-btn-primary" id="attachment"><i class="layui-icon"></i>上传附件</button>
                            <small>仅支持 zip|rar|7z 格式压缩包文件</small>
                        </div>
                    </div>
                </div>


                <div class="layui-form-item" data-role="pay-deposit">
                    <label class="layui-form-label">运输发票</label>
                    <div class="layui-input-block">
                        <input type="hidden" id="transport-fee-file" />
                        <div class="layui-upload" id="deposit-upload" style="margin: 20px 20px;">
                            <button type="button" class="layui-btn" id="deposit-img">上传支付凭证</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" style=" width: 105px;height: 105px;margin: 0 10px 10px 0;" id="deposit-img-tmp">
                                <p id="demoText"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item layui-form-text"  data-role="subPay">
                    <input type="text" name="orderId" id="orderId" value="1" hidden>
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="payOrderForm">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>