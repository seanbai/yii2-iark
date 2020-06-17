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
      elem: '#order',
      height: 'full-115',
      toolbar: '#pendingOrderBar',
      url: 'list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      // skin: 'line',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'project_name', title: '项目名称'},
        {field: 'status_label', title: '订单状态'},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'},
        {field: 'quote', title: '报价', sort: true}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(order)', function(obj){
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
        // track order
        case 'status':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据")
          }else{
            layer.open({
              type: 2,
              title: '修改订单状态',
              area: ['640px', 'auto'],
              content: 'status.html',
              btn: ['Save','Close'],
              resize: false
            });
          }
          break;
        //提货清单
        case 'goodslist':
          if(checkStatus.data.length === 0) {
            layer.msg("您需要先选择一条数据", {icon:0});
          } else {
            var id = checkStatus.data[0].id;
            layer.open({
              type: 1,
              title: '提货订单',
              area: ['90%', '70%'],
              content: $('#showItems'),
              btn: ['提交'],
              success: showGoodslist(id)
            });
          }
          break;
      }
    });

    // 显示产品清单方法
    window.showItems = function(id){

      table.render({
        elem: '#items',
        url: 'items?id=' + id, //数据接口
        toolbar: '#showItemsBar',
        skin: 'row',
        even: true,
        totalRow: true, //开启合计行
        cols: [[
          {field: 'brand', title: '名称'},
          {field: 'number', title: '数量', totalRow: true},
          {field: 'files', title: '样式图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },


          {field: 'type', title: '型号'},
          {field: 'size', title: '产品尺寸'},
          {field: 'material', title: '材质'},
          {field: 'att', title: '附件',
            templet: function(d){
              var att = d.att;
              if(att.length === 0){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'supplier_name', title: '供应商'},
          {field: 'desc', title: '备注'},
          {field: 'price', title: '价格', totalRow: true}
        ]]
      })
    }

    // 取消订单方法
    window.cancel = function(id){
      $.ajax({
        type: 'POST',
        url: 'cancel',
        data: {
          id: id
        },
        error: function(){ // 保存错误处理
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(response){ // 保存成功处理
          // 成功提示
          if(response.errCode == 0){
            layer.msg('订单已取消，您可以在已取消订单的列表里找到它。');
            // 表格重载
            order.reload();
          }else{
            layer.msg(response.errMsg, {icon: 0});
          }
        }
      });
    }
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

    // 显示提货清单方法
    window.showGoodslist = function(id){
      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'goods-list?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true,
        skin: 'row',
        even: true,
        cols: [[
          {field: 'name', title: '提货编号'},
          {field: 'status_name', title: '状态'},
          {field: 'wait_tax_amount', title: '应收税金', edit: 'text'},
          {field: 'confirm_tax_amount', title: '实收税金', edit: 'text'},
          {field: 'wait_support_amount', title: '应收服务费', edit: 'text'},
          {field: 'confirm_supprot_amount', title: '实收服务费', edit: 'text'},
          {field: 'desc', title: '备注', edit: 'text', width:300},
          {fixed: 'right', title: '操作', toolbar: '#taxAction', width: 180}
        ]]
      });
      //价格编辑
      table.on('edit(items)', function(obj){
        console.log(obj);
        var value = obj.value;  //修改后的金额
        var field = obj.field;   //修改的字段
        var status = obj.data.status;
        var itemId = obj.data.id;
        var msgStatus = true;
        //修改待申请税金。
        if (status == 0 && field != 'wait_tax_amount') {
          layer.msg("当前状态不能修改其他字段", {
            icon: 5,
            time: 2000 //2秒关闭（如果不配置，默认是3秒）
          })
          msgStatus = false;
        }
        if (msgStatus == true) {
          // 改动完即同步数据库
          $.ajax({
            type: 'POST',
            //同步接口，传数据ID和修改后的金额值
            url: 'update-tax-service?id=' + itemId + '&price=' + value + '&field=' + field + '&status=' + status,
            success: function(response){
              if(response.errCode == 0){
                layer.msg('操作成功', {icon: 6});
                table.reload('items',{}); // 重载数据表格
              }else {
                layer.msg(response.errMsg,{icon: 5});
                table.reload('items',{}); // 重载数据表格
              }
            },
            error: function(){
              layer.msg('Error');
            }
          })
        }
      })
    }


  });
  //
  exports('purchase', {});
});
