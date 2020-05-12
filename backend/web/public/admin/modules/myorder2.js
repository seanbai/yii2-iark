layui.define(function(exports){

  //
  layui.use(['table','element','form','laydate'], function(){

    var order = layui.table;
    var element = layui.element;
    var form = layui.form;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
      elem: '#processList' //指定元素
    });

    order.render({
      elem: '#order',
      height: 'full-115',
      toolbar: '#toolbarDemo',
      url: 'purchaser-cancel-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'num', title: '订单号',templet:'<div>{{d.order_number}}</div>'},
        {field: 'status', title: '订单状态',templet:'#orderStatus'},
        {field: 's_price', title: '报价金额', sort: true, templet:'<div>{{d.product_amount}}</div>'},
        {field: 'dj', title: '税金和运费', templet:'<div>{{d.tax}}</div>'},
        {field: 'orderDate', title: '创建时间', templet:'<div>{{d.create_time}}</div>'},
        {field: 'date', title: '期望交付时间', templet:'<div>{{d.date}}</div>'},
      ]],
    });

    // 表格菜单事件
    order.on('toolbar(order)', function(obj){
      var checkStatus = order.checkStatus(obj.config.id);
      console.log(checkStatus);
      switch(obj.event){
          // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.open({
              type: 2,
              title: '产品清单',
              area: ['960px', '540px'],
              content: 'products?orderId='+checkStatus.data[0].id,
              btn: ['Close'],
              resize: false,
              yes: function(index, layero){
                layer.closeAll();
              }
            });
          }
          break;
      }
    });
    // 表格行单击事件
    order.on('rowDouble(processList)',function(obj){
      console.log(obj.tr);
      var data = obj.data;
      layer.alert(JSON.stringify(data), {
        title: '当前行数据：'
      });
    });
  });
  //
  exports('myorder', {});
});
