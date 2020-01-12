layui.define(function(exports){
  //
  layui.use(['table','jquery'], function(){
    var table = layui.table;
    var $ = layui.$;
    //
    table.render({
      elem: '#workflow',
      height: 'full-115',
      toolbar: '#toolbar',
      // url: '../../admin/json/workflow.json', //数据接口
      url: 'list',
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'line',
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'case', width: 200, title: 'Case Number', templet:'<div>{{d.order_number}}</div>'},
        {field: 'date', title: 'Case Date',templet:'<div>{{d.date}}</div>'},
        {field: 'status', title: 'Status',templet:'<div>{{d.order_status}}</div>'},
        {field: 'orderdate', title: 'Order Date', templet:'<div>{{d.create_time}}</div>'},
      ]],
      done: function(res, curr, count){
        var vheight = $('#leftCard').height();
        $('#rightCard').height(vheight);
      }
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
