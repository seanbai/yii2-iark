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
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'supplier_name', title: '供应商'},
          {field: 'desc', title: '备注'},
          {field: 'origin_price', title: '产品单价', totalRow: true},
          {field: 'disc_price', title: '采购折扣价', totalRow: true}
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
        url: '/workflow/goods-list?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true,
        skin: 'row',
        even: true,
        cols: [[
          {field: 'name', title: '提货编号'},
          {field: 'status_name', title: '状态'},
          {field: 'wait_tax_amount', title: '税金', },
          {field: 'wait_support_amount', title: '服务费', },
          {fixed: 'right', title: '操作', toolbar: '#taxAction', width: 250}
        ]]
      });
      //按钮提交
      table.on('tool(items)', function(obj){
        var data = obj.data;
        var id = data.id;
        var status = data.status;
        switch(obj.event){
          case 'confirmTax':
            layer.confirm('请确认是否已经支付税金，一旦确认无法修改，如有问题，请联系管理人员', function (index) {
              if (status != 1) {
                layer.msg("当前状态不能执行税金已支付操作");
              } else {
                $.ajax({
                  type: 'POST',
                  url: '/workflow/update-tax',
                  data: {
                    id : id,
                    status: 2
                  },
                  error: function(){
                    layer.msg('系统异常,请联系管理人员',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        items.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      });
                    }
                  }
                });
              }
      })
      break;
    case 'confirmSupprot':
      layer.confirm('请确认是否已经支付服务费，一旦确认无法修改，如有问题，请联系管理人员', function (index) {
        if (status != 4) {
          layer.msg("当前状态不能执行服务费已支付操作");
        } else {
          $.ajax({
            type: 'POST',
            url: '/workflow/update-tax',
            data: {
              id : id,
              status: 5
            },
            error: function(){
              layer.msg('系统异常,请联系管理人员',{icon:5});
            },
            success: function(response){
              if(response.errCode == 0){
                layer.msg(response.errMsg, {
                  icon: 1,
                  time: 2000 //2秒关闭（如果不配置，默认是3秒）
                }, function () {
                  layer.closeAll();
                  items.reload();
                });
              }else{
                layer.msg(response.errMsg, {
                  icon: 5,
                  time: 2000 //2秒关闭（如果不配置，默认是3秒）
                });
              }
            }
          });
        }
      })
      break;
    }
      })
    }


  });
  //
  exports('purchase', {});
});
