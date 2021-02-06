layui.define(function(exports){
  //
  layui.use(['table','jquery','form'], function(){
    var table = layui.table;
    var products = layui.table;
    var $ = layui.jquery;
    var form = layui.form;
    //
    var workflow = table.render({
      elem: '#myOrder',
      height: 'full-115',
      toolbar: '#toolbar',
      url: 'pending-order-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      text: {
        none: 'There are not any record' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
      },
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: 'Order Number'},
        {field: 'order_status_label', title: 'Order Status'},
        {field: 'total', title: 'Order Total'},
        //{field: 'deposit', title: 'Paid Deposit'},
        {field: 'deposit_file', title: 'Deposit Certificate', templet: function(d){
            if(d.deposit_file){
              return '<div onclick="showImg(this)"><img src="'+d.deposit_file+'"></div>'
            }
            return ''
          }},
        //{field: 'balance', title: 'Paid Balance'},
        {field: 'balance_file', title: 'Balance Certificate', templet: function(d){
            if(d.balance_file){
              return '<div onclick="showImg(this)"><img src="'+d.balance_file+'"></div>'
            }
            return ''
          }},
        {field: 'date', title: 'Expect Delivery Date'}, //期望交付时间
        {field: 'create_time', title: 'Order Date'}, //创建时间
        {field: 'quote_time', title: 'Quotation Date'} //创建时间
      ]]
    });

    //填写packing_number
    var packingNumbers = [];
    table.on('edit(items)', function(obj){
      // 取到修改的价格字段值
      var value = obj.value;
      // 取到被修改的产品数据id
      var data = obj.data;
      var itemId = data.id;
      packingNumbers.push({'id': itemId, 'value': value});
    });

    //
    table.on('toolbar(myOrder)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;
      switch(obj.event){
          // 查看产品清单
        case 'items':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select an order first!");
          }else{
            // 取订单ID 和 项目名称
            var data = checkStatus.data;
            var id = data[0].id;
            var num = data[0].order_number;
            // 打开产品列表弹层
            var itemsbox = layer.open({
              type: 1,
              title: 'Order Number: ' + num,
              area: ['95%', '65%'],
              content: $('#showItems'),
              btn: ['Submit Quote','Cancel'],
              resize: false,
              success: showItems(id),
              yes: function(){
                $.ajax({
                  type: 'POST',
                  url: 'submit-quote',
                  data: {
                    id: id
                  },
                  error: function(){
                    layer.msg('the request error!',{icon: 5});
                  },
                  success: function(response){
                    if(response.code == 200){
                      layer.msg(response.msg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function(){
                        layer.closeAll();
                      });
                    }else{
                      // 关闭弹层
                      layer.msg(response.msg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function(){
                        layer.closeAll();
                      });
                    }
                    layer.close(itemsbox);
                    workflow.reload();
                  }
                });
              }
            });
          }
          break;
          // 打开状态修改弹层
        case 'status':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select an order first!");
          }else{
            // 取 Order ID
            var data = checkStatus.data;
            var id = data[0].id;
            var statusId = data[0].order_status;
            var num = data[0].order_number;
            if(statusId < 81){
              layer.msg('The order not in production, can not do this action.');
              return false;
            }
            if( statusId == 81 ){
              // 状态码等于1时执行
              layer.confirm('Confirm receipt of deposit? <br>After confirmation, the order can start production',{
                btn: ['Confirm', 'Cancel'], title:'Change Order Status'}, function(index){
                $.ajax({
                  type: 'post',
                  // 同步接口，传数据ID和修改后的金额值
                  url: 'order-update',
                  data:{
                    id: id,
                    status: 91 //生成中
                  },
                  success: function(res){
                    if(res.code == 0){
                      layer.msg('The order status has been changed');
                      workflow.reload(); // 重载数据表格
                      layer.closeAll();
                    }else{
                      layer.msg(res.msg,{icon:5});
                      return false;
                    }

                  },
                  error: function(){
                    layer.msg('Error');
                  }
                })
              });
            }else if (statusId == 91) {
              // 状态码等于2时执行
              layer.confirm('Confirm production completion? <br>After confirmation, the other party will pay the final payment',{
                btn: ['Confirm', 'Cancel'], title:'Change Order Status'}, function(index){
                $.ajax({
                  type: 'post',
                  // 同步接口，传数据ID和修改后的金额值
                  url: 'order-update',
                  data:{
                    id: id,
                    status: 101 //生产完成
                  },
                  success: function(res){
                    if(res.code == 0){
                      layer.msg('The order status has been changed');
                      workflow.reload(); // 重载数据表格
                      layer.closeAll();
                    }else{
                      layer.msg(res.msg,{icon:5});
                      return false;
                    }
                  },
                  error: function(){
                    layer.msg('Error');
                  }
                })
              });
            }else if (statusId == 131) {
              // 状态码等于3时执行
              layer.confirm('Confirm receipt of balance? <br>After receiving the final payment, the buyer will pick up the goods',{
                btn: ['Confirm', 'Cancel'], title:'Change Order Status'}, function(index){
                //添加装货单号
                layer.open({
                  type: 1,
                  title: 'Add Packing Number - Order#'+num,
                  area: ['55%', '50%'],
                  content: $('#showItems'),
                  resize: false,
                  success: addPackingNumber(id),
                  btn: ['Ok', 'Cancel'],
                  yes: function () {
                    console.log(packingNumbers);
                    if(packingNumbers.length < 0){
                      layer.msg('Please fill packing number for per product',{icon:5});
                      return false;
                    }
                    $.ajax({
                      type: 'post',
                      // 同步接口，传数据ID和修改后的金额值
                      url: 'order-update',
                      data:{
                        id: id,
                        packing_numbers: packingNumbers,
                        status: 141 //子订单待提货
                      },
                      success: function(res){
                        if(res.code === 0){
                          layer.msg('The order status has been changed');
                          workflow.reload(); // 重载数据表格
                          layer.closeAll();
                          packingNumbers = [];
                        }else{
                          layer.msg(res.msg,{icon:5});
                          return false;
                        }
                      },
                      error: function(){
                        layer.msg('The request is abort');
                      }
                    })
                  }
                });
              });
            }
            // 提交报价

          }
          break;
      };
    });

    window.addPackingNumber = function(id){
      table.render({
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: 'Item'},
          {field: 'number', title: 'Qty'},
          {field: 'price', title: 'Price (EUR)'},
          {field: 'packing_number', title: 'Packing Number', edit: 'text'}
        ]]
      });
    }

    window.showItems = function(id){
      var ProductList = table.render({
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: 'Item'},
          {field: 'number', title: 'Qty'},
          {field: 'files', title: 'Image', width: 150,
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
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">Download</a></div>'
              }
            }
          },
          {field: 'desc', title: 'Remarks'},
          {field: 'uploads', title: 'Origin Price(EUR)' },
          {field: 'origin_price', title: 'Origin Price(EUR)', edit: 'text'},
          {field: 'ghs_file', title: '供货商附件',
            templet: function(d){
              var ghs_file = d.ghs_file;
              if(!ghs_file){
                return ''
              }else{
                return '<div><a href="'+d.ghs_file+'">Download</a></div>'
              }
            }
          },
          {fixed: 'action', title:'附件上传', toolbar: '#action', width:100}
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
          url: 'quote-item?id=' + itemId + '&price=' + value,
          success: function(response){
            if(response.code == 200){
              layer.msg('Quote has been saved!', {icon: 1});
              table.reload('items',{}); // 重载数据表格
            }else {
              layer.msg(response.msg,{icon: 5});
            }
          },
          error: function(){
            layer.msg('Error');
          }
        })
      });
      // 附件上传
      table.on('tool(items)', function(obj){
        var data = obj.data;
        var id = data.id;
        var title = data.title;
        // 显示子订单
        switch(obj.event){
          case 'file-uploads':
            layer.open({
              type: 1,
              title: '供货商附件上传',
              area: ['40%', '60%'],
              content: $('#ghs-upload'),
              success: function(){
                $('#deposit-img-tmp').attr('src', null);
              },
              btn: ['确认', '取消'],
              yes: function () {
                var deposit_file = $("#deposit-upload-file").val();
                if(!deposit_file){
                  layer.msg('请上传附件后进行保存',{icon:5});
                  return false;
                }

                $.ajax({
                  type: 'POST',
                  url: 'upload-ghx-file', //确认报价支付订单
                  data:{id: id, deposit_file: deposit_file},
                  error: function(){
                    layer.msg('系统异常...',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 900 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        ProductList.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                      });
                    }
                  }
                });
              }
            });
            break;
        }
      });
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

    // 附件上传事件
    upload.render({
      elem: '#deposit-img',
      url: '/uploads/uploads?',
      // 只允许压缩包格式
      accept: 'file',
      exts: 'zip|rar|7z',
      choose: function(obj){
        // 上传时加载 Loading
        layer.load();
      },
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      done: function(res){
        // 上传完成后关闭 Loading
        layer.closeAll('loading');
        if (res.code == 200) {
          //将图片添加到input
          $('#att').val(res.data);
          $('#fileName').html(res.data)
        } else {
          layer.msg('上传失败');
        }
      }
    });
  });
  //
  exports('production', {});
});
