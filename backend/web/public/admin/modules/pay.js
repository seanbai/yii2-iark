layui.define(function(exports){

  //
  layui.use(['table','jquery','form', 'upload'], function(){

    var table = layui.table;
    var $ = layui.jquery;
    var form = layui.form, upload = layui.upload;

    var order = table.render({
      elem: '#quote',
      height: 'full-115',
      toolbar: '#quoteBar',
      url: 'pay-orders', //数据接口
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
              success: showItems(id)
            });
          }
        break;
      };
    });
    // 付款确认按钮
    table.on('tool(quote)', function(obj){
      var data = obj.data;
      var id = data.id;
      var project = data.project_name;
      // 显示子订单
      switch(obj.event){
        case 'confirm':
          layer.open({
            type: 1,
            title: project + ' — 项目需要向以下供应商分别支付货款',
            area: ['99%', '98%'],
            content: $('#subOrderBox'),
            success: showSubOrder(id)
          });
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
          {field: 'product_supplier', title: '品牌'},
          {field: 'type', title: '型号'},
          {field: 'size', title: '产品尺寸'},
          {field: 'material', title: '材质'},
          {field: 'att', title: '附件',
            templet: function(d){
              var att = d.att;
              if(!att){
                return ''
              }else{
                return '<div><a href="'+d.att+'">Download</a></div>'
              }
            }
          },
          {field: 'supplier_name', title: '供货商'},
          {field: 'desc', title: '备注'},
          {field: 'origin_price', title: '价格(欧元)', totalRow: true},
          {field: 'price', title: '出厂价(欧元)', totalRow: true},
          {field: 'disc_price', title: '采购折扣价(欧元)', totalRow: true}
        ]]
      });
    }
    // 显示子订单
    window.showSubOrder = function(id){
      var subOrder = table.render({
        elem: '#subOrder',
        toolbar: '#toolbar',
        url: 'child-orders?parent_id='+id, //数据接口
        skin: 'row',
        even: true,
        cols: [[ //表头
          {field: 'supplier_name', title: '供货商'},
          {field: 'quote_time', title: '报价时间'},
          {field: 'original_price', title: '价格'},
          {field: 'total', title: '出厂价'},
          {field: 'deposit', title: '定金'},
          {field: 'deposit_file', title: '定金付款凭证',
            templet: function(d){
              if(d.deposit_file){
                return '<div onclick="showImg(this)"><img src="'+d.deposit_file+'"></div>'
              }
              return  '';
            }},
          // {field: 'depositdate', title: '定金支付时间'},
          {field: 'balance', title: '尾款'},
          {field: 'balance_file', title: '尾款付款凭证',templet: function(d){
              if(d.balance_file){
                return '<div onclick="showImg(this)"><img src="'+d.balance_file+'"></div>'
              }
              return  '';
            }},
          // {field: 'balancedate', title: '尾款支付时间'},
          {fixed: 'right', title:'操作', toolbar: '#logPay', width:100}
        ]]
      });
      // 操作事件
      table.on('tool(subOrder)', function(obj){
        var data = obj.data;
        var id = data.id;
        var statusId = data.order_status;
        var deposit = data.deposit ? parseInt(data.deposit) : 0;
        var balance = data.balance ? parseInt(data.balance) : 0;
        var total  = data.total ? parseInt(data.total) : 0;
        // 弹出付款登记
        switch(obj.event){
          case 'logPay':
            console.log(data);

            if(deposit <= 0 || balance <= 0){
              var form = layer.open({
                type: 1,
                title: '付款确认',
                area: ['640px', '600px'],
                scrollbar: true,
                content: $('#confirmPay'),
                success: function(layero, index){
                  $('#subOrderId').val(id);
                  if(data.deposit_file){
                    $('#deposit-img').hide();
                    $('#pay-file-deposit').attr('src', data.deposit_file);
                  }
                  if(data.balance_file){
                    $('#balance-img').hide();
                    $('#pay-file-balance').attr('src', data.balance_file);
                  }
                  $('#total').val(total).attr('disabled',true);
                  if(balance){
                    $('#balance').attr("disabled", true);
                  }
                  if(deposit){
                    $('#deposit').attr("disabled", true);
                  }
                  $('#balance').val(balance);
                  $('#deposit').val(deposit);
                }
              });
            }else{
              layer.alert("此订单已支付过，在订单状态更新前不可重复操作");
            }
          break;
        }
      });
    }

    //定金付款上传
    var uploadInst = upload.render({
      elem: '#deposit-img',
      url: '/uploads/upload?',
      size: 2*1024*1024, //kb
      exts: 'jpg|jpeg|png',
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      before: function(obj){
        //预读本地文件示例，不支持ie8
        obj.preview(function(index, file, result){
          $('#pay-file-deposit').attr('src', result).bind('click', function () {
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
              content: '<div style="text-align:center"><img width="500" height="500" src="' + result + '" /></div>'
            });
          });
        });
      },
      done: function(res){
        //如果上传失败
        if(res.code !== 200){
          return layer.msg('上传失败');
        }
        if(res.data !== undefined){
          $("#deposit-upload-file").val(res.data);
        }
        //上传成功
      },
      error: function(){
        //演示失败状态，并实现重传
        var demoText = $('#demoText-deposi');
        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs upload-reload">重试</a>');
        demoText.find('.upload-reload').on('click', function(){
          uploadInst.upload();
        });
      }
    });

    //付款品质上传
    var uploadBalance = upload.render({
      elem: '#balance-img',
      url: '/uploads/upload?',
      size: 2*1024*1024, //kb
      exts: 'jpg|jpeg|png',
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      before: function(obj){
        //预读本地文件示例，不支持ie8
        obj.preview(function(index, file, result){
          $('#pay-file-balance').attr('src', result).bind('click', function () {
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
              content: '<div style="text-align:center"><img width="500" height="500" src="' + result + '" /></div>'
            });
          });
        });
      },
      done: function(res){
        //如果上传失败
        if(res.code !== 200){
          return layer.msg('上传失败');
        }
        if(res.data !== undefined){
          $("#balance-upload-file").val(res.data);
        }
        //上传成功
      },
      error: function(){
        //演示失败状态，并实现重传
        var demoText = $('#demoText-balance');
        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs upload-reload">重试</a>');
        demoText.find('.upload-reload').on('click', function(){
          uploadInst.upload();
        });
      }
    });

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
    form.on('submit(confirmPay)',function(data,id){
        $.ajax({
          type: 'post',
          dataType: 'json',
          data: data.field,
          url: 'payment', //todo
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
