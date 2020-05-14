layui.define(function(exports){

  //
  layui.use(['table','jquery'], function(){

    var table = layui.table;
    var $ = layui.jquery;

    table.render({
      elem: '#cancelled',
      height: 'full-115',
      toolbar: '#cancelledBar',
      url: 'purchaser-cancel-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'project_name', title: '项目名称'},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'},
        {field: 'quote', title: '报价'}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(cancelled)', function(obj){
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
        url: 'products?id=' + id, //数据接口
        toolbar: '#showItemsBar',
        skin: 'row',
        even: true,
        totalRow: true, //开启合计行
        cols: [[
          {field: 'item', title: '名称'},
          {field: 'qty', title: '数量', totalRow: true},
          {field: 'image', title: '样式图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.image+'"></div>'
            }
          },
          {field: 'supplier', title: '供应商'},
          {field: 'model', title: '型号'},
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
          {field: 'des', title: '备注'},
          {field: 'price', title: '价格', totalRow: true}
        ]]
      })
    }

  });
  //
  exports('cancel', {});
});
