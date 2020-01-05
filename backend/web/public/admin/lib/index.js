/**

**/
// layui.config({
//   base: 'admin/modules/'
// });
//
// layui.use('element', function(){
//   var element = layui.element;
// });

layui.define(function(exports){

  layui.use('element', function(){
    var element = layui.element;
  });

  layui.config({
    base: '/public/admin/modules/'
  });

  exports('index');
})
