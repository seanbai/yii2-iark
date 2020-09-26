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
        {field: 'deposit_file', title: '定金支付凭证',
          templet: function(d){
            if(!d.deposit_file){
              return  '';
            }
            return '<div onclick="showImg(this)"><img src="'+d.deposit_file+'"></div>'
          }
        },
        {field: 'balance_file', title: '尾款支付凭证',
          templet: function(d){
            if(!d.balance_file){
              return  '';
            }
            return '<div onclick="showImg(this)"><img src="'+d.balance_file+'"></div>'
          }
        },
        {field: 'date', title: '期望交付时间'},
        {field: 'package', title: '包装要求'},
        {field: 'owner', title: '采购商'},
        {fixed: 'right', title:'操作', toolbar: '#action', width:200}
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
            layer.msg("您需要先选择一条数据",{icon:0});
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
        case 'receiveNotice':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据", {icon:0});
          }else{
            var id = data[0].id;
            var project = data[0].project_name;
            var total = data[0].desc_quote;
            var status = (data[0].order_status);
            var tax = data[0].tax ? (data[0].tax) : 0;
            var fuwu = data[0].fuwu ? (data[0].fuwu) : 0;
            total = total ? (total) : 0;
            layer.open({
              type: 1,
              title: '项目名称 - ' + project,
              area: ['50%', '60%'],
              content: $('#receiveNotice'),
              resize: false,
              success: function () {
                $('#noticeId').val(id);
                $('#notice-total').val(total);
                var depositEle = $('input[name="deposit_notice"]'),
                    balanceEle = $('input[name="balance_notice"]'),
                    taxEle     = $('input[name="tax_notice"]'),
                    fuwuEle     = $('input[name="fuwu_notice"]');
                if(data[0].deposit_notice == 1){
                  depositEle.attr({"checked": true, "disabled":true});
                }
                if(data[0].balance_notice == 1){
                  balanceEle.attr({"checked": true, "disabled":true});
                }
                if(data[0].tax_notice == 1){
                  taxEle.attr({"checked": true, "disabled":true});
                }
                if(data[0].fuwu_notice == 1){
                  fuwuEle.attr({"checked": true, "disabled":true});
                }
                // if(status === 6){//等待收取定金
                //   depositEle.attr({"checked": true, "disabled":true});
                //   balanceEle.attr({"checked": false, "disabled":false});
                //   taxEle.attr({"checked": false, "disabled":true});
                // }
                // if(status === 11){//等待收取尾款
                //   depositEle.attr({"checked": true, "disabled":true});
                //   balanceEle.attr({"checked": true, "disabled":true});
                //   taxEle.attr({"checked": false, "disabled":false});
                // }
                // if(status === 14){//等待收取税金
                //   depositEle.attr({"checked": true, "disabled":true});
                //   balanceEle.attr({"checked": true, "disabled":true});
                //   taxEle.attr({"checked": true, "disabled":true});
                //   $('.layui-layer-btn .layui-layer-btn0').hide();
                // }

                $('#aux-balance').html('应收尾款'+ (total/2));
                $('#aux-tax').html('应收税金'+ (tax));
                $('#aux-fuwu').html('应收服务费'+ (fuwu));
                $('#aux-deposit').html('应收定金'+ (total/2));
                form.render();
              }
            });
          }
          break;
        case 'support':
          if(checkStatus.data.length === 0) {
            layer.msg("您需要先选择一条数据", {icon:0});
          } else {
            var id = data[0].id;
            var project = data[0].project_name;
            var supprot_notice = $("#supprot_notice").is(":checked");
            if (supprot_notice) {
              $('#serviceAmount').show();
            } else {
              $('#serviceAmount').hide();
            }
            console.log(supprot_notice);
            layer.open({
              type: 1,
              title: '项目名称 - ' + project,
              area: ['50%', '60%'],
              content: $('#support'),
              resize: false,
              success : function(){
                $('#supprotId').val(id);
              }
            });
          }
          break;
        case 'goodslist':
          if(checkStatus.data.length === 0) {
            layer.msg("您需要先选择一条数据", {icon:0});
          } else {
            var id = data[0].id;
            console.log(id);

            layer.open({
              type: 1,
              title: '项目名称 - ' + "",
              area: ['90%', '70%'],
              content: $('#showItems'),
              btn: ['提交'],
              success: showGoodslist(id)
            });
          }
          break;
      }
    });
    // 判断报价方式的开关状态
    form.on('switch(supprot)', function (obj) {
      if (obj.elem.checked === false) {
        $('#serviceAmount').hide();
      } else {
        $('#serviceAmount').show();
      }
    });

    // 表单提交
    form.on('submit(submit-support)',function(data,id){


      layer.prompt({
        formType: 2,
        title: '请先为此订单添加留言'
      },function(value,index){
        layer.close(index);
        $.ajax({
          type: 'POST',
          // url: '/api/feedback?orderId=' + id + '&content=' + value,
          url: '/message/save?orderId='+ orderId +'&type='+ 6 +'&content='+value,
          error: function(){ // 保存错误处理
            layer.msg('留言失败,请稍后重试.',{
              icon: 5,
              time: 1000
            }, function(){
              layer.confirm('确定收到订单金额吗?', function(index){
                $.ajax({
                  type: 'post',
                  dataType: 'json',
                  data: data.field,
                  url: 'create-supprot',
                  error: function(){
                    layer.msg('系统错误,请稍后重试.');
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        order.reload();
                        $('#supportForm')[0].reset();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如是果不配置，默认3秒）
                      }, function () {
                        layer.close(itemsbox);
                      });
                    }
                  }
                });
              });
            })
          },
          success: function(){ // 保存成功处理
            layer.confirm('确定收到订单金额吗?', function(index){
              $.ajax({
                type: 'post',
                dataType: 'json',
                data: data.field,
                url: 'create-supprot',
                error: function(){
                  layer.msg('系统错误,请稍后重试.');
                },
                success: function(response){
                  if(response.errCode == 0){
                    layer.msg(response.errMsg, {
                      icon: 1,
                      time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function () {
                      layer.closeAll();
                      order.reload();
                      $('#supportForm')[0].reset();
                    });
                  }else{
                    layer.msg(response.errMsg, {
                      icon: 5,
                      time: 2000 //2秒关闭（如是果不配置，默认3秒）
                    }, function () {
                      layer.close(itemsbox);
                    });
                  }
                }
              });
            });
          },
        });
      });
      return false;
    });

    // 表单提交
    form.on('submit(receiveNotice)',function(data,id){
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: 'receive-notice',
        error: function(){
          layer.msg('系统错误,请稍后重试.');
        },
        success: function(res){
          if(res.errCode == 0){
            layer.closeAll();
            order.reload();
          }else{
            layer.msg(res.errMsg);
          }
        }
      });
      return false;
    });

    // 收款确认按钮
    table.on('tool(quote)', function(obj){
      var data = obj.data;
      var id = data.id;
      var total = data.desc_quote;
      var status = data.order_status;
      var tax = data.tax;
      var fuwu = data.fuwu !== undefined || data.fuwu ? data.fuwu : 0;
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
            var receive_deposit =  data.receive_deposit ? (data.receive_deposit) : 0;
            var receive_balance = data.receive_balance ? (data.receive_balance) : 0;
            var receive_tax = data.receive_tax ? (data.receive_tax) : 0;
            var receive_fuwu = (data.receive_fuwu !== undefined || data.receive_fuwu) ? (data.receive_fuwu) : 0;
            var form = layer.open({
              type: 1,
              title: '收款确认',
              area: ['640px', 'auto'],
              content: $('#confirmPayment'),
              success: function(layero, index){
                $('#orderId').val(id);
                $('#orderStatus').val(7);
                $('#total').val(total);
                $('#deposit').val(total/2);
                $('#balance').val(total/2).attr('disabled',true);
                $('#fuwu').val(fuwu);
                if(receive_tax){
                  $('#receive_tax').val(receive_tax).attr('disabled',true);
                }
                if(receive_fuwu){
                  $('#receive_fuwu').val(receive_fuwu).attr('disabled',true);
                }
                if(receive_deposit){
                  $('#receive_deposit').val(receive_deposit).attr('disabled',true);
                }
                if(receive_balance){
                  $('#receive_balance').val(receive_balance).attr('disabled',true);
                }
                $('#tax').val(tax);
              }
            });
          }
          break;
        case 'confirmSupprot':
          var itemsbox = layer.open({
            type: 1,
            title: '服务费清单 ',
            area: ['60%', '50%'],
            content: $('#showItems'),
            btn: ['提交'],
            success: showSupprots(id),
            yes: function(){
              layer.confirm('请仔细核对收到的服务费金额', function(index){
                $.ajax({
                  type: 'POST',
                  url: 'update',
                  data:{
                  },
                  error: function(){
                    layer.msg('系统异常...',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.close(itemsbox);
                        workflow.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.close(itemsbox);
                      });
                    }
                  }
                });
              })
            }
          });
          break;
      }
    });

    // 显示产品清单方法
    window.showSupprots = function(id){
      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'supprot-list?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true,
        skin: 'row',
        even: true,
        cols: [[
          {field: 'id', title: '编号'},
          {field: 'charge_amount', title: '应收金额'},
          {field: 'confirm_amout', title: '实收金额', edit: 'text'},
          {field: 'created_at', title: '收取时间'},
          {field: 'desc', title: '备注', edit: 'text', width:300}
        ]]
      });
    }

    // 显示提货清单方法
    window.showGoodslist = function(id){
      var items = table.render({
        id: 'itemsList',
        elem: '#items',
        url: 'goods-list?id='+id, //数据接口
        toolbar: '#showItemsBar',
        totalRow: true,
        skin: 'row',
        even: true,
        cols: [[
          {field: 'name', title: '提货编号'},
          {field: 'status_name', title: '状态'},
          {field: 'wait_tax_amount', title: '应收服务费', edit: 'text'},
          {field: 'confirm_tax_amount', title: '实收服务费', edit: 'text'},
          {field: 'desc', title: '备注', edit: 'text', width: 300},
          {fixed: 'right', title: '操作', toolbar: '#taxAction', width: 220}
        ]]
      });

      //价格编辑
      table.on('edit(items)', function(obj){
        var value = obj.value;  //修改后的金额
        var field = obj.field;   //修改的字段
        var status = obj.data.status;
        var itemId = obj.data.id;
        var desc = obj.data.desc;
          // 改动完即同步数据库
        $.ajax({
          type: 'POST',
          //同步接口，传数据ID和修改后的金额值
          url: 'update-tax-service?id=' + itemId + '&price=' + value + '&field=' + field + '&status=' + status + '$desc=' + desc,
          success: function(response){
            if(response.errCode == 0){
              layer.msg('操作成功', {icon: 6});
              table.reload('items',{}); // 重载数据表格
            }else {
              layer.msg(response.errMsg,{icon: 5});
              table.reload('items',{}); // 重载数据表格
            }
          },
          error: function(){
            layer.msg('系统异常，请联系管理人员');
          }
        })
      });

      //按钮提交
      table.on('tool(items)', function(obj){
        var data = obj.data;
        var id = data.id;
        var status = data.status;
        var confirm_tax_amount = data.confirm_tax_amount;
        var wait_support_amount = data.wait_support_amount;
        var confirm_supprot_amount = data.confirm_supprot_amount;
        switch(obj.event){
          case 'pleaseTax':
            layer.confirm('请仔细核对税金收取金额', function (index) {
              if (status != 0) {
                layer.msg("当前状态不能发起税金收取操作");
              } else {
                $.ajax({
                  type: 'POST',
                  url: 'update-tax',
                  data: {
                    id : id,
                    status: 1
                  },
                  error: function(){
                    layer.msg('系统异常,请联系管理人员',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        items.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      });
                    }
                  }
                });
              }
            })
            break;
          case 'confirmTax':
            layer.confirm('请仔细核对税金收取金额,一旦确认则无法修改', function (index) {
              if (status != 2) {
                layer.msg("当前状态不能执行确认税金操作");
              } else {
                $.ajax({
                  type: 'POST',
                  url: 'update-tax',
                  data: {
                    id : id,
                    status: 3,
                    price: confirm_tax_amount
                  },
                  error: function(){
                    layer.msg('系统异常,请联系管理人员',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        items.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      });
                    }
                  }
                });
              }
            })
            break;
          case 'pleaseSupprot':
            layer.confirm('请仔细核对服务费收取金额，一旦发起申请则无法修改', function (index) {
              if (status != 3) {
                layer.msg("当前状态不能发起服务费收取操作");
              } else {
                $.ajax({
                  type: 'POST',
                  url: 'update-tax',
                  data: {
                    id : id,
                    status: 4,
                    price: wait_support_amount
                  },
                  error: function(){
                    layer.msg('系统异常,请联系管理人员',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        items.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      });
                    }
                  }
                });
              }
            })
            break;
          case 'confirmSupprot':
            layer.confirm('请仔细核对服务费收取金额，一旦确认则无法修改', function (index) {
              if (status != 5) {
                layer.msg("当前状态不能执行确认服务费操作");
              } else {
                $.ajax({
                  type: 'POST',
                  url: 'update-tax',
                  data: {
                    id : id,
                    status: 6,
                    price: confirm_supprot_amount
                  },
                  error: function(){
                    layer.msg('系统异常,请联系管理人员',{icon:5});
                  },
                  success: function(response){
                    if(response.errCode == 0){
                      layer.msg(response.errMsg, {
                        icon: 1,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      }, function () {
                        layer.closeAll();
                        items.reload();
                      });
                    }else{
                      layer.msg(response.errMsg, {
                        icon: 5,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                      });
                    }
                  }
                });
              }
            })
            break;
        }
      })
    }

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
                return '<div><a href="'+d.att+'">'+d.att+'</a></div>'
              }
            }
          },
          {field: 'supplier_name', title: '供应商'},
          {field: 'desc', title: '备注'},
          {field: 'origin_price', title: '单价(欧元)',
            totalRow: true},
          {field: 'price', title: '供货折扣价(欧元)',
            totalRow: true},
          {field: 'disc_price', title: '采购折扣价(欧元)',
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
        content: '<div style="text-align:center"><img width="500" height="600" src="' + $(t).attr('src') + '" /></div>'
      });
    }
    // 表单提交
    form.on('submit(confirmPayment)',function(data,id){
      var orderId = $('#orderId').val();
      var orderStatus = $('#orderStatus').val();
      var orderS = 5;
      if (orderStatus == 7) { orderS = 7; }

      layer.prompt({
        formType: 2,
        title: '请先为此订单添加留言'
      },function(value,index){
        layer.close(index);
        $.ajax({
          type: 'POST',
          // url: '/api/feedback?orderId=' + id + '&content=' + value,
          url: '/message/save?orderId='+ orderId +'&type='+ orderS +'&content='+value,
          error: function(){ // 保存错误处理
            layer.msg('留言失败,请稍后重试.',{
              icon: 5,
              time: 1000
            }, function(){
              layer.confirm('确认是否收到尾款?', function(index){
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
              });
            })
          },
          success: function(){ // 保存成功处理
            layer.confirm('确认是否收到尾款?', function(index){
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
            });
          },
        });
      });
      return false;
    })
  });
  //
  exports('receive', {});
});
