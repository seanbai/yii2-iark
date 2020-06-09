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
      url: 'wait-pick-list', //数据接口
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
              btn: ['确认已提货', '取消'],
              success: showItems(id),
              yes: function(){
                layer.confirm('是否已经提货完成', function(index){
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
              }
            });
          }
        break;
      }
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
  exports('watingquote', {});
});
