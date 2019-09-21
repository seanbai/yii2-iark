layui.define(function(exports){
  //
  layui.use(['table'], function(){
    var table = layui.table;
    //
    table.render({
      elem: '#manufacturer',
      height: 'full-115',
      toolbar: '#toolbar',
      url: '../../admin/json/manufacturer.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'brand', title: 'Purchaser Name'},
        {field: 'boss', title: '负责人'},
        {field: 'phone', title: 'Phone'},
        {field: 'mail', title: 'Email'},
        {field: 'city', title: 'City'},
        {field: 'address', title: 'Address'},
        {field: 'username', title: 'Username'}
      ]]
    });
    //
    table.on('toolbar(manufacturer)', function(obj){
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
  exports('manufacturer', {});
});
