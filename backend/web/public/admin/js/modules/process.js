layui.use(['table','element'], function(){
  var table = layui.table;
  var element = layui.element;

  table.render({
    elem: '#processList',
    height: 'full-115',
    url: '/purchase.json', //数据接口
    cellMinWidth: 100,
    page: true, //开启分页
    cols: [[ //表头
      {field: 'id', title: 'ID', width:80, sort: true},
      {field: 'ordernum', title: '单据编号'},
      {field: 'status', title: '状态',width:80},
      {field: 'pubdata', title: '创建时间',sort: true},
      {field: 'finishdata', title: '期望交付时间', sort: true},
      {field: 'owner', title: '制单人'}
    ]],
    done: function(res, curr, count){
      element.render();
    }
  });
});
