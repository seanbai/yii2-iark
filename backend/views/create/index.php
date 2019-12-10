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
                                <input type="text" name="user" lay-verify="title" autocomplete="off"
                                       value="<?php echo \yii::$app->user->identity->id; ?>" placeholder="订货商"
                                       class="layui-input" disabled>
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
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>品牌</th>
                                    <th>类型</th>
                                    <th>数量</th>
                                    <th>描述</th>
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
                                        <a href="javascript:;" class="file">选择文件
                                            <input type="file" name="upload-files" id="coverPhoto-file" class="upload-file"/>
                                        </a>
                                        <span class="showFileName"></span>
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
            $(document).on('click', '#save', function () {

                data2 = new FormData($('#form1')[0]);
                console.log(data2);
                //data2 = $('#form1').serialize();
                // var user = $("input[name='user']").val();
                // var date = $("input[name='date']").val();
                // var name = $("input[name='name']").val();
                // var phone = $("input[name='phone']").val();
                // var address = $("input[name='address']").val();
                // var currency = $("input[name='currency']").val();
                //
                // /*整理列表数据*/
                // from1 = [];
                // from2 = [];
                // from3 = [];
                // from4 = [];
                // from5 = [];
                // from6 = [];
                // from7 = [];
                // if ($("input[name='brand1']").val() != '') {
                //     from1 = {
                //         "brand": $("input[name='brand1']").val(),
                //         "type": $("input[name='type1']").val(),
                //         "number": $("input[name='number1']").val(),
                //         "desc": $("input[name='desc1']").val()
                //
                //     }
                // }
                // if ($("input[name='brand2']").val() != '') {
                //     from2 = {
                //         "brand": $("input[name='brand2']").val(),
                //         "type": $("input[name='type2']").val(),
                //         "number": $("input[name='number2']").val(),
                //         "desc": $("input[name='desc2']").val()
                //     }
                // }
                // if ($("input[name='brand3']").val() != '') {
                //     from3 = {
                //         "brand": $("input[name='brand3']").val(),
                //         "type": $("input[name='type3']").val(),
                //         "number": $("input[name='number3']").val(),
                //         "desc": $("input[name='desc3']").val()
                //     }
                // }
                // if ($("input[name='brand4']").val() != '') {
                //     from4 = {
                //         "brand": $("input[name='brand4']").val(),
                //         "type": $("input[name='type4']").val(),
                //         "number": $("input[name='number4']").val(),
                //         "desc": $("input[name='desc4']").val()
                //     }
                // }
                // if ($("input[name='brand5']").val() != '') {
                //     from5 = {
                //         "brand": $("input[name='brand5']").val(),
                //         "type": $("input[name='type5']").val(),
                //         "number": $("input[name='number5']").val(),
                //         "desc": $("input[name='desc5']").val()
                //     }
                // }
                // if ($("input[name='brand6']").val() != '') {
                //     from6 = {
                //         "brand1": $("input[name='brand6']").val(),
                //         "type1": $("input[name='type6']").val(),
                //         "number1": $("input[name='number6']").val(),
                //         "desc1": $("input[name='desc6']").val()
                //     }
                // }
                // if ($("input[name='brand7']").val() != '') {
                //     from7 = {
                //         "brand": $("input[name='brand7']").val(),
                //         "type": $("input[name='type7']").val(),
                //         "number": $("input[name='number7']").val(),
                //         "desc": $("input[name='desc7']").val()
                //     }
                // }
                //
                // data2 = {
                //     "user": user,
                //     "date": date,
                //     "name": name,
                //     "phone": phone,
                //     "address": address,
                //     "currency": 1,
                //     "data": [
                //         from1, from2, from3, from4, from5, from6, from7
                //     ]
                // }

                meTables.ajax({
                    url: 'from',
                    type: 'post',
                    data: data2,
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
            })

        });

        ////////添加一行、删除一行封装方法///////
        /**
         * 为table指定行添加一行
         *
         * tab 表id
         * row 行数，如：0->第一行 1->第二行 -2->倒数第二行 -1->最后一行
         * trHtml 添加行的html代码
         *
         */
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

        /**
         * 全选
         *
         * allCkb 全选复选框的id
         * items 复选框的name
         */
        function allCheck(allCkb, items) {
            $("#" + allCkb).click(function () {
                $('[name=' + items + ']:checkbox').attr("checked", this.checked);
            });
        }

        ////////添加一行、删除一行测试方法///////
        $(function () {
            //全选
            allCheck("allCkb", "ckb");
        });

        function addTr2(tab, row) {
            var trHtml = '<tr><td><input type="checkbox" name="ckb"/></td><td><input type="text" name="brand[]" lay-verify="title" class="layui-input"></td><td><input type="text" name="type[]"   lay-verify="title" class="layui-input"></td><td><input type="text" name="number[]"  lay-verify="title" class="layui-input"></td><td><input type="text" name="desc[]" lay-verify="title" class="layui-input"></td></tr>'
            // var trHtml="<tr align='center'><td width='30%'><input type='checkbox' name='ckb'/></td><td width='30%'><input></td><td width='30%'><input></td></tr>";
            addTr(tab, row, trHtml);
        }

        function delTr2() {
            delTr('ckb');
        }


        $(".file").on("change", "input[type='file']", function () {
            var filePath = $(this).val();
            $(".fileerrorTip").html("").hide();
            var arr = filePath.split('\\');
            var fileName = arr[arr.length - 1];
            $(".showFileName").html(fileName);

        })
    </script>


<?php $this->endBlock(); ?>