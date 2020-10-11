layui.define(function(exports){

  //
  layui.use(['table','element','form','laydate'], function(){

    var table = layui.table;
    var element = layui.element;
    var form = layui.form;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
      elem: '#processList' //指定元素
    })

    table.render({
      elem: '#completed',
      height: 'full-115',
      toolbar: '#completedBar',
      url: 'list', //数据接口
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
        {field: 'project_name', title: 'Project Name'},
        {field: 'create_time', title: 'Create Time'},
        {field: 'date', title: 'Delivery Date'},
        {field: 'package', title: 'Package'},
        {field: 'name', title: 'Name'},
        {field: 'address', title: 'Address'},
        {field: 'total', title: 'Quote'}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(completed)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select an order first!");
          }else{
            // 取订单ID 和 项目名称
            var data = checkStatus.data;
            var id = data[0].id;
            var project = data[0].project_name;
            // 打开详情
            layer.open({
              type: 1,
              title: 'Project Name - ' + project,
              area: ['90%', '80%'],
              content: $('#showItems'),
              resize: false,
              success: showItems(id)
            });
          }
        break;
        // track order
        case 'track':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select an order first!");
          }else{

          }
      };
    });
    // 显示产品清单方法
    window.showItems = function(id){

      table.render({
        elem: '#items',
        url: '/manufacturer/items?id=' + id, //数据接口
        toolbar: '#showItemsBar',
        skin: 'row',
        even: true,
        totalRow: true, //开启合计行
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
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: 'Remarks'},
          {field: 'origin_price', title: 'Origin Price(EUR)'},
          {field: 'price', title: 'Price (EUR)'}
        ]]
      })
    };
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
  exports('completed', {});
});
