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
        <div class="layui-card-header">Waiting For Receive Money</div>
        <div class="layui-card-body">
          <table id="quote" lay-filter="quote"></table>
          <!-- tool bar -->
          <script type="text/html" id="quoteBar">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">查看商品清单</button>
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="receiveNotice">发送收款通知</button>
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="goodslist">提货清单</button>
            </div>
          </script>
          <script type="text/html" id="action">
            <a class="layui-btn layui-btn-xs" lay-event="confirm">收款确认</a>
<!--            <a class="layui-btn layui-btn-xs" lay-event="confirmTax">税金收取清单</a>-->
            <a class="layui-btn layui-btn-xs" lay-event="confirmSupprot">服务费收取清单</a>
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
}).use(['index', 'receive']);
</script>

<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
  {{#  if(d.status == 5){ }}
    <span class="tag layui-bg-cyan">确认报价</span>
  {{# } else { }}
    <span class="tag layui-bg-red">尾款待确认</span>
  {{# } }}
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

<!--发送收款通知-->
<div style="display:none;margin-top: 16px" id="receiveNotice" class="layui-fluids">
    <form class="layui-form">
        <input type="hidden" name="id" id="noticeId" />
        <div class="layui-form-item">
            <label class="layui-form-label">订单总价</label>
            <div class="layui-input-block" style="width: 50%;">
                <input type="text" id="notice-total" autocomplete="off" class="layui-input" value="20000" disabled/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">通知支付定金</label>
            <div class="layui-input-inline">
                <input type="checkbox" lay-text="是|否" name="deposit_notice" lay-skin="switch">
            </div>
            <div class="layui-form-mid layui-word-aux" id="aux-deposit">应收定金10000</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">通知支付尾款</label>
            <div class="layui-input-inline">
                <input type="checkbox" lay-text="是|否" name="balance_notice" lay-skin="switch">
            </div>
            <div class="layui-form-mid layui-word-aux" id="aux-balance">应收尾款10000</div>
        </div>
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">通知支付税金</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="checkbox" lay-text="是|否" name="tax_notice" lay-skin="switch">-->
<!--            </div>-->
<!--            <div class="layui-form-mid layui-word-aux" id="aux-tax">应收金10000</div>-->
<!--        </div>-->
<!--        <div class="layui-form-item">-->
<!--            <label class="layui-form-label">通知支付服务费</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="checkbox" lay-text="是|否" name="fuwu_notice" lay-skin="switch">-->
<!--            </div>-->
<!--            <div class="layui-form-mid layui-word-aux" id="aux-fuwu">应收服务费10000</div>-->
<!--        </div>-->
        <!-- 提交按钮 -->
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="receiveNotice">确认</button>
        </div>
    </form>
</div>
<!-- 确认收款表单 -->
<div style="display:none" id="confirmPayment" class="layui-fluids">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <form class="layui-form layui-form-pane">
        <!-- 自动计算总金额的50% -->
        <div class="layui-form-item">
          <label class="layui-form-label">订单总价</label>
          <div class="layui-input-block">
            <input type="text" name="quote" id="total" value="20000" class="layui-input" disabled>
          </div>
        </div>
        <!-- 自动计算总金额的50% -->
        <div class="layui-form-item">
          <label class="layui-form-label">应收定金</label>
          <div class="layui-input-block">
            <input type="text" value="10000" name="deposit" id="deposit" disabled class="layui-input" >
          </div>
        </div>
        <!-- 实际收款金额 -->
        <div class="layui-form-item">
          <label class="layui-form-label">实收定金</label>
          <div class="layui-input-block">
            <input type="text" name="receive_deposit"  id="receive_deposit" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 自动计算总金额的50% -->
        <div class="layui-form-item">
          <label class="layui-form-label">应收尾款</label>
          <div class="layui-input-block">
            <input type="text" value="10000" id="balance" name="balance" class="layui-input" disabled>
          </div>
        </div>
        <!-- 实际收款金额 -->
        <div class="layui-form-item">
          <label class="layui-form-label">实收尾款</label>
          <div class="layui-input-block">
            <input type="text" name="receive_balance" id="receive_balance" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 自动计算总金额的50% -->
        <div class="layui-form-item">
          <label class="layui-form-label">应收税金</label>
          <div class="layui-input-block">
            <input type="text" value="10000" name="tax" id="tax" class="layui-input">
          </div>
        </div>
        <!-- 实际收款金额 -->
        <div class="layui-form-item">
          <label class="layui-form-label">实收税金</label>
          <div class="layui-input-block">
            <input type="text" name="receive_tax" id="receive_tax" autocomplete="off" class="layui-input">
          </div>
        </div>
          <!-- 自动计算总金额的50% -->
          <div class="layui-form-item">
              <label class="layui-form-label">应收服务费</label>
              <div class="layui-input-block">
                  <input type="text" value="10000" name="fuwu" id="fuwu" class="layui-input">
              </div>
          </div>
          <!-- 实际收款金额 -->
          <div class="layui-form-item">
              <label class="layui-form-label">实收服务费</label>
              <div class="layui-input-block">
                  <input type="text" name="receive_tax" id="receive_fuwu" autocomplete="off" class="layui-input">
              </div>
          </div>
        <!-- 备注说明 -->
        <!--<div class="layui-form-item layui-form-text">
          <label class="layui-form-label">收款备注</label>
          <div class="layui-input-block">
            <textarea name="info" class="layui-textarea"></textarea>
          </div>
        </div>-->
        <!-- 提交按钮 -->
        <div class="layui-form-item layui-form-text">
          <input type="text" name="orderId" id="orderId" hidden>
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="confirmPayment">确认收款</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!--服务费申请-->
<div style="display:none;margin-top: 16px" id="support" class="layui-fluids">
    <form class="layui-form" id="supportForm">
        <input type="hidden" name="id" id="supprotId" />
        <div class="layui-form-item">
            <label class="layui-form-label">是否需要支付服务费</label>
            <div class="layui-input-switch">
                <input type="checkbox" lay-text="是|否" id="supprot_notice" name="supprot_notice" lay-skin="switch" lay-filter="supprot" checked >
            </div>
        </div>

        <div class="layui-form-item" id="serviceAmount">
            <label class="layui-form-label">服务费金额</label>
            <div class="layui-input-block">
                <input type="text" id="service_amount" name="service_amount" autocomplete="off" placeholder="请输入金额" class="layui-input">
            </div>
        </div>

        <!-- 提交按钮 -->
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="submit-support">确认</button>
        </div>

    </form>
</div>

<!-- 行编辑 -->
<script type="text/html" id="taxAction">
    <a class="layui-btn layui-btn-xs" lay-event="pleaseTax">税金申请</a>
    <a class="layui-btn layui-btn-xs" lay-event="pleaseSupprot">服务费申请</a>
</script>

<!-- 确认收款服务费表单 -->
