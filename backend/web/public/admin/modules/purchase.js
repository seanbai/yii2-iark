layui.define(function(exports){

  //
  layui.use(['table','element','form','laydate'], function(){

    var table = layui.table;
    var element = layui.element;
    var form = layui.form;
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
      elem: '#processList' //指定元素
    })

    table.render({
      elem: '#processList',
      height: 'full-115',
      toolbar: '#toolbarDemo',
      url: '../../admin/json/purchase.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'ordernum', title: '单据编号'},
        {field: 'status', title: '状态',width:80},
        {
          field: 'process',
          width: 300,
          sort: true,
          title: '进度',
          templet: function(d){
            var html = '<div class="table-process"><div class="layui-progress">';
            html += '<div class="layui-progress-bar layui-bg-red" lay-percent="'+d.process+'%"></div>';
            html += '</div></div>';
            return html;
          }
        },
        {field: 'pubdata', title: '创建时间',sort: true},
        {field: 'finishdata', title: '期望交付时间', sort: true},
        {field: 'owner', title: '制单人'},
        {field: 'telphone', title: '电话'},
        {field: 'address', title: '收货地址'},
        {field: 'statement', title: '结算方式',sort: true}
      ]],
      done: function(res, curr, count){
        element.render();
      }
    });
    // 表格菜单事件
    table.on('toolbar(processList)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.open({
              type: 2,
              title: 'Products List',
              area: ['960px', '540px'],
              content: 'items_list.html',
              btn: ['Close'],
              resize: false,
              yes: function(index, layero){
                layer.closeAll();
              }
            });
          }
        break;
        // cancel order
        case 'cancel':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据")
          }else{
            layer.confirm('Confirm cancel ?', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
        // track order
        case 'track':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据")
          }else{
            
          }
      };
    });
    // 表格行单击事件
    table.on('rowDouble(processList)',function(obj){
      console.log(obj.tr);
      var data = obj.data;
      layer.alert(JSON.stringify(data), {
        title: '当前行数据：'
      });
    });
  });
  //
  exports('purchase', {});
});
