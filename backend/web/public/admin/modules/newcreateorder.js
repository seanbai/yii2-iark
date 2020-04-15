layui.define(function(exports){
  // include form
  layui.use(['form','upload','laydate','table','jquery'], function(){

    var form = layui.form;
    var upload = layui.upload;
    var laydate = layui.laydate;
    var items = layui.table;
    var $ = layui.jquery;

    laydate.render({
      elem: '#delivery'
    });

    var tableIns = items.render({
      elem: '#items',
      url:  'items',
      toolbar: '#createOrder',
      cellMinWidth: 100,
      skin: 'row',
      limit: 10,
      even: true,
      cols: [[ //表头
        {field: 'id', title: 'ID', width:80},
        {field: 'brand', title: '品牌'},
        {field: 'model', title: '型号名称'},
        {field: 'image', title: '样式图片', templet: '#itemImage'},
        {field: 'size', title: '尺寸'},
        {field: 'material', title: '材质'},
        {field: 'qty', title: '数量'},
        {field: 'desc', title: '备注'},
        {fixed: 'right', title:'操作', toolbar: '#delete', width:140}
      ]],
    });

    // 按钮事件
    items.on('toolbar(items)', function(obj){
      switch (obj.event) {
        // create
        case 'create':
          layer.open({
            type: 1,
            title: '添加产品信息',
            area: ['640px', '500px'],
            content: $('#addItem'),
            resize: false
          });
        break;
      }
    });

    // 监听行删除事件
    items.on('tool(items)',function(obj){
      var data = obj.data;
      console.log(data);
      if(obj.event === 'del'){
        $.ajax({
          type: 'POST',
          url: 'delete',
          data:{
            'id': data.pid,
            '_csrf': $('meta[name=csrf-token]').attr('content'),
          },
          error: function(){ // 保存错误处理
            layer.msg('系统错误,请稍后重试.');
          },
          success: function(){ // 保存成功处理
            // 成功提示
            layer.msg('删除成功');
            // 表格重载
            tableIns.reload();
          }
        })
      }else{

      }
    });

    // 图片上传事件
    upload.render({
      elem: '#test3',
      url: '/uploads/upload?',
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      done: function(res, index, upload){
         if (res.code == 200) {
           //将图片添加到input
           $('#image').attr('value',res.data);
           layer.msg('上传成功');
         } else {
           layer.msg('上传失败');
         }
      }
    });

    // 提交创建表单
    form.on('submit(addItem)', function(data){
      console.log(data.field);
      var $post = data.field;
      $post._csrf = $('meta[name=csrf-token]').attr('content');
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: $post,
        url: "product",
        error: function(){ // 保存错误处理
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(){ // 保存成功处理
          // 成功提示
          layer.msg('保存成功!您可以继续添加产品.');
          // 保留弹层同时清空表单缓存
          $('#addItems')[0].reset();
          layer.closeAll();
          // 表格重载
          tableIns.reload();
        }
      });
      return false;
    })
  });
  //
  exports('newcreateorder', {});
});
