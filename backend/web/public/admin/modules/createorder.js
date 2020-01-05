layui.define(function(exports){
  // include form
  layui.use(['form','upload','laydate'], function(){

    var form = layui.form;
    var upload = layui.upload;
    var laydate = layui.laydate;

    laydate.render({
      elem: '#date'
    });

    var uploadInst = upload.render({
      elem: '#upload',
      url: '/upload', //上传接口
      before: function(){
        layer.load(); //loading 效果
      },
      done: function(res){
        layer.closeAll('loading'); //关闭loading
      },
      error: function(){
        layer.closeAll('loading');
      }
    })

  });
  //
  exports('createorder', {});
});
