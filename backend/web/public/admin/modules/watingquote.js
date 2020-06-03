layui.define(function(exports){
  //
  layui.use(['table','jquery','form'], function(){
    var table = layui.table;
    var $ = layui.jquery;
    var form = layui.form;
    //
    var workflow = table.render({
      elem: '#myOrder',
      height: 'full-115',
      toolbar: '#orderBar',
      url: 'watingquote-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'order_status_label', title: '订单状态'},
        {field: 'project_name', title: '项目名称'},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'},
        {field: 'quote', title: '报价'}
      ]]
    });

    // 表格工具
    table.on('toolbar(myOrder)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;

      switch(obj.event){
        // 查看产品清单
        case 'items':
          if(checkStatus.data.length === 0){
            layer.msg("请选择一条订单数据");
          }else{
            // 取订单ID 和 项目名称
            var data = checkStatus.data;
            var id = data[0].id;
            var num = data[0].order_number;
            // 打开产品列表弹层
            var itemsbox = layer.open({
              type: 1,
              title: 'Order Number: ' + num,
              area: ['99%', '98%'],
              content: $('#showItems'),
              btn: ['确认报价','拒绝报价', '取消'],
              success: showItems(id),
              yes: function(){
                layer.confirm('是否确认报价,并支付定金', function(index){
                  $.ajax({
                    type: 'POST',
                    url: 'update',
                    data:{
                      id:id,
                      status: 5
                    },
                    error: function(){
                      layer.msg('系统异常...',{icon:5});
                    },
                    success: function(response){
                      if(response.errCode == 0){
                        layer.msg(response.errMsg, {
                          icon: 1,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                          workflow.reload();
                        });
                      }else{
                        layer.msg(response.errMsg, {
                          icon: 5,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                        });
                      }
                    }
                  });
                })
              },
              btn2 : function () {
                layer.confirm('您确定拒绝报价吗？', function(){
                  $.ajax({
                    type: 'POST',
                    url: 'update',
                    data:{
                      id:id,
                      status: 401 //取消订单
                    },
                    error: function(){
                      layer.msg('系统异常...',{icon:5});
                    },
                    success: function(response){
                      // 关闭弹层
                      if(response.errCode == 0){
                        layer.msg('订单更新成功', {
                          icon: 1,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                          workflow.reload();
                        });
                      }else{
                        layer.msg(response.errMsg, {
                          icon: 5,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                        });
                      }
                    }
                  });
                });
                return false;
              }
            });
          }
        break;
      case 'payOrder':
          if(checkStatus.data.length === 0){
            layer.msg("请选择一条订单数据");
          }else {
            var order_data = checkStatus.data,
                order_id = order_data[0].id,
                total = order_data[0].quote,
                order_status = order_data[0].order_status,
             form=layer.open({
              type: 1,
              title: '付款确认',
              area: ['640px', '400px'],
              content: $('#payOrderForm'),
              success: function(layero, index){
                console.log(total);
                total = total ? parseInt(total) : 0;
                $('#orderId').val(order_id);
                $('#order-total').val(total);
                if(total <= 0){
                  $('[data-role="subPay"]').hide();
                }else{
                  $('[data-role="subPay"]').show();
                }
                if(order_status == 6){
                  $('#order-deposit').val(total/2);
                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').hide();
                  $('[data-role="pay-tax"]').hide();
                }
                if(order_status == 11){
                  $('#order-balance').val( total/2);
                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').show();
                  $('[data-role="pay-tax"]').hide();
                }
                if(order_status == 14){
                  $('#order-tax').val(data[0].tax);
                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').hide();
                  $('[data-role="pay-tax"]').show();
                }

              }
            });
          }
        break;
      }
    });

    // 表单提交
    form.on('submit(payOrderForm)',function(data, id){
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: 'order-pay',
        error: function(){
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(res){
          if(res.code == 200){
            layer.closeAll();
            workflow.reload();
          }else{
            layer.msg(res.msg);
          }
        }
      });
      return false;
    })

    window.showItems = function(id){
      table.render({
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: 'Item'},
          {field: 'number', title: 'Qty'},
          {field: 'files', title: 'Image',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'type', title: 'Model'},
          {field: 'size', title: 'Size'},
          {field: 'material', title: 'Material'},
          {field: 'att', title: 'Attachment',
            templet: function(d){
              var att = d.att;
              if(att.length === 0){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: 'Remarks'},
          {field: 'price2', title: 'Price (EUR)', edit: 'text'}
        ]]
      });
      // 价格编辑
      table.on('edit(items)', function(obj){
        // 取到修改的价格字段值
        var value = obj.value;
        // 取到被修改的产品数据id
        var data = obj.data;
        var itemId = data.id;

        // 改动完即同步数据库
        $.ajax({
          type: 'POST',
          // 同步接口，传数据ID和修改后的金额值
          url: '/items?id=' + itemId + '&price=' + value,
          success: function(){
            layer.msg('Quote has been saved!');
            table.reload('items',{}); // 重载数据表格
          },
          error: function(){
            layer.msg('Error');
          }
        })
      })
    }

    // 产品图片预览
    window.showImg = function(t){
      var t = $(t).find("img");
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
  exports('watingquote', {});
});
