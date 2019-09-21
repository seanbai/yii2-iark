layui.define(function(exports){
  //
  layui.use(['table','layer'], function(){
    var table = layui.table;
    var layer = layui.layer;
    $ = layui.jquery;
    //
    table.render({
      elem: '#purchaser',
      height: 'full-115',
      toolbar: '#toolbar',
      url: '../../admin/json/purchaser.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'title', title: '采购商名称'},
        {field: 'boss', title: '负责人'},
        {field: 'phone', title: '电话'},
        {field: 'mail', title: '邮箱'},
        {field: 'city', title: '省份城市'},
        {field: 'address', title: '地址'},
        {field: 'taxcode', title: '纳税人识别号'},
        {field: 'bank', title: '开户行'},
        {field: 'bnumber', title: '银行账号'}
      ]]
    });
    //
    table.on('toolbar(purchaser)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* add a new user */
        case 'add':
          layer.open({
            type: 1,
            content: $('#addBox'),
            area: '960px'
          })
        break;
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
  exports('purchaser', {});
});
