<?php
// 定义标题和面包屑信息
$this->title = 'System Config';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>

    <style>
        #main-container {
            display: none;
        }
    </style>



    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">System Config</div>
                    <div class="layui-card-body">
                        <div class="layui-form" wid150>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">系统名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" lay-verify="title" autocomplete="off"
                                           value="<?= $title ?>"
                                           placeholder="请输入系统名称，将显示在左上角" class="layui-input">
                                </div>
                            </div>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">系统域名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="domain" lay-verify="title" autocomplete="off"
                                           value="<?= $domain ?>"
                                           placeholder="www.i-ark.com/erp" class="layui-input">
                                </div>
                            </div>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">浏览器标题</label>
                                <div class="layui-input-block">
                                    <input type="text" name="header" lay-verify="title" autocomplete="off"
                                           value="<?= $domain_title ?>"
                                           placeholder="Order Order System" class="layui-input">
                                </div>
                            </div>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">最大附件上传</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="size" lay-verify="number" 
                                           value="<?= $update_size ?>"
                                           class="layui-input">
                                </div>
                                <div class="layui-input-inline layui-input-info">KB</div>
                                <div class="layui-form-mid layui-word-aux">提示：1 M = 1024 KB</div>
                            </div>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <label class="layui-form-label">欧元/人民币汇率</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="rate" lay-verify="number"
                                           value="<?= $rate ?>"
                                           class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">设置系统汇率，变更后立即生效（新订单有效）</div>
                            </div>
                            <!-- form item -->
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" id="layui-btn" lay-submit="" lay-filter="set_website">确认保存</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        $(function(){
            $(document).on('click', '#layui-btn', function () {
                var title = $("input[name='title']").val();
                var domain = $("input[name='domain']").val();
                var header = $("input[name='header']").val();
                var size = $("input[name='size']").val();
                var rate = $("input[name='rate']").val();
                meTables.ajax({
                    url: 'from',
                    type: 'GET',
                    dataType: "json",
                    data: 'title='+title+'&domain='+domain+'&header='+header+'&size='+size+'&rate='+rate,
                }).success(function(rsp){
                    alert('保存成功');
                });
            })

        });
    </script>



<?php $this->endBlock(); ?>