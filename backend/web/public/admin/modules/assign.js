layui.define(function(exports){

  //
  layui.use(['table','form','laydate','jquery'], function(){

    var table = layui.table;
    var form = layui.form;
    var laydate = layui.laydate;
    var $ = layui.jquery;


    var order = table.render({
      elem: '#assign',
      height: 'full-115',
      toolbar: '#quoteBar',
      url: 'assign-orders', //数据接口
      page: true, //开启分页
      // skin: 'line',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'project_name', title: '项目名称'},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'},
        {field: 'owner', title: '采购商'}
      ]]
    });
    // 表格菜单事件
    table.on('toolbar(assign)', function(obj){
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
              btn: ['保存','取消'],
              resize: false,
              success: showItems(id),
              yes: function(){
                changeStatus(id);
                layer.msg('success');
              }
            });
          }
        break;
        //  使订单生效并流转下个流程
        case 'save':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            var id = data[0].id;
            var project = data[0].project_name;

            $.ajax({
              type: 'POST',
              url: '/api/order/status?id=' + id,
              error: function(){
                layer.alert('此订单至少有一件或多件商品未明确报价方！<br>请返回操作产品清单核查…', function(index){
                  layer.open({
                    type: 1,
                    title: '项目名称 - ' + project,
                    area: ['99%', '98%'],
                    content: $('#showItems'),
                    resize: false,
                    success: showItems(id)
                  });
                  layer.close(index);
                });
              },
              success: function(){
                changeStatus(id);
              }
            });
          }
        break;
      };
    });
    // 显示产品清单方法
    window.showItems = function(id){

      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#showItemsBar',
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
          {field: 'supplier', title: '供应商'},
          {field: 'type', title: '型号'},
          {field: 'size', title: '图纸尺寸'},
          {field: 'material', title: '材质'},
          {field: 'att', title: '附件',
            templet: function(d){
              var att = d.att;
              if(att.length === 0){
                return ''
              }else{
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'desc', title: '备注'},
          {field: 'price', title: '单价(欧元)'},
          {field: 'quoteOwner', title: '报价方'},
          {field: 'supplier_name', title: '供应商'},
          {fixed: 'right', title:'操作', toolbar: '#action', width:90}
        ]],
        //
        done: function(res, curr, count){
          // 打开产品清单弹层时取供应商列表赋值给下拉菜单
          manufacturer();
        }
      });
    }
    // 监听行工具事件
    table.on('tool(items)', function(obj){
      // 获取行数据ID
      var data = obj.data
      var id = data.id

      // 报价前先清空价格和供应商名称和ID
      obj.update({
        price: '',
        quoteOwner: '',
        quoteOwnerId: ''
      });

      switch (obj.event) {
        case 'set':
          $('#quoteForm')[0].reset();
          $('#priceInput').show();
          // 供货商指定与报价设置
          var setPop = layer.open({
            type: 1,
            title: '选择供应商与报价方',
            area: ['720px','auto'],
            btn: ['保存','取消'],
            content: $('.manuList'),
            yes: function(){
              // 取供货商ID
              var bid = $("#manuList").val();
              // 更新单条产品数据
              $.ajax({
                type: 'POST',
                url: '/api/items/update?id=' + id + '&ghs=' + bid
              });
            }
          });
        break;
      }
    });

    // 判断报价方式的开关状态
    form.on('switch(price)', function(obj){
      if( obj.elem.checked === true){
        $('#priceInput').hide();
      }else{
        $('#priceInput').show();
      }
    });
    // 产品图片预览
    window.showImg = function(t){
      var t = $(t).find("img");
      console.log(t);
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
    // 取供应商列表追加到下拉框
    window.manufacturer = function(para){
      $.ajax({
        type: 'get',
        async: false,
        url: 'user',
        success: function(res){
          var list = res.data;
          $.each(list, function(i,n){
            var id = n.id;
            var name = n.name;
            $("#manuList").append("<option value='" + id + "'>" + name + "</option>");
          });
        }
      });
      return false;
    }
    // 改变订单状态
    window.changeStatus = function(id){
      $.ajax({
        type: 'POST',
        url: '/api/order/status?id=' + id,
        error: function(){
          layer.msg('error...');
        },
        success: function(){
          layer.msg('error...');
        }
      });
    }
  });
  //
  exports('assign', {});
});
