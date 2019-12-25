<?php
// 定义标题和面包屑信息
$this->title = 'Create A New Order';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>

    <style>
        #main-container {
            display: none;
        }
    </style>

    <div class="layui-fluid">
        <div class="layui-row layui-col-space15 create-order">
            <form id="form1" enctype="multipart/form-data"  method="post">
                <div class="layui-col-md4">
                    <div class="layui-card layui-form">
                        <div class="layui-card-header">采购单基本信息</div>
                        <div class="layui-card-body layui-row layui-col-space10">
                            <div class="layui-col-md12">
                                <input type="text" name="userId" lay-verify="title" autocomplete="off"
                                       value="<?php echo \yii::$app->user->identity->username; ?>" placeholder="订货商"
                                       class="layui-input" disabled>
                                <input type="hidden" name="userId" value="<?php echo \yii::$app->user->identity->getId(); ?>">
                            </div>
                            <div class="layui-col-md12">
                                <input type="text" name="date" lay-verify="date" class="layui-input" id="date"
                                       placeholder="期望交货时间" autocomplete="off">
                            </div>
                            <div class="layui-col-md12">
                                <input type="text" name="name" lay-verify="title" autocomplete="off"
                                       value="<?php echo \yii::$app->user->identity->username; ?>" placeholder="联系人"
                                       class="layui-input">
                            </div>
                            <div class="layui-col-md12">
                                <input type="text" name="phone" lay-verify="title" autocomplete="off" placeholder="电话"
                                       class="layui-input">
                            </div>
                            <div class="layui-col-md12">
                                <input type="text" name="address" lay-verify="title" autocomplete="off"
                                       placeholder="收货地址"
                                       class="layui-input">
                            </div>
                            <div class="layui-col-md12">
                                <select name="currency" lay-verify="">
                                    <option value="0">结算币种选择</option>
                                    <option value="1" selected="selected">人民币</option>
                                    <option value="2">欧元</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-md8">
                    <div class="layui-card layui-form">
                        <div class="layui-card-header">产品清单</div>
                        <div class="layui-card-body layui-row layui-col-space10">
                            <!-- 产品列表 -->
                            <table class="layui-table" lay-skin="line" id="tab">
                                <colgroup>
                                    <col width="80">
                                    <col width="200">
                                    <col width="200">
                                    <col width="100">
                                    <col width="100">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>品牌</th>
                                    <th>类型</th>
                                    <th>数量</th>
                                    <th>描述</th>
                                    <th>附件</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input id="allCkb" type="checkbox"/></td>
                                    <td>
                                        <input type="text" name="brand[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="type[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="number[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="desc[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="file" name="files[]" lay-verify="title" class="layui-file"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name='ckb'/></td>
                                    <td>
                                        <input type="text" name="brand[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="type[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="number[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="desc[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="file" name="files[]" lay-verify="title" class="layui-file"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name='ckb'/></td>
                                    <td>
                                        <input type="text" name="brand[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="type[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="number[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="desc[]" lay-verify="title" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="file" name="files[]" lay-verify="title"  class="layui-file"/>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="layui-btn fr" id="save">
                                            <i class="layui-icon">&#xe605;</i>提交采购单
                                        </button>
                                        <button type="button" class="layui-btn fr" id="addline"
                                                onclick="addTr2('tab', -1)">
                                            <i class="layui-icon">&#xe654;</i>增加行
                                        </button>
                                        <button type="button" class="layui-btn fr" id="delete" onclick="delTr2()">
                                            <i class="layui-icon">&#xe67c;</i>删除行
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script type="text/javascript">
        $(function () {
            $('input[name="files[]"]').change(function(event) {
                var file = $(this).val();
                console.log(file);
            });
            $(document).on('click', '#save', function () {
                var fromdata = new FormData($('#form1')[0]);
                console.log(fromdata);
                meTables.ajax({
                    url: 'from',
                    type: 'post',
                    data: fromdata,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).success(function (rsp) {
                    if (rsp == 1) {
                        alert("提交成功");
                        top.location.href = "/";
                    } else {
                        alert("提交失败,请刷新后重试");
                    }
                });
            });
        });
        function addTr(tab, row, trHtml) {
            var $tr = $("#" + tab + " tbody tr").eq(row);
            if ($tr.size() == 0) {
                alert("指定的table id或行数不存在！");
                return;
            }
            $tr.after(trHtml);
        }
        function delTr(ckb) {
            //获取选中的复选框，然后循环遍历删除
            var ckbs = $("input[name=" + ckb + "]:checked");
            if (ckbs.size() == 0) {
                alert("要删除指定行，需选中要删除的行！");
                return;
            }
            ckbs.each(function () {
                $(this).parent().parent().remove();
            });
        }
        function allCheck(allCkb, items) {
            $("#" + allCkb).click(function () {
                $('[name=' + items + ']:checkbox').attr("checked", this.checked);
            });
        }
        $(function () {allCheck("allCkb", "ckb");});

        function addTr2(tab, row) {
            var trHtml = '<tr><td><input type="checkbox" name="ckb"/></td><td><input type="text" name="brand[]" lay-verify="title" class="layui-input"></td><td><input type="text" name="type[]"   lay-verify="title" class="layui-input"></td><td><input type="text" name="number[]"  lay-verify="title" class="layui-input"></td><td><input type="text" name="desc[]" lay-verify="title" class="layui-input"></td><td><input type="file" name="files[]" lay-verify="title" class="layui-file files"/></td></tr>'
            addTr(tab, row, trHtml);
        }

        function delTr2() {
            delTr('ckb');
        }


    </script>


<?php $this->endBlock(); ?>