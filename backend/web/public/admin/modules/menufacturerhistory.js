layui.define(function(exports){

  //
  layui.use(['table','jquery'], function(){

    var table = layui.table;
    var $ = layui.jquery;

    table.render({
      elem: '#history',
      height: 'full-115',
      toolbar: '#historybar',
      url: 'history-list?type=menufacturer', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: 'Order Number'},
        {field: 'project_name', title: 'Product Name'},
        {field: 'create_time', title: 'Create Date'},
        {field: 'date', title: 'Delivery Date'},
        {field: 'package', title: 'Package'},
        {field: 'name', title: 'Contact'},
        {field: 'address', title: 'Address'},
        {field: 'total', title: 'Quote'}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(history)', function(obj){
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
            // 打开详情
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
      };
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
          {field: 'brand', title: 'Name'},
          {field: 'number', title: 'Qty', totalRow: true},
          {field: 'files', title: 'Image',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'product_supplier', title: 'Brand'},
          {field: 'type', title: 'Model'},
          {field: 'size', title: 'Size'},
          {field: 'material', title: 'Material'},
          {field: 'att', title: 'Attachment',
            templet: function(d){
              var att = d.att;
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: 'Remake'},
          {field: 'price', title: 'Price'},
          {field: 'rowTotal', title: 'Subtotal',  templet: function(d){
              var price = d.price, qty = d.number;
              return Math.round(qty * price);
            }, totalRow: true}
        ]]
      })
    }

  });
  //
  exports('menufacturerhistory', {});
});
