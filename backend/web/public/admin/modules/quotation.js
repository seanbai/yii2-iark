layui.define(function(exports){
  //
  layui.use(['table','jquery','form'], function(){
    var table = layui.table;
    var $ = layui.jquery;
    //
    var workflow = table.render({
      elem: '#myOrder',
      height: 'full-115',
      toolbar: '#toolbar',
      url: 'list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: 'Order Number'},
        {field: 'date', title: 'Expect Delivery Date'}, //期望交付时间
        {field: 'create_time', title: 'Order Date'} //创建时间
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
              area: ['99%', '98%'],
              content: $('#showItems'),
              btn: ['Submit Quote','Cancel'],
              success: showItems(id),
              yes: function(){
                layer.confirm('The quotation has been completed and verified?', function(index){
                  $.ajax({
                    type: 'POST',
                    url: 'submit-quote',
                    data: {
                      id: id
                    },
                    error: function(){
                      layer.msg('the request error!');
                    },
                    success: function(response){
                      if(response.code != 200){
                        layer.msg(response.msg,{icon: 6});
                      }else{
                        // 关闭弹层
                        layer.close(itemsbox);
                      }
                      // 表格重载
                      workflow.reload();
                    }
                  });
                })
              }
            });
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
        totalRow: true,
        cols: [[
          {field: 'brand', title: 'Item'},
          {field: 'number', title: 'Qty', totalRow: true},
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
          {field: 'price', title: 'Price (EUR)',  totalRow: true, edit: 'text'}
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
          url: 'quote-items?id=' + itemId + '&price=' + value,
          success: function(response){
            if(response.code == 200){
              layer.msg('Quote has been saved!');
              table.reload('items',{}); // 重载数据表格
            }else {
              layer.msg(response.msg,{icon: 6});
            }
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
  exports('quotation', {});
});
