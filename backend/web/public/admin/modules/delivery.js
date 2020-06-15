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
      url: 'delivery-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type: 'radio'},
        {field: 'name', title: '提货编号'},
        {field: 'product_ids', title: '提货的产品ids'},
        {field: 'created_at', title: '提货时间'}
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
              var id = data[0].product_ids;

              var itemsbox = layer.open({
                type: 1,
                title: '本次提货商品列表',
                area: ['90%', '80%'],
                content: $('#showItems'),
                success: showItems(id),
              })
            }
          break;
      }
    });

    window.showItems = function(id){
      table.render({
        elem: '#items',
        url: 'delivery-items?ids='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'project_name', title: '项目名称'},
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
