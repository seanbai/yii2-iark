<?php

use yii\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <link rel="stylesheet" href="<?= Url::to('@web/public/admin/ui/css/layui.css',true)?>">
    <link rel="stylesheet" href="<?= Url::to('@web/public/admin/css/custom.css',true)?>">
</head>
<body class="dark">

<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">Waiting For Quote</div>
        <div class="layui-card-body">
          <table id="quote" lay-filter="quote"></table>
          <!-- tool bar -->
          <script type="text/html" id="quoteBar">
            <div class="layui-btn-container">
              <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">查看报价明细并操作</button>
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
}).use(['index', 'quote']);
</script>
</body>
</html>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <table class="items" id="items" lay-filter="items"></table>
    </div>
  </div>
</div>

<!-- 查看子订单 -->
<div style="display:none" class="subOrder" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <table class="items" id="subOrder" lay-filter="subOrder"></table>
    </div>
  </div>
</div>
