layui.define(function (exports) {
    //
    layui.use(['table', 'jquery', 'form'], function () {
        var order = layui.table;
        var items = layui.table;
        var $ = layui.$;
        var form = layui.form;
        // 判断是否需要报价，切换价格框的输入状态
        form.on('select(bj)', function (data) {
            var val = data.value;
            if (val == 1) {
                $("#pPrice").val("").attr("disabled", "disabled").addClass('layui-disabled');
            } else {
                $("#pPrice").removeAttr("disabled").removeClass('layui-disabled');
            }
        });
        //
        //
        var tableIns = order.render({
            elem: '#order',
            height: 'full-115',
            toolbar: '#toolbar',
            url: 'list', //数据接口
            cellMinWidth: 100,
            page: true, //开启分页
            title: "订单列表",
            skin: 'row',
            even: true,
            cols: [[ //表头
                {type: 'radio'},
                {field: 'num', title: '订单号',templet:'<div>{{d.order_number}}</div>'},
                {field: 'status', title: '订单状态', width: 150, templet: '#orderStatus'},
                {field: 'brand', title: '订货商',templet:'<div>{{d.name}}</div>'},
                {field: 'contact', title: '联系人',templet:'<div>{{d.date}}</div>'},
                {field: 'orderDate', title: '下单时间',templet:'<div>{{d.create_time}}</div>'},
                {field: 'date', title: '期望交货时间',templet:'<div>{{d.date}}</div>'},
                {fixed: 'right', title: '操作', width: 110, toolbar: '#editTool'}
            ]],
            // 计算左侧高度，使右侧高度一致
            done: function(res, curr, count){
                var vheight = $('#leftCard').height();
                $('#rightCard').height(vheight);
                manufacturer();
            }
        });
        // 表格左上角按钮事件
        order.on('toolbar(order)', function (obj) {
            var checkStatus = order.checkStatus(obj.config.id);
            var jsonData = checkStatus.data;
            // 状态按钮事件
            switch (obj.event) {
                /* del user */
                case 'status':
                    if (checkStatus.data.length === 0) {
                        layer.msg("您需要先选择一条数据");
                    } else {
                        var orderStatus = layer.open({
                            type: 2,
                            title: '修改订单状态',
                            area: ['640px', '680px'],
                            content: 'update?id='+jsonData[0].id,
                            resize: false,
                        });
                    }
                    break;
            }
        });


        // 提交表单,改变订单状态操作
        form.on('submit(update)', function(data){
            console.log(data.field);
            var formData = data.field;
            if (formData.status == 1 && formData.bj == '请报价'){
                layer.msg('请选择报价方式',{
                    icon: 0,
                    time: 1000 //2秒关闭（如果不配置，默认是3秒）
                });
                return false;
            } else if (formData.status == 1 && formData.bj == '2' && formData.pPrice == '0'){
                layer.msg('无需供货商报价则需填写报价金额',{
                    icon: 0,
                    time: 1000 //2秒关闭（如果不配置，默认是3秒）
                });
                return false;
            } else if (formData.status == 15 && formData.bj == '2' && formData.pPrice == '0'){
                layer.msg('无需供货商报价则需填写报价金额',{
                    icon: 0,
                    time: 1000 //2秒关闭（如果不配置，默认是3秒）
                });
                return false;
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: data.field,
                url: "status",
                error: function(){ // 保存错误处理
                    layer.msg('系统错误，请稍后重试');
                },
                success: function(e){ // 保存成功处理
                    if (e.errCode == 0){
                        layer.msg('保存成功',{
                            icon: 1,
                            time: 1000 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            parent.layer.closeAll();
                            parent.location.reload();
                        });
                    } else {
                        layer.msg(e.errMsg);
                    }
                }
            });
            return false;
        });


        // 点击每一行的"订单分配"触发
        order.on('tool(order)', function (obj) {
            var data = obj.data;
            var oid = data.id;
            // 取当前订单的 ID
            console.log(data);
            // 行编辑-分配操作事件
            switch (obj.event) {
                case 'edit':
                    // 需要报价
                    if (data.order_status == 2) {
                        var normal = layer.open({
                            type: 1,
                            title: '修改订单状态',
                            content: $('.popList'),
                            // maxmin: true,
                            area: ['960px', '500px'],
                            btn: ['保存分配'],
                            yes: function () {
                                $.ajax({
                                    type: 'post',
                                    url: 'update-status?status=3&id=' + oid,
                                    success: function (e) {
                                        if (e.errCode == 0){
                                            layer.msg('保存成功',{
                                                icon: 1,
                                                time: 1000
                                            }, function(){
                                                layer.close(normal);
                                                tableIns.reload();
                                            });
                                        } else {
                                            layer.msg(e.errMsg ,{
                                                icon: 1,
                                                time: 3000
                                            }, function(){
                                                layer.close(normal);
                                                tableIns.reload();
                                            });
                                        }

                                    },
                                    error: function () {
                                        layer.msg('保存失败',{
                                            icon: 1,
                                            time: 1000
                                        }, function(){
                                            layer.close();
                                            tableIns.reload();
                                        });
                                    }
                                });
                            },
                            success: function (layero, index) {
                                loadItemsEdit(oid);
                            }
                        });
                    } else if (data.order_status == 9) {    //收到订金后进行分配
                        var normal = layer.open({
                            type: 1,
                            title: '修改订单状态',
                            content: $('.popList'),
                            area: ['960px', '500px'],
                            btn: ['保存分配'],
                            success: function (layero, index) {
                                loadItemsEdit(oid);
                            },
                            yes: function () {
                                $.ajax({
                                    type: 'post',
                                    url: 'update-status?status=9&id=' + oid,
                                    success: function (e) {
                                        if (e.errCode == 0){
                                            layer.msg('保存成功',{
                                                icon: 1,
                                                time: 1000
                                            }, function(){
                                                layer.close(normal);
                                                tableIns.reload();
                                            });
                                        } else {
                                            layer.msg(e.errMsg ,{
                                                icon: 1,
                                                time: 1000
                                            }, function(){
                                                layer.close(normal);
                                                tableIns.reload();
                                            });
                                        }
                                    },
                                    error: function () {
                                        layer.msg('操作失败');
                                    }
                                });
                            }
                        });
                    } else { // 只读模式的订单
                        layer.open({
                            type: 1,
                            title: '修改订单状态',
                            content: $('.popList'),
                            area: ['960px', '500px'],
                            success: function (layero, index) {
                                loadItemsReadonly(oid);
                            }
                        });
                    }
                    break;
            }
        });

        // 动态取意大利制造商的品牌列表
        function manufacturer() {
            $.ajax({
                type: 'get',
                async: false,
                url: 'user',
                success: function (res) {
                    var list = res.data;
                    $.each(list, function (i, n) {
                        // console.log(n);
                        var id = n.id;
                        var name = n.name;
                        $("#manuList").append("<option value='" + id + "'>" + name + "</option>");
                    });
                }
            });
            return false;
        }

        // 浏览模式
        function loadItemsReadonly(oid) {
            var tables =  items.render({
                elem: '#items',
                toolbar: '#itemsBar',
                // url: '../../admin/json/products.json', //数据接口
                url: 'products?orderId='+oid,
                cellMinWidth: 100,
                skin: 'row',
                even: true,
                cols: [[ //表头
                    {field: 'bid', title: '供应商ID',templet:'<div>{{d.supplier_id}}</div>'},
                    {field: 'manu', title: '供货商名称',templet:'<div>{{d.supplier_name}}</div>'},
                    {field: 'brand', title: '品牌',templet:'<div>{{d.brand}}</div>'},
                    {field: 'qty', title: '数量',templet:'<div>{{d.number}}</div>'},
                    {field: 'type', title: '型号',templet:'<div>{{d.type}}</div>'},
                    {field: 'size', title: '图纸尺寸',templet:'<div>{{d.size}}</div>'},
                    {field: 'material', title: '材质',templet:'<div>{{d.material}}</div>'},
                    {field: 'des', title: '描述',templet:'<div>{{d.desc}}</div>'},
                ]]
            });
        }

        // 分配可交互模式
        function loadItemsEdit(oid) {
            var tables =  items.render({
                elem: '#items',
                toolbar: '#itemsBar',
                url: 'products?orderId='+oid,
                where: {id: oid}, // 传
                cellMinWidth: 100,
                skin: 'row',
                even: true,
                cols: [[ //表头
                    {field: 'bid', title: '供应商ID',templet:'<div>{{d.supplier_id}}</div>'},
                    {field: 'manu', title: '供货商名称', event: 'set',templet:'<div>{{d.supplier_name}}</div>', style: 'cursor: pointer;'},
                    {field: 'brand', title: '品牌',templet:'<div>{{d.brand}}</div>'},
                    {field: 'type', title: '型号',templet:'<div>{{d.type}}</div>'},
                    {field: 'qty', title: '数量',templet:'<div>{{d.number}}</div>'},
                    {field: 'des', title: '描述',templet:'<div>{{d.desc}}</div>'},
                ]]
            });
            // 单元格事件
            items.on('tool(items)', function (obj) {
                // 点击数据行取出行数据的 ID
                var row_data = obj.data;
                var row_id = row_data.id;
                if (obj.event === 'set') {
                    var fp = layer.open({
                        type: 1,
                        title: '选择供货商',
                        btn: ['确定', '取消'],
                        content: $('.dropList'),
                        yes: function (index) {
                            var n_name = $("#manuList").find("option:selected").text();
                            var n_bid = $("#manuList").val();

                            if (n_bid == 0){
                                layer.msg('请选择供货商');
                                return false;
                            }
                            // 点击确定后 AJAX 更新数据
                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: 'update-user?', // 产品数据接口
                                data: {
                                    id: row_id,
                                    userId: n_bid,
                                    name: n_name
                                },
                                beforeSend: function () {
                                    layer.msg('Update...');
                                },
                                error: function () {
                                    layer.msg('系统错误，请稍后重试');
                                    layer.close(fp);
                                },
                                success: function () {
                                    layer.msg('保存成功',{
                                        icon: 1,
                                        time: 1000 //2秒关闭（如果不配置，默认是3秒）
                                    }, function(){
                                        layer.close(fp);
                                        // 表格重载
                                        tables.reload();
                                    });
                                }
                            });
                            return false;
                        }
                    });
                }
            });
        }
    });
    //
    exports('workflow', {});
});
