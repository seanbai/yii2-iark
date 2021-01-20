<?php
$this->title = 'Create New Order';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">

    <!-- 产品信息 -->
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">产品信息</div>
        <div class="layui-card-body">
            <table class="items" id="items" lay-filter="items"></table>
            <!-- 顶部工具栏 -->
            <script type="text/html" id="createOrder">
              <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm" lay-event="create">添加</button>
              </div>
            </script>
            <!-- 删除 -->
            <script type="text/html" id="delete">
                <a class="layui-btn layui-btn-xs" lay-event="edit">修改</a>
              <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="del">删除</a>
            </script>
        </div>
      </div>
    </div>
    <!-- 订单基本信息 -->
    <form class="layui-col-md12 layui-form">
      <div class="layui-card">
        <div class="layui-card-header">基本信息</div>
        <div class="layui-card-body layui-row layui-col-space10">
          <div class="layui-col-md3">
            <input type="text" name="project" placeholder="项目名称" autocomplete="off" class="layui-input">
          </div>
          <div class="layui-col-md3">
            <input type="text" name="delivery" placeholder="预计交付期" autocomplete="off" class="layui-input" id="delivery">
          </div>
          <div class="layui-col-md3">
            <input type="text" name="package" placeholder="包装要求" autocomplete="off" class="layui-input">
          </div>
          <div class="layui-col-md3">
            <input type="text" name="contact" placeholder="请输入联系人姓名" autocomplete="off" class="layui-input">
          </div>
          <div class="layui-col-md12">
            <input type="text" name="address" placeholder="交付地址" autocomplete="off" class="layui-input">
          </div>
          <div class="layui-col-md12">
            <button class="layui-btn" type="button"  id="createOrder" lay-submit="" lay-filter="component-form-demo1">提交订单</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          </div>
        </div>
      </div>
    </form >
  </div>
</div>
<!--suppress HtmlUnknownTarget -->
<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        newindex: 'lib/newindex' //主入口模块
    }).use(['newindex', 'newcreateorder']);
    window.orderItems = [];
</script>
<script>
    //Demo
    layui.use(['form','jquery'], function(){
        var form = layui.form;
        var $ = layui.jquery;

        //监听提交
        form.on('submit(component-form-demo1)', function(data){
            var $postData = data.field;
            $postData._csrf = $('meta[name=csrf-token]').attr('content');
            $.post('<?= \yii\helpers\Url::toRoute(['create/from'])?>', $postData, function (res) {
                if(res.errCode == 0){
                    layer.msg(res.errMsg, {
                        icon: 6,
                        time: 2000
                    }, function(){
                        window.location.reload();
                    });
                }else{
                    layer.msg(res.errMsg,{
                        icon: 2,
                        time: 2000
                    });
                }
            });
        });
    });
</script>
<!-- 将表格里的图片路径转化为图片显示 -->
<script type="text/html" id="itemImage">
  <a href="{{d.image}}" target="_blank"><img src="{{d.image}}"></a>
</script>

<!-- 添加产品清单的弹层 -->
<div style="display:none" id="addItem">
    <div class="layui-fluids">
        <form class="layui-form layui-form-pane" id="addItems" lay-filter="edit">
            <!-- 型号名称 -->
            <input type="hidden" name="pid" value="" />
            <div class="layui-form-item">
                <label class="layui-form-label">名称*</label>
                <div class="layui-input-block">
                    <input type="text" name="brand" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 数量 -->
            <div class="layui-form-item">
                <label class="layui-form-label">数量*</label>
                <div class="layui-input-block">
                    <input type="text" name="qty" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 上传图片 -->
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">样式图片</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="image" id="image">
                        <button type="button" class="layui-btn layui-btn-primary" id="image-up"><i class="layui-icon"></i>上传图片</button>
                    </div>
                </div>
                <div class="layui-inline">
                    <img src="" id="demo1" height="38">
                </div>
            </div>
            <!-- 品牌 -->
            <div class="layui-form-item">
                <label class="layui-form-label">品牌*</label>
                <div class="layui-input-block">
                    <input type="text" name="product_supplier" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 型号 -->
            <div class="layui-form-item">
                <label class="layui-form-label">型号</label>
                <div class="layui-input-block">
                    <input type="text" name="model" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 产品尺寸 -->
            <div class="layui-form-item">
                <label class="layui-form-label">产品尺寸*</label>
                <div class="layui-input-block">
                    <input type="text" name="size" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 材质 -->
            <div class="layui-form-item">
                <label class="layui-form-label">材质</label>
                <div class="layui-input-block">
                    <input type="text" name="material" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 上传图片 -->
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">附件</label>
                    <div class="layui-input-block">
                        <input type="hidden" name="att" id="att">
                        <button type="button" class="layui-btn layui-btn-primary" id="attachment"><i class="layui-icon"></i>上传附件</button>
                        <small id="file-name">仅支持 zip|rar|7z 格式压缩包文件</small>
                    </div>
                </div>
            </div>
            <!-- 备注 -->
            <div class="layui-form-item">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <input type="text" name="desc" autocomplete="off" class="layui-input">
                </div>
            </div>
            <!-- 提交表单 -->
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="addItem">保存</button>
            </div>
        </form>
    </div>
</div>
<?php $this->endBlock(); ?>
