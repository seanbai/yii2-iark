layui.define(function(exports){
  //
  layui.use(['table','form','jquery'], function(){
    var table = layui.table;
    // var layer = layui.layer;
    var form = layui.form;
    var $ = layui.jquery;
    //
    var tableIns = table.render({
      elem: '#purchaser',
      height: 'full-115',
      toolbar: '#toolbar',
      url: 'list', //数据接口
      where: {limit: 10},
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'title', title: '采购商名称',templet:'<div>{{d.name}}</div>'},
        {field: 'boss', title: '联系人',templet:'<div>{{d.contact}}</div>'},
        {field: 'status', title: '状态',templet:'<div>{{d.status}}</div>'},
        {field: 'phone', title: '电话',templet:'<div>{{d.phone}}</div>'},
        {field: 'mail', title: '邮箱',templet:'<div>{{d.email}}</div>'},
        {field: 'address', title: '地址',templet:'<div>{{d.address}}</div>'},
        {field: 'username', title: '登录账户',templet:'<div>{{d.username}}</div>'}
      ]]
    });
    //
    table.on('toolbar(purchaser)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;
      console.log(jsonData[0].id);

      switch(obj.event){
          /* add a new user */
        case 'add':
          layer.open({
            type: 2,
            title: '添加新的采购商',
            area: ['640px', '500px'],
            content: 'add',
            resize: false
          });
          break;
          /* del user */
        case 'disabled':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('确定是否停用？', {
              btn: ['确定','取消'] //按钮
            }, function(){
              $.ajax({
                type: 'post',
                data: {
                  id : jsonData[0].id
                },
                url: "status",
                error: function(){ // 保存错误处理
                  layer.msg('系统错误，请稍后重试');
                },
                success: function(e){ // 保存成功处理
                  // 成功提示
                  if (e == 0){
                    layer.msg('禁用成功');
                    tableIns.reload();
                  } else {
                    layer.msg('禁用失败');
                  }
                }
              });
            }, function(){
              layer.msg('取消');
            });
          }
          break;
      };
    });
    // 提交表单
    form.on('submit(create)', function(data){
      console.log(data.field);
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: "create",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(e){ // 保存成功处理
          // 成功提示
          if (e.errCode == 0){
            layer.msg('用户创建成功');
          } else {
            layer.msg(e.errMsg);
          }
          // 表格重载
          tableIns.reload({
            where: {limit: 10},
            page: {curr: 1}
          });

          // 表格重载
          tableIns.reload({
            where: {limit: 10},
            page: {curr: 1}
          });
        }
      });
      return false;
    })
  });
  //
  exports('purchaser', {});
});
