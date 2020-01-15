layui.define(function(exports){
  //
  layui.use(['table','laydate','form','jquery'], function(){
    var table = layui.table;
    var laydate = layui.laydate;
    var form = layui.form;
    var $ = layui.jquery;

    laydate.render({
      elem: '#date'
    });
    //
    var tableIns = table.render({
      elem: '#manufacturer',
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
        {field: 'brand', title: '供货商名称',templet:'<div>{{d.name}}</div>'},
        {field: 'boss', title: '联系人',templet:'<div>{{d.contact}}</div>'},
        {field: 'phone', title: '联系电话',templet:'<div>{{d.phone}}</div>'},
        {field: 'status', title: '账户状态', templet:'#userStatus'},
        {field: 'mail', title: '邮件',templet:'<div>{{d.email}}</div>'},
        {field: 'city', title: '城市',templet:'<div>{{d.city}}</div>'},
        {field: 'address', title: '地址',templet:'<div>{{d.address}}</div>'},
        {field: 'username', title: '用户名',templet:'<div>{{d.username}}</div>'},
        {field: 'contract', title: '签约时间',templet:'<div>{{d.time}}</div>'},
        {fixed: 'right', title:'操作', toolbar: '#editTool', width:140}
      ]]
    });
    //
    table.on('toolbar(manufacturer)', function(obj){
      switch(obj.event){
        /* del user */
        case 'create':
          layer.open({
            type: 1,
            title: '供货商账户信息',
            area: ['640px', '600px'],
            content: $("#editForm"),
            resize: false
          });
        break;
      }
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
          if (e.errCode == 0){
            layer.msg('用户新增成功',{
              icon: 1,
              time: 1000 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
              layer.closeAll();
              tableIns.reload();
            });
          } else {
            layer.msg(e.errMsg);
          }
        }
      });
      return false;
    });

    // 创建表单
    form.on('submit(update)', function(data){
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: "status",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(data){ // 保存成功处理
          if (data.errCode == 0){
            layer.msg('用户修改成功',{
              icon: 1,
              time: 1000 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
              layer.closeAll();
              // 表格重载
              tableIns.reload();
            });
          } else {
            layer.msg(data.errMsg);
          }
        }
      });
      return false;
    });

    // 行编辑事件
    table.on('tool(manufacturer)', function(obj){
      var data = obj.data;
      var id = data.id;
      // 行工具事件
      if(obj.event === 'edit'){
        // 编辑事件
        layer.open({
          type: 1,
          title: '采购商账户信息',
          area: ['640px', '600px'],
          content: $('#editForm'),
          resize: false,
          success: function(){
            // 弹层打开成功将行数据赋值给表单
            form.val("edit",data);
            $('#username').attr("disabled","disabled").addClass('layui-disabled');
            $('#time').attr("disabled","disabled").addClass('layui-disabled');
            $('#layui-btn').attr("lay-filter",'update');
          }
        });
      }else if(obj.event === 'disable'){
        // 禁用事件
        layer.confirm('确认要禁用此账户？<br>禁用的账户不能登录系统，但历史订单将保留', function(index){
          $.ajax({
            type: 'post',
            url: 'status?id=' + id,
            success: function(){
              layer.msg('用户停用成功',{
                icon: 1,
                time: 1000
              }, function(){
                tableIns.reload();
              });
            },
            error: function(){
              layer.msg('操作失败请稍后重试');
            }
          });
        });
      }
    });
  });
  //
  exports('manufacturer', {});
});
