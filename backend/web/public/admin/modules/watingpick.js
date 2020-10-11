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
        {type: 'checkbox'},
        {field: 'order_number', title: '项目编号'},
        {field: 'packing_number', title: '提货编码'},
        {field: 'project_name', title: '项目名称'},
        {field: 'package', title: '运输方式'},
        {field: 'brand', title: '产品名称'},
        {field: 'number', title: '数量'},
        {field: 'files', title: '产品图片',
          templet: function(d){
            return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
          }
        },
        {field: 'type', title: '型号'},
        {field: 'size', title: '产品尺寸'},
        {field: 'material', title: '材质'},
        {field: 'product_supplier', title: '品牌'},
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
        {field: 'desc', title: '备注'}
      ]]
    });

    // 表格工具
    table.on('toolbar(myOrder)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        // 查看产品清单
          case 'items':
            if(checkStatus.data.length === 0){
              layer.msg("请选择一条订单数据");
            }else{
              // 取订单ID 和 项目名称
              var data = checkStatus.data;
              // 打开产品列表弹层
              layer.msg("确定以下商品已完成提货操作");
              var fileData = [];
              var ids = [];
              for (var i=0;i<checkStatus.data.length;i++) {
                fileData.push(data[i]);
                ids += data[i].id+",";
              }
              console.log(ids);
              var itemsbox = layer.open({
                type: 1,
                title: '本次提货商品列表',
                area: ['90%', '80%'],
                content: $('#showItems'),
                btn: ['提交'],
                success: showItems(ids),
                yes: function(){
                  layer.confirm('是否确认这些商品提货完成', function(index){
                    $.ajax({
                      type: 'POST',
                      url: 'create-pick',
                      dataType: 'json',
                      data: { ids: fileData},
                      async: false,
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
                            time: 2000 //2秒关闭（如是果不配置，默认3秒）
                          }, function () {
                            layer.close(itemsbox);
                          });
                        }
                      }
                    });
                  })
                }
              })
            }
          break;
      }
    });

    window.showItems = function(id){
      table.render({
        elem: '#items',
        url: 'pick-items?ids='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: '产品名称'},
          {field: 'number', title: '数量'},
          {field: 'files', title: '图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'type', title: '型号'},
          {field: 'size', title: '尺寸'},
          {field: 'material', title: '材质'},
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
