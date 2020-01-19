layui.define(function(exports){

  //
  layui.use(['table','element','form','laydate'], function(){

    var order = layui.table;
    var element = layui.element;
    var form = layui.form;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
      elem: '#order' //指定元素
    });

    order.render({
      elem: '#order',
      height: 'full-115',
      toolbar: '#toolbarDemo',
      url: 'list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'num', title: '订单号',templet:'<div>{{d.order_number}}</div>'},
        {field: 'status', title: '订单状态',templet:'#orderStatus'},
        {field: 'date', title: '期望交付时间', templet:'<div>{{d.date}}</div>'},
      ]],
    });

    // 表格菜单事件
    order.on('toolbar(order)', function(obj){
      var checkStatus = order.checkStatus(obj.config.id);
      console.log(checkStatus);
      switch(obj.event){
          // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.open({
              type: 2,
              title: '产品清单',
              area: ['960px', '540px'],
              content: 'products?orderId='+checkStatus.data[0].id,
              btn: ['Close'],
              resize: false,
              yes: function(index, layero){
                layer.closeAll();
              }
            });
          }
          break;
          // track order
        case 'status':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.open({
              type: 2,
              title: '修改订单状态',
              area: ['640px', '420px'],
              content: 'status?id='+checkStatus.data[0].id,
              // btn: ['Save','Close'],
              resize: false,
            });
          }
      }
    });
    // 表格行单击事件
    order.on('rowDouble(processList)',function(obj){
      console.log(obj.tr);
      var data = obj.data;
      layer.alert(JSON.stringify(data), {
        title: '当前行数据：'
      });
    });

    // 提交表单,改变订单状态操作
    form.on('submit(update)', function(data){
      console.log(data.field);
      var formData = data.field;
      //收取定金
      if (formData.status == 10 && formData.prepayment == '0'){
        layer.msg('请确定是否收取到定金',{
          icon: 0,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
      }
      //开始生成
      if (formData.status == 11 && formData.in_production == '0'){
        layer.msg('请选择是否开始生成',{
          icon: 0,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
      }
      //尾款申请
      if (formData.status == 12 && formData.balance == '0'){
        layer.msg('请选择是否开始申请尾款',{
          icon: 0,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
      }
      //尾款确认
      if (formData.status == 13 && formData.final == '0'){
        layer.msg('请选择尾款是否到账',{
          icon: 0,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
      }
      if (formData.status == 14 && formData.pick == '0'){
        layer.msg('请确认是否可以提货',{
          icon: 0,
          time: 1000 //2秒关闭（如果不配置，默认是3秒）
        });
        return false;
      }

      $.ajax({
        type: 'post',
        dataType: 'json',
        data: formData,
        url: "update",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(e){ // 保存成功处理
          if (e.errCode == 0){
            layer.msg('保存成功',{
              icon: 1,
              time: 1000 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
              parent.layer.closeAll();
              parent.location.reload();
            });
          } else {
            layer.msg(e.errMsg);
          }
        }
      });
      return false;
    });
  });
  //
  exports('myorder', {});
});
