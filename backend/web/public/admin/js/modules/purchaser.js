layui.use(['table','element','form','laydate'], function(){

  var table = layui.table;
  var element = layui.element;
  var form = layui.form;
  var laydate = layui.laydate;

  //执行一个laydate实例
  laydate.render({
    elem: '#purchaser' //指定元素
  })

  table.render({
    elem: '#purchaser',
    height: 'full-115',
    toolbar: '#toolbarDemo',
    url: '/purchaser.json', //数据接口
    cellMinWidth: 100,
    page: true, //开启分页
    cols: [[ //表头
      {field: 'id', title: 'ID', width:80, sort: true},
      {field: 'title', title: '采购商名称'},
      {field: 'boss', title: '负责人'},
      {field: 'phone', title: '电话'},
      {field: 'mail', title: '邮箱'},
      {field: 'city', title: '省份城市'},
      {field: 'address', title: '地址'},
      {field: 'taxcode', title: '纳税人识别号'},
      {field: 'bank', title: '开户行'},
      {field: 'bnumber', title: '银行账号'},
      {fixed: 'right', title:'操作', toolbar: '#barDemo'}
    ]],
    done: function(res, curr, count){
      element.render();
    }
  });
});
