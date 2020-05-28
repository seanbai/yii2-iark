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
          <table id="assign" lay-filter="assign"></table>
          <!-- tool bar -->
          <script type="text/html" id="quoteBar">
            <div class="layui-btn-container">
              <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">操作产品清单</button>
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
}).use(['index', 'assign']);
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
    <script type="text/html" id="action">
      <a class="layui-btn layui-btn-xs" lay-event="set">立即分配</a>
    </script>
  </div>
</div>

<!-- 供货商选择弹层 -->
<div class="manuList" style="display:none">
  <div class="layui-card">
    <div class="layui-card-body">
      <style media="screen">
        .layui-layer-page .layui-layer-content {overflow: inherit !important;}
      </style>
      <form class="layui-form layui-form-pane" id="quoteForm">
        <div class="layui-form-item">
          <label class="layui-form-label">指定供货商</label>
          <div class="layui-input-block">
            <select id="manuList" name="manuList" lay-verify="required" lay-search="">
              <option value="">请邀请供货商</option>
            </select>
          </div>
        </div>

        <div class="layui-form-item">
          <label class="layui-form-label">供货商报价</label>
          <div class="layui-input-block">
            <input type="checkbox" id="open" name="open" lay-skin="switch" lay-text="是|否" lay-filter="price">
          </div>
        </div>

        <div class="layui-form-item" id="priceInput">
          <label class="layui-form-label">单价(欧元)</label>
          <div class="layui-input-block">
            <input type="text" name="title" autocomplete="off" placeholder="请输入金额" class="layui-input">
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
