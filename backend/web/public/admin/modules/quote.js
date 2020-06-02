layui.define(function(exports){

  //
  layui.use(['table','jquery'], function(){

    var table = layui.table;
    var $ = layui.jquery;

    var order = table.render({
      elem: '#quote',
      height: 'full-115',
      toolbar: '#quoteBar',
      url: 'quote-orders', //数据接口
      page: true, //开启分页
      // skin: 'line',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'project_name', title: '项目名称'},
        {field: 'create_time', title: '创建时间'},
        {field: 'order_status', title: '订单状态'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'},
        {field: 'owner', title: '采购商'},
        {field: 'quote', title: '报价'}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(quote)', function(obj){
      // 取被选中数据的ID
      var checkStatus = table.checkStatus(obj.config.id);
      var data = checkStatus.data;

      switch(obj.event){
        // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            var id = data[0].id;
            var project = data[0].project_name;

            layer.open({
              type: 1,
              title: '项目名称 - ' + project,
              area: ['99%', '98%'],
              content: $('#showItems'),
              btn: ['发送给采购方','关闭'],
              success: showItems(id),
              yes: function(){
                var id = data[0].id;
                changeStatus(id);
              }
            });
          }
        break;
        //
        case 'subOrder':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            var id = data[0].id;
            var project = data[0].project_name;

            layer.open({
              type: 1,
              title: '项目名称 - ' + project,
              area: ['99%', '98%'],
              content: $('#showItems'),
              btn: ['发送给采购方','关闭'],
              success: showItems(id),
              yes: function(){
                var id = data[0].id;
                changeStatus(id);
              }
            });
          }
        break;
      };
    });
    // 显示产品清单方法
    window.showItems = function(id){

      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true, //自动合计
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: '名称'},
          {field: 'number', title: '数量'},
          {field: 'files', title: '样式图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'supplier_name', title: '供应商'},
          {field: 'type', title: '型号'},
          {field: 'size', title: '图纸尺寸'},
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
          {field: 'desc', title: '备注'},
          {field: 'price', title: '单价(欧元)'},
          {field: 'price', title: '总价(欧元)', totalRow: true}
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
    // 改变订单状态
    window.changeStatus = function(id){
      $.ajax({
        type: 'POST',
        url: '/order/status?id=' + id + '&code=0',
        error: function(){
          layer.alert('订单中存在商品还未完成报价，请联系供货商。');
        },
        success: function(e){
          layer.msg();
        }
      });
    }
  });
  //
  exports('quote', {});
});
