layui.define(function(exports){
  //
  layui.use(['table','jquery','form', 'upload'], function(){
    var table = layui.table;
    var $ = layui.jquery;
    var form = layui.form, upload = layui.upload;

    //
    var workflow = table.render({
      elem: '#myOrder',
      height: 'full-115',
      toolbar: '#orderBar',
      url: 'watingquote-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'order_number', title: '订单号'},
        {field: 'order_status_label', title: '订单状态'},
        {field: 'project_name', title: '项目名称'},
        {field: 'deposit_file', title: '定金付款凭证',
          templet: function(d){
            if(d.deposit_file){
              return '<div onclick="showImg(this)"><img src="'+d.deposit_file+'"></div>'
            }
            return  '';
          }},
        {field: 'balance_file', title: '尾款付款凭证',templet: function(d){
            if(d.balance_file){
              return '<div onclick="showImg(this)"><img src="'+d.balance_file+'"></div>'
            }
            return  '';
          }},
        {field: 'create_time', title: '创建时间'},
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'name', title: '提货联系人'},
        {field: 'address', title: '交付地址'}
      ]]
    });

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
          $('#deposit-img-tmp').attr('src', result).bind('click', function () {
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
        var demoText = $('#demoText');
        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs upload-reload">重试</a>');
        demoText.find('.upload-reload').on('click', function(){
          uploadInst.upload();
        });
      }
    });
    // 表格工具
    table.on('toolbar(myOrder)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      var jsonData = checkStatus.data;

      switch(obj.event){
        // 查看产品清单
        case 'items':
          if(checkStatus.data.length === 0){
            layer.msg("请选择一条订单数据");
          }else{
            // 取订单ID 和 项目名称
            var data = checkStatus.data;
            var id = data[0].id;
            var num = data[0].order_number;
            // 打开产品列表弹层
            var itemsbox = layer.open({
              type: 1,
              title: '订单号: ' + num,
              area: ['99%', '98%'],
              content: $('#showItems'),
              btn: ['确认报价', '拒绝报价', '取消'],
              success: showItems(id),
              yes: function(){
                layer.open({
                  type: 1,
                  title: '请上传支付凭证',
                  area: ['50%', '60%'],
                  content: $('.deposit-upload'),
                  success: function(){
                    $('#deposit-img-tmp').attr('src', null);
                    $('#deposit-amount').html(data[0].desc_quote / 2);
                  },
                  btn: ['确认', '取消'],
                  yes: function () {
                    var $deposit_file = $("#deposit-upload-file").val();
                    if(!$deposit_file){
                      layer.msg('请上传支付凭证',{icon:5});
                      return false;
                    }
                    $.ajax({
                      type: 'POST',
                      url: 'confirm-quote-pay-deposit', //确认报价支付订单
                      data:{id: id, deposit_file: $deposit_file},
                      error: function(){
                        layer.msg('系统异常...',{icon:5});
                      },
                      success: function(response){
                        if(response.errCode == 0){
                          layer.msg(response.errMsg, {
                            icon: 1,
                            time: 900 //2秒关闭（如果不配置，默认是3秒）
                          }, function () {
                            layer.closeAll();
                            workflow.reload();
                          });
                        }else{
                          layer.msg(response.errMsg, {
                            icon: 5,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                          }, function () {
                            layer.closeAll();
                          });
                        }
                      }
                    });
                  }
                });
                // layer.confirm('是否确认报价,并支付定金', function(index){
                //   $.ajax({
                //     type: 'POST',
                //     //url: 'update',
                //     url: 'confirm-quote-pay-deposit', //确认报价支付订单
                //     data:{
                //       id:id,
                //       status: 5
                //     },
                //     error: function(){
                //       layer.msg('系统异常...',{icon:5});
                //     },
                //     success: function(response){
                //       if(response.errCode == 0){
                //         layer.msg(response.errMsg, {
                //           icon: 1,
                //           time: 2000 //2秒关闭（如果不配置，默认是3秒）
                //         }, function () {
                //           layer.close(itemsbox);
                //           workflow.reload();
                //         });
                //       }else{
                //         layer.msg(response.errMsg, {
                //           icon: 5,
                //           time: 2000 //2秒关闭（如果不配置，默认是3秒）
                //         }, function () {
                //           layer.close(itemsbox);
                //         });
                //       }
                //     }
                //   });
                // })
              },
              btn2 : function () {
                layer.confirm('您确定拒绝报价吗？', function(){
                  $.ajax({
                    type: 'POST',
                    url: 'update',
                    data:{
                      id:id,
                      status: 401 //取消订单
                    },
                    error: function(){
                      layer.msg('系统异常...',{icon:5});
                    },
                    success: function(response){
                      // 关闭弹层
                      if(response.errCode == 0){
                        layer.msg('订单更新成功', {
                          icon: 1,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                          workflow.reload();
                        });
                      }else{
                        layer.msg('订单更新失败', {
                          icon: 5,
                          time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                          layer.close(itemsbox);
                        });
                      }
                    }
                  });
                });
                return false;
              }
            });
          }
        break;
      case 'payOrder':
          if(checkStatus.data.length === 0){
            layer.msg("请选择一条订单数据");
          }else {
            var order_data = checkStatus.data,
                order_id = order_data[0].id,
                total = order_data[0].desc_quote,
                order_status = order_data[0].order_status;

             layer.open({
              type: 1,
              title: '付款确认',
              area: ['640px', '400px'],
              content: $('#payOrderForm'),
              success: function(layero, index){
                console.log(total);
                total = total ? parseInt(total) : 0;
                $('#orderId').val(order_id);
                $('#order-total').val(total);
                if(total <= 0){
                  $('[data-role="subPay"]').hide();
                }else{
                  $('[data-role="subPay"]').show();
                }
                if(order_status < 6){
                  $('[data-role="pay-balance"]').hide();
                  $('[data-role="pay-tax"]').hide();
                  $('[data-role="pay-deposit"]').hide();
                  $('[data-role="pay-fuwu"]').hide();
                  $('[data-role="subPay"]').hide();
                }else{
                  $('[data-role="subPay"]').show();
                }
                if(order_status == 6){
                  $('#order-deposit').val(total/2);
                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').hide();
                  $('[data-role="pay-tax"]').hide();
                  $('[data-role="pay-fuwu"]').hide();
                }
                if(order_status == 11){
                  $('#order-deposit').val(total/2);
                  $('#order-balance').val( total/2);
                  $('[data-role="pay-deposit"]').show();
                  $('input[name="pay_deposit"]').attr({'checked':true,"disabled":true});
                  $('[data-role="pay-balance"]').show();
                  $('[data-role="pay-fuwu"]').hide();
                  $('[data-role="pay-tax"]').hide();
                }
                if(order_status == 14){
                  $('#order-deposit').val(total/2);
                  $('#order-balance').val( total/2);
                  $('input[name="pay_deposit"]').attr({'checked':true,"disabled":true});
                  $('input[name="pay_balance"]').attr({'checked':true,"disabled":true});

                  $('#order-tax').val(order_data[0].tax);
                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').show();
                  $('[data-role="pay-fuwu"]').hide();
                  $('[data-role="pay-tax"]').show();
                }

                if(order_status == 202){
                  $('#order-deposit').val(total/2);
                  $('#order-balance').val( total/2);
                  $('#order-tax').val(order_data[0].tax);
                  $('#order-fuwu').val(order_data[0].fuwu);
                  $('input[name="pay_deposit"]').attr({'checked':true,"disabled":true});
                  $('input[name="pay_balance"]').attr({'checked':true,"disabled":true});
                  $('input[name="pay_tax"]').attr({'checked':true,"disabled":true});

                  $('[data-role="pay-deposit"]').show();
                  $('[data-role="pay-balance"]').show();
                  $('[data-role="pay-tax"]').show();
                  $('[data-role="pay-fuwu"]').show();
               }
                form.render();
              }
            });
          }
        break;
      }
    });

    // 表单提交
    form.on('submit(payOrderForm)',function(data, id){
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: 'order-pay',
        error: function(){
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(res){
          if(res.code == 200){
            layer.closeAll();
            workflow.reload();
          }else{
            layer.msg('操作失败，稍后再试');
          }
        }
      });
      return false;
    })

    window.showItems = function(id){
      table.render({
        elem: '#items',
        url: 'items?id='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'brand', title: '名称'},
          {field: 'number', title: '数量'},
          {field: 'files', title: '图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'type', title: '型号'},
          {field: 'size', title: '尺寸'},
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
          {field: 'origin_price', title: '产品单价(欧元)'},
          {field: 'disc_price', title: '采购折扣价 (欧元)'}
        ]]
      });
      // 价格编辑
      table.on('edit(items)', function(obj){
        // 取到修改的价格字段值
        var value = obj.value;
        // 取到被修改的产品数据id
        var data = obj.data;
        var itemId = data.id;

        // 改动完即同步数据库
        $.ajax({
          type: 'POST',
          // 同步接口，传数据ID和修改后的金额值
          url: '/items?id=' + itemId + '&price=' + value,
          success: function(){
            layer.msg('报价已保存');
            table.reload('items',{}); // 重载数据表格
          },
          error: function(){
            layer.msg('系统错误');
          }
        })
      })
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

  });
  //
  exports('watingquote', {});
});
