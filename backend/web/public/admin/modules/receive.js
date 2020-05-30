layui.define(function(exports){

  //
  layui.use(['table','jquery','form'], function(){

    var table = layui.table;
    var $ = layui.jquery;
    var form = layui.form;

    var order = table.render({
      elem: '#quote',
      height: 'full-115',
      toolbar: '#quoteBar',
      url: 'receive-orders', //数据接口
      page: true, //开启分页
      // skin: 'line',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'project_name', title: '项目名称'},
        {field: 'order_status', title: '订单状态', width:150},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'owner', title: '采购商'},
        {field: 'quote', title: '总计'},
        {fixed: 'right', title:'操作', toolbar: '#action', width:100}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(quote)', function(obj){
      // 取被选中数据的ID
      var checkStatus = table.checkStatus(obj.config.id);
      var data = checkStatus.data;

      switch(obj.event){
        // show details
        case 'details':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            var id = data[0].id;
            var project = data[0].project_name;

            layer.open({
              type: 1,
              title: '项目名称 - ' + project,
              area: ['99%', '98%'],
              content: $('#showItems'),
              resize: false,
              success: showItems(id)
            });
          }
        break;
      };
    });
    // 收款确认按钮
    table.on('tool(quote)', function(obj){
      var data = obj.data;
      var id = data.id;
      var total = data.quote;
      var status = data.order_status;
      var tax = data.tax;
      total = total ? parseInt(total) : 0;
      switch(obj.event){
        case 'confirm':
          if(status == 5){
            //询问框
            layer.confirm('是否已通知采购方支付定金？', {
              btn: ['是','否'] //按钮
            }, function(){
              $.ajax({
                type: 'post',
                dataType: 'json',
                data: {
                  id: id,
                  status: 6
                },
                url: 'update-status',
                success:function () {
                  order.reload();
                  layer.closeAll();
                }
              });
            },function(){
              layer.closeAll();
            });
          }else{
            var form = layer.open({
              type: 1,
              title: '收款确认',
              area: ['640px', 'auto'],
              content: $('#confirmPayment'),
              success: function(layero, index){
                $('#orderId').val(id);
                $('#total').val(total);
                $('#deposit').val(total/2);
                $('#balance').val(total/2);
                $('#tax').val(tax);
              }
            });
          }
          break;
        default:
      }
    });
    // 显示产品清单方法
    window.showItems = function(id){

      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true,
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: '名称'},
          {field: 'number', title: '数量'},
          {field: 'files', title: '样式图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'supplier_name', title: '供应商'},
          {field: 'type', title: '型号'},
          {field: 'size', title: '图纸尺寸'},
          {field: 'material', title: '材质'},
          {field: 'att', title: '附件',
            templet: function(d){
              var att = d.att;
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: '备注'},
          {field: 'price', title: '单价（欧元）'},
          {field: 'total', title: '合计(欧元)',
            totalRow: true}
        ]]
      });
    }
    // 产品图片预览
    window.showImg = function(t){
      var t = $(t).find("img");
      // 图片 lightbox
      layer.open({
        type: 1,
        title: false,
        skin: 'layui-layer-rim',
        area: ['auto'],
        shadeClose: true,
        end: function(index, layero){
          return false;
        },
        content: '<div style="text-align:center"><img width="500" src="' + $(t).attr('src') + '" /></div>'
      });
    }
    // 表单提交
    form.on('submit(confirmPayment)',function(data,id){
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: 'receive-confirm',
        error: function(){
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(res){
          if(res.errCode == 0){
            layer.msg('操作成功');
            layer.closeAll();
            order.reload();
          }else{
            layer.msg(res.errMsg);
          }
        }
      });
      return false;
    })
  });
  //
  exports('receive', {});
});
