layui.define(function(exports){
  //
  layui.use(['table','jquery','form'], function(){
    var table = layui.table;
    var items = layui.table;
    var $ = layui.$;
    var form = layui.form;
    // 判断是否需要报价，切换价格框的输入状态
    form.on('select(bj)', function(data){
      var val = data.value;
      console.info(val);
      if(val == 1){
        $("#pPrice").val("").attr("disabled","disabled").addClass('layui-disabled');
      }else{
        $("#pPrice").removeAttr("disabled").removeClass('layui-disabled');
      }
    });
    //
    //
    table.render({
      elem: '#workflow',
      height: 'full-115',
      toolbar: '#toolbar',
      url: 'list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      title: "订单列表",
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'num', title: '订单号', templet:'<div>{{d.order_number}}</div>'},
        {field: 'status', title: '订单状态',templet:'#orderStatus'},
        {field: 'brand', title: '订货商',templet:'<div>{{d.name}}</div>'},
        {field: 'contact', title: '联系人',templet:'<div>{{d.date}}</div>'},
        {field: 'orderDate', title: '下单时间',templet:'<div>{{d.create_time}}</div>'},
        {field: 'date', title: '期望交货时间',templet:'<div>{{d.date}}</div>'},
        {fixed: 'right', title:'操作', toolbar: '#editTool', width:140}
      ]],
      done: function(res, curr, count){
        var vheight = $('#leftCard').height();
        $('#rightCard').height(vheight);
      }
    });
    //
    table.on('toolbar(workflow)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;
      console.log(jsonData);

      switch(obj.event){
          /* del user */
        case 'status':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.open({
              type: 2,
              title: '修改订单状态',
              area: ['640px', '540px'],
              content: 'update?id='+jsonData[0].id,
              resize: false,
              yes: function(index, layero){
                var from = $('#form-submit').serialize();
                $.ajax({
                  url:"status",
                  data: from,
                  type:"POST",
                  dataType:"json",
                  success:function(data){

                    console.log(data);
                  },
                  error:function(data){

                  }
                });
                // layer.close(index);
              }
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
      }
    });
    // 点击每一行的"订单分配"触发
    table.on('tool(workflow)', function(obj){
      var data = obj.data;
      // console.log(data)
      switch(obj.event){
        case 'edit':
          if(data.status === 1){
            layer.open({
              type: 1,
              title: '修改订单状态',
              content: $('.popList'),
              // maxmin: true,
              area: ['960px', '500px'],
              success: function(layero, index){
                items.render({
                  elem: '#items',
                  toolbar: '#itemsBar',
                  // url: '../../admin/json/products.json', //数据接口
                  where: {id: 1}, //传订单号
                  cellMinWidth: 100,
                  skin: 'row',
                  even: true,
                  cols: [[ //表头
                    {type:'checkbox'},
                    {field: 'brand', title: '品牌'},
                    {field: 'type', title: '型号'},
                    {field: 'qty', title: '数量'},
                    {field: 'des', title: '描述'},
                  ]],
                  "data" : [{
                    "id" : 1,
                    "brand": "Arflex",
                    "type": "cupboard",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 2,
                    "brand": "Arflex",
                    "type": "cupboard",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 3,
                    "brand": "Arflex",
                    "type": "cupboard",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 4,
                    "brand": "Arflex",
                    "type": "TV stand",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 5,
                    "brand": "Arflex",
                    "type": "coffee table",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 6,
                    "brand": "Arflex",
                    "type": "wardrobe",
                    "qty": 1,
                    "des": "here is demo description"
                  },{
                    "id" : 7,
                    "brand": "Arflex",
                    "type": "wardrobe",
                    "qty": 1,
                    "des": "here is demo description"
                  }]
                });
                //
                items.on('toolbar(items)', function(obj){
                  //
                  var checkItems = items.checkStatus(obj.config.id);
                  console.log(checkItems);
                  //
                  switch(obj.event){
                      // 打开状态修改弹层
                    case 'save':
                      if(checkItems.data.length === 0){
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
                  };
                });
              }
            });
          }else{
            layer.alert('此订单当前状态禁止分配');
          }
          break;
      }
    });
    //
    // 提交表单
    form.on('submit(create)', function(data){
      console.log(data.field);
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: "status",
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
  exports('workflow', {});
});
