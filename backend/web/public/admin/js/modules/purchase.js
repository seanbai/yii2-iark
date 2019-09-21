layui.use(['table','element','form','laydate'], function(){

  var table = layui.table;
  var element = layui.element;
  var form = layui.form;
  var laydate = layui.laydate;

  //执行一个laydate实例
  laydate.render({
    elem: '#test' //指定元素
  })

  table.render({
    elem: '#demo',
    height: 'full-115',
    toolbar: '#toolbarDemo',
    url: '/purchase.json', //数据接口
    cellMinWidth: 100,
    page: true, //开启分页
    cols: [[ //表头
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
      {field: 'company', title: '公司'},
      {field: 'address', title: '收货地址'},
      {field: 'statement', title: '结算方式',sort: true}
    ]],
    done: function(res, curr, count){
      element.render();
    }
  });
});
