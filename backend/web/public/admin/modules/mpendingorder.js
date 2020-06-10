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
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: 'Order Number'},
        {field: 'order_status_label', title: 'Order Status'},
        {field: 'date', title: 'Expect Delivery Date'}, //期望交付时间
        {field: 'create_time', title: 'Order Date'}, //创建时间
        {field: 'quote_time', title: 'Quotation Date'} //创建时间
      ]]
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
            layer.open({
              type: 1,
              title: 'Order Number: ' + num,
              area: ['95%', '65%'],
              content: $('#showItems'),
              resize: false,
              success: showItems(id)
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
                      table.reload(); // 重载数据表格
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
                      table.reload(); // 重载数据表格
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
                $.ajax({
                  type: 'post',
                  // 同步接口，传数据ID和修改后的金额值
                  url: 'order-update',
                  data:{
                    id: id,
                    status: 141 //子订单待提货
                  },
                  success: function(res){
                    if(res.code == 0){
                      layer.msg('The order status has been changed');
                      table.reload(); // 重载数据表格
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
            }
            // 提交报价

          }
          break;
      };
    });

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
              if(att.length === 0){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: 'Remarks'},
          {field: 'price', title: 'Price (EUR)'}
        ]]
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

  });
  //
  exports('production', {});
});
