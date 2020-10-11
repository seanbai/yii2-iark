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
      elem: '#completed',
      height: 'full-115',
      toolbar: '#completedBar',
      //url: '../../admin/json/completed.json', //数据接口
      url: 'end',
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'ordernum', title: '单据编号'},
        {field: 'pubdata', title: '创建时间',sort: true},
        {field: 'finishdata', title: '期望交付时间', sort: true},
        {field: 'truedata', title: '实际交付时间', sort: true},
        {field: 'preprice', title: '定金'},
        {field: 'endprice', title: '尾款'},
        {field: 'tax', title: '税费'},
        {field: 'fullprice', title: '合计'},
        {field: 'address', title: '收货地址'},
        {field: 'statement', title: '结算方式'}
      ]],
      done: function(res, curr, count){
        element.render();
      }
    });
    // 表格菜单事件
    table.on('toolbar(completed)', function(obj){
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
        // track order
        case 'track':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据")
          }else{

          }
      };
    });
    // 表格行单击事件
    table.on('rowDouble(completed)',function(obj){
      console.log(obj.tr);
      var data = obj.data;
      layer.alert(JSON.stringify(data), {
        title: '当前行数据：'
      });
    });
  });
  //
  exports('completed', {});
});
