layui.define(function(exports){
  //
  layui.use(['form','jquery'], function(){
    var form = layui.form;
    var $ = layui.jquery;

    // 提交修改密码表单
    form.on('submit(setmypass)', function(data){
      console.log(data.field);
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: "/admin/reset-password/",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(){ // 保存成功处理
          // 成功提示
          layer.msg('保存成功');
        }
      });
      return false;
    })

  });
  //
  exports('setmypass', {});
});
