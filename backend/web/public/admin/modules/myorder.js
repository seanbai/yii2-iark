layui.define(function(exports){
  //
  layui.use(['table','jquery','form'], function(){
    var workflow = layui.table;
    var products = layui.table;
    var $ = layui.jquery;
    //
    workflow.render({
      elem: '#workflow',
      height: 'full-115',
      toolbar: '#toolbar',
      url: 'list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'line',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'num', title: 'Order Number',templet:'<div>{{d.order_number}}</div>'},
        {field: 'phone', title: 'Phone',templet:'<div>{{d.phone}}</div>'},
        {field: 'status', title: 'Status',templet:'<div>{{d.order_status}}</div>'},
        {field: 'orderdate', title: 'Order Date', templet:'<div>{{d.create_time}}</div>'}
        // {field: 'price', title: 'Price Quote', templet:'<div>{{d.process}}</div>'}
      ]],
      done: function(res, curr, count){
        $(".layui-table-view[lay-id='workflow'] .layui-table-body tr[data-index=0] .layui-form-radio").click();
      }
    });
    //
    workflow.on('toolbar(flow)', function(obj){
      var checkStatus = workflow.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;
      console.log(jsonData[0].id);

      switch(obj.event){
          // 打开状态修改弹层
        case 'status':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select a piece of data first!");
          }else{
            layer.open({
              type: 2,
              title: 'Change Status',
              area: ['640px', '540px'],
              content: 'status?id='+jsonData[0].id,
              btn: ['Save','Close'],
              resize: false,
              yes: function(index, layero){
                $.ajax({
                  url:"update",
                  data:{'id':jsonData[0].id},
                  type:"Post",
                  dataType:"json",
                  success:function(data){


                    console.log(data);
                  },
                  error:function(data){
                    $.messager.alert('错误',data.msg);
                  }
                });
                layer.close(index);
              }
            });
          }
          break;
          // track order
        case 'ignore':
          if(checkStatus.data.length === 0){
            layer.msg("You should be select a piece of data first!")
          }else if(orderDate.order.offer === 8){
            alert("订单不允许取消");
          }
      };
    });
    // 单选左侧行，触发右侧产品数据表格刷新
    workflow.on('radio(flow)', function(obj){
      console.log(obj.data);
      var orderId = obj.data.id;
      $("#orderName").html(obj.data.order_number);

      products.render({
        elem: '#products',
        height: 'full-115',
        url: 'products?orderId='+orderId, //数据接口
        cellMinWidth: 100,
        skin: 'line',
        even: true,
        cols: [[ //表头
          {field: 'id', width: 80, title: 'No.',templet:'<div>{{d.id}}</div>'},
          {field: 'brand', width: 120, title: 'Brand',templet:'<div>{{d.brand}}</div>'},
          {field: 'type', width: 120, title: 'Type',templet:'<div>{{d.type}}</div>'},
          {field: 'qty', width: 60, title: 'Qty',templet:'<div>{{d.number}}</div>'},
          {field: 'des', title: 'Description', templet:'<div>{{d.desc}}</div>'},
          {field: 'file', title: '附件', templet:'<div>{{d.files}}</div>'}
        ]]
      });
    });
  });
  //
  exports('myorder', {});
});
