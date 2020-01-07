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
      //url: '../../admin/json/purchaser.json', //数据接口
      where: {limit: 10},
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
        {field: 'address', title: '地址'},
        {field: 'username', title: '登录账户'}
      ]],
      data : [{
        "id" : 1,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 2,
        "title" : "New Mind Design",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 3,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 4,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 5,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 6,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 7,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 8,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 9,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      },{
        "id" : 10,
        "title" : "Design4u Studio",
        "boss" : "Daniel Chen",
        "phone" : "18628077530",
        "mail" : "together008@gmail.com",
        "address" : "高新区天府软件园B区",
        "taxcode" : "91510100792198800C",
        "bank" : "建设银行高新区支行",
        "bnumber" : "6217 0038 0002 3254 596",
        "username": "danielchen008",
        "password": "123456"
      }]
    });
    //
    table.on('toolbar(purchaser)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* add a new user */
        case 'add':
          layer.open({
            type: 2,
            title: '添加新的采购商',
            area: ['640px', '500px'],
            content: 'add.html',
            resize: false
          });
        break;
        /* del user */
        case 'disabled':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('Confirm?', function(index){
              obj.del();
              layer.close(index);
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
        url: "....",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(){ // 保存成功处理
          // 成功提示
          layer.msg('已成功创建新的供应商账户');
          // 表格重载
          tableIns.reload({
            where: {limit: 10},
            page: {curr: 1}
          });

          // 关闭弹层
          // var index = parent.layer.getFrameIndex(window.name);
          // parent.layer.close(index);
        }
      });
      return false;
    })
  });
  //
  exports('purchaser', {});
});
