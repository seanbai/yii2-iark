layui.define(function(exports){
  //
  layui.use(['table'], function(){
    var table = layui.table;
    //
    table.render({
      elem: '#workflow',
      height: 'full-115',
      toolbar: '#toolbar',
      url: '../../admin/json/workflow.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'line',
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'case', width: 200, title: 'Case Number'},
        {field: 'date', title: 'Case Date'},
        {field: 'status', title: 'Status'},
        {field: 'link', width: 200, title: 'Link Order', templet:'<div>{{d.order.number}}</div>'},
        {field: 'orderdate', title: 'Order Date', templet:'<div>{{d.order.date}}</div>'},
        {field: 'offer', title: 'Offer', templet:'<div>{{d.order.offer}}</div>'},
        {field: 'info', title: 'Information', templet:'<div>{{d.order.info}}</div>'},
        {field: 'price', title: 'Price', templet:'<div>{{d.offer.price}}</div>'}
      ]]
    });
    //
    table.on('toolbar(workflow)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* del user */
        case 'del':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('Confirm?', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
        /* reset password */
        case 'reset':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('Confirm to reset user password?', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
      };
    });
    //
  });
  //
  exports('workflow', {});
});
