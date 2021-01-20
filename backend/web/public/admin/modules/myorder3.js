layui.define(function(exports){

    //
    layui.use(['table','form','laydate','jquery'], function(){

        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;
        var $ = layui.jquery;

        //执行一个laydate实例
        laydate.render({
            elem: '#processList' //指定元素
        })

        var order = table.render({
            elem: '#neworder',
            height: 'full-115',
            toolbar: '#newOrderBar',
            url: 'myorder-list', //数据接口
            page: true, //开启分页
            // skin: 'line',
            even: true,
            cols: [[ //表头
                {type:'radio'},
                {field: 'num', title: '订单号', templet:'<div>{{d.order_number}}</div>'},
                {field: 'project', title: '项目名称', templet: '<div>{{d.project_name}}</div>'},
                {field: 'create', title: '创建时间', templet:'<div>{{d.create_time}}</div>'},
                {field: 'start', title: '订单状态', templet:'<div>{{d.order_status}}</div>'},
                {field: 'package', title: '包装要求', templet:'<div>{{d.package}}</div>'},
                {field: 'contact', title: '提货联系人', templet:'<div>{{d.name}}</div>'},
                {field: 'address', title: '交付地址', templet:'<div>{{d.address}}</div>'},
            ]]
        });
        // 表格菜单事件
        table.on('toolbar(neworder)', function(obj){
            var checkStatus = table.checkStatus(obj.config.id);

            switch(obj.event){
                // show details
                case 'details':
                    if(checkStatus.data.length === 0){
                        layer.msg("您需要先选择一条数据");
                    }else{
                        // 取订单ID 和 项目名称
                        var data = checkStatus.data;
                        var id = data[0].id;
                        var project = data[0].project_name;

                        layer.open({
                            type: 1,
                            title: '项目名称 - ' + project,
                            area: ['90%', '80%'],
                            content: $('#showItems'),
                            resize: false,
                            success: showItems(id)
                        });
                    }
                    break;
                // owner info
                case 'ownerInfo':
                    if(checkStatus.data.length === 0){
                        layer.msg("您需要先选择一条数据")
                    }else{
                        // 取订单ID 和 项目名称
                        var data = checkStatus.data;
                        var id = data[0].user;
                        ownerInfo(id);
                    }
                    break;
                // confirm order
                case 'confirm':
                    if(checkStatus.data.length === 0){
                        layer.msg("您需要先选择一条数据")
                    }else{
                        // 取订单ID 和 项目名称
                        var data = checkStatus.data;
                        var id = data[0].id;

                        layer.confirm('确认使订单立即生效?', function(index){
                            confirm(id);
                            // layer.close(index);
                            order.reload();
                        });
                    }
                    break;
                // cancel order
                case 'cancel':
                    if(checkStatus.data.length === 0){
                        layer.msg("您需要先选择一条数据")
                    }else{
                        // 取订单ID 和 项目名称
                        var data = checkStatus.data;
                        var id = data[0].id;

                        layer.confirm('确认取消此订单?', function(index){
                            cancel(id);
                            // layer.close(index);
                        });
                    }
                    break;
            }
        });

        // 显示产品清单方法
        window.showItems = function(id){
            table.render({
                elem: '#items',
                url: 'products?orderId=' + id, //数据接口
                toolbar: '#showItemsBar',
                skin: 'row',
                even: true,
                totalRow: true, //开启合计行
                cols: [[
                    {field: 'brand', title: '名称', templet:'<div>{{d.brand}}</div>'},
                    {field: 'number', title: '数量', totalRow: true, templet:'<div>{{d.number}}</div>'},
                    {field: 'files', title: '样式图片',
                        templet: function(d){
                            return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
                        }
                    },
                    {field: 'product_supplier', title: '品牌',  templet:'<div>{{d.product_supplier}}</div>'},
                    {field: 'type', title: '型号',  templet:'<div>{{d.type}}</div>'},
                    {field: 'size', title: '产品尺寸',  templet:'<div>{{d.size}}</div>'},
                    {field: 'material', title: '材质',  templet:'<div>{{d.material}}</div>'},
                    {field: 'att', title: '附件',
                        templet: function(d){
                            var att = d.att;
                            if(att){
                                return '<div><a href="'+d.att+'">Download</a></div>'
                            }else{
                                return ''
                            }
                        }
                    },
                    {field: 'supplier_name', title: '供货商',  templet:'<div>{{d.supplier_name}}</div>'},
                    {field: 'desc', title: '备注',  templet:'<div>{{d.desc}}</div>'}
                ]]
            })
        };

        // 查看采购商详情
        window.ownerInfo = function(id){
            $.ajax({
                type: 'GET',
                url: 'order-user?id=' + id,
                success: function(res){
                    var data = res.data;
                    var name = data.username;
                    var boss = data.name;
                    var phone = data.phone;
                    var mail = data.email;
                    var address = data.address;

                    layer.alert('采购商：' + name + '<br>联系人：' + boss + '<br>联系电话：' + phone + '<br>电子邮箱：' + mail + '<br>通讯地址：' + address);
                }
            })
        };

        // 确认订单方法
        window.confirm = function(id){
            $.ajax({
                type: 'POST',
                url: 'confirm-order?id=' + id,
                error: function(){ // 保存错误处理
                    layer.msg('系统错误,请稍后重试.');
                    order.reload();
                },
                success: function(){ // 保存成功处理
                    // 成功提示
                    layer.msg('订单已生效，您可以在等待报价的订单列表里找到它。');
                    // 表格重载
                    order.reload();
                }
            });
        };
        // 取消订单方法
        window.cancel = function(id){
            $.ajax({
                type: 'POST',
                url: 'cancel-one-order?id=' + id,
                error: function(){ // 保存错误处理
                    layer.msg('系统错误,请稍后重试.');
                },
                success: function(){ // 保存成功处理
                    // 成功提示
                    layer.msg('订单已取消，您可以在已取消订单的列表里找到它。');
                    // 表格重载
                    order.reload();
                }
            });
        };
        // 产品图片预览
        window.showImg = function(t){
            var t = $(t).find("img");
            console.log(t);
            // 图片 lightbox
            layer.open({
                type: 1,
                title: false,
                skin: 'layui-layer-rim',
                area: ['auto'],
                shadeClose: true,
                end: function(index, layero){
                    return false;
                },
                content: '<div style="text-align:center"><img width="500" src="' + $(t).attr('src') + '" /></div>'
            });
        }

    });
    //
    exports('neworder', {});
});
