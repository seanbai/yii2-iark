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
        {field: 'mail', title: '邮件',templet:'<div>{{d.email}}</div>'},
        {field: 'city', title: '城市',templet:'<div>{{d.city}}</div>'},
        {field: 'address', title: '地址',templet:'<div>{{d.address}}</div>'},
        {field: 'username', title: '用户名',templet:'<div>{{d.username}}</div>'},
        {field: 'contract', title: '签约时间',templet:'<div>{{d.time}}</div>'}
      ]]
    });
    //
    table.on('toolbar(manufacturer)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* del user */
        case 'create':
          layer.open({
            type: 2,
            title: 'Change Status',
            area: ['640px', '610px'],
            content: 'add',
            resize: false
          });
        break;
        /* reset password */
        case 'disabled':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('确认要停用此账户么？', function(index){
              obj.del();
              

              layer.close(index);
            });
          }
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
          // 成功提示
          if (e.errCode == 0){
            layer.msg('用户创建成功');
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
          } else {
            layer.msg(e.errMsg);
          }
          // 表格重载
          tableIns.reload({
            where: {limit: 10},
            page: {curr: 1}
          });
          // 关闭弹层

        }
      });
      return false;
    });
  });
  //
  exports('manufacturer', {});
});
