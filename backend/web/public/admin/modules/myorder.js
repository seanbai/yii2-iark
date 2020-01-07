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
      url: '../../admin/json/workflow.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'num', title: 'Order Number'},
        {field: 'date', title: 'Expect Delivery Date'},
        {field: 'status', title: 'Status'},
        {field: 'orderdate', title: 'Order Date', templet:'<div>{{d.order.date}}</div>'},
        {field: 'price', title: 'Price Quote', templet:'<div>{{d.offer.price}}</div>'}
      ]],
      done: function(res, curr, count){
        $(".layui-table-view[lay-id='workflow'] .layui-table-body tr[data-index=0] .layui-form-radio").click();
      }
    });
    //
    workflow.on('toolbar(flow)', function(obj){
      var checkStatus = workflow.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;
      // var orderDate = JSON.stringify(jsonData);
      console.log(jsonData);
      // console.log(jsonData.ignoreNum);
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
              content: 'status_form.html',
              btn: ['Save','Close'],
              resize: false
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
      // 获取选中行数据
      var data = obj.data;
      var id = data.id; //示例：获取左侧选中行的ID
      console.log(id);

      products.render({
        elem: '#products',
        height: 'full-115',
        url: '../../admin/json/products.json', //数据接口
        where: {id: id},  // 传指定ID取产品列表数据
        cellMinWidth: 100,
        skin: 'row',
        even: true,
        cols: [[ //表头
          {field: 'id', width: 80, title: 'No.'},
          {field: 'brand', width: 120, title: 'Brand'},
          {field: 'type', width: 120, title: 'Type'},
          {field: 'qty', width: 60, title: 'Qty'},
          {field: 'des', title: 'Description'}
        ]]
      });
    });
  });
  //
  exports('myorder', {});
});
