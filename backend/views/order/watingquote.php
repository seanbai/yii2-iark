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
                <div class="layui-card-header">订单报价</div>
                <div class="layui-card-body">
                    <table id="myOrder" lay-filter="myOrder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="orderBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm " lay-event="items">查看报价并付款</button>
                            <button class="layui-btn layui-btn-sm " lay-event="payOrder">付款确认</button>
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
    }).use(['index', 'watingquote']);
</script>
</body>
</html>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluid">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <table class="items" id="items" lay-filter="items"></table>
        </div>
        <!-- 顶部工具栏 -->
        <script type="text/html" id="showItemsBar">
    </script>
    </div>
</div>

<!--支付凭证上传-->
<div class="deposit-upload" style="display: none;">
    <div class="layui-card">
        <div class="layui-card-body">应支付定金：<span id="deposit-amount">1000</span></div>
    </div>
    <input type="hidden" id="deposit-upload-file" />
    <div class="layui-upload" id="deposit-upload" style="margin: 20px 20px;">
        <button type="button" class="layui-btn" id="deposit-img">上传支付凭证</button>
        <div class="layui-upload-list">
            <img class="layui-upload-img" style=" width: 105px;height: 105px;margin: 0 10px 10px 0;" id="deposit-img-tmp">
            <p id="demoText"></p>
        </div>
    </div>
    <div class="layui-card">
        <div class="layui-card-body">上传凭证后，请确认无误后再点击“确认”</div>
    </div>
</div>

<div style="display:none; padding: 20px;" id="payOrderForm" class="layui-fluids">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane">
                <!-- 自动计算总金额的50% -->
                <div class="layui-form-item">
                    <label class="layui-form-label">订单报价</label>
                    <div class="layui-input-block">
                        <input type="text"  id="order-total" value="20000" class="layui-input" disabled>
                    </div>
                </div>

                <!--定金-->
                <div class="layui-form-item" style="display:none"  data-role="pay-deposit">
                    <label class="layui-form-label">应收定金</label>
                    <div class="layui-input-block">
                        <input type="text" value="10000"  id="order-deposit" class="layui-input" disabled>
                    </div>
                </div>
<!--                <div class="layui-form-item" style="display:none"  data-role="pay-deposit">-->
<!--                    <label class="layui-form-label">是否支付</label>-->
<!--                    <div class="layui-input-block">-->
<!--                        <input type="checkbox" name="pay_deposit" lay-skin="switch" lay-text="是|否">-->
<!--                    </div>-->
<!--                </div>-->

                <!-- 应收尾款% -->
                <div class="layui-form-item" style="display:none" data-role="pay-balance">
                    <label class="layui-form-label">应收尾款</label>
                    <div class="layui-input-block">
                        <input type="text" value="10000" id="order-balance" class="layui-input" disabled>
                    </div>
                    <!--支付凭证上传-->
                    <div class="pay-upload">
                        <input type="hidden" id="balance-upload-file" name="balance-upload-file" />
                        <div class="layui-upload" id="balance-upload" style="margin: 20px 20px;">
                            <button type="button" class="layui-btn" id="balance-img">上传付款凭证</button>
                            <div class="layui-upload-list">
                                <img class="layui-upload-img" style=" width: 105px;height: 105px;margin: 0 10px 10px 0;"
                                     id="pay-file-balance">
                                <p id="demoText-balance"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item" style="display:none"  data-role="pay-balance2">
                    <label class="layui-form-label">是否支付</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="pay_balance" lay-skin="switch" lay-text="是|否">
                    </div>
                </div>

                <div class="layui-form-item layui-form-text" style="display:none"  data-role="subPay">
                    <input type="text" name="orderId" id="orderId" hidden>
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="payOrderForm">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>