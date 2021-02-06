layui.define(function(exports){
  //
  layui.use(['table','jquery','form', 'upload'], function(){
    var table = layui.table;
    var $ = layui.jquery;
    var form = layui.form,
        upload = layui.upload;
    //
    var workflow = table.render({
      elem: '#myOrder',
      height: 'full-115',
      toolbar: '#orderBar',
      url: 'delivery-list', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type: 'radio'},
        {field: 'name', title: '提货编号'},
        {field: 'product_ids', title: '提货的产品ids'},
        {field: 'created_at', title: '提货时间'},
        {field: 'transport', title: '运输信息'},
        {field: 'port_time', title: '预计到港时间'},
        {field: 'file', title: '服务费附件'},
        {field: 'image', title: '运输发票',templet: function(d){
            if(d.image) { return '<div onclick="showImg(this)"><img src="'+d.image+'"></div>' }
            return  '';
          }}
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
          $('#deposit-img-tmp2').attr('src', result).bind('click', function () {
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
          $("#service-fee-file").val(res.data);
        }
        //上传成功
      },
      error: function(){
        //演示失败状态，并实现重传
        var demoText = $('#demoText');
        demoText.html('<span style="color: #ff5722;">上传失败</span> <a class="layui-btn layui-btn-xs upload-reload">重试</a>');
        demoText.find('.upload-reload').on('click', function(){
          uploadInst.upload();
        });
      }
    });

    // 表格工具
    table.on('toolbar(myOrder)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        // 查看产品清单
          case 'items':
            if(checkStatus.data.length === 0){
              layer.msg("请选择一条订单数据");
            }else{
              // 取订单ID 和 项目名称
              var data = checkStatus.data;
              var id = data[0].product_ids;

              var itemsbox = layer.open({
                type: 1,
                title: '本次提货商品列表',
                area: ['90%', '80%'],
                content: $('#showItems'),
                success: showItems(id),
              })
            }
          break;
        case 'add':
          if(checkStatus.data.length === 0){
            layer.msg("请选择一条订单数据");
          }else{
            $('#addFrom')[0].reset();
            // 取订单ID 和 项目名称
            var data = checkStatus.data;
            var id = data[0].id;

            $("#orderId").val(id);
            var itemsbox = layer.open({
              type: 1,
              title: '填写物流信息',
              area: ['90%', '80%'],
              content: $('#payOrderForm'),
              success: function(layero, index){
              }
            })
          }
          break;
      }
    });

    // 表单提交
    form.on('submit(payOrderForm)',function(data, id){
      var deliveryId =  $("#orderId").val();
      layer.prompt({
        formType: 2,
        title: '请先为此订单添加留言'
      },function(value,index){
        layer.close(index);
        $.ajax({
          type: 'POST',
          // url: '/api/feedback?orderId=' + id + '&content=' + value,
          url: '/message/save?type='+ 200 +'&delivery='+ deliveryId +'&content='+value,
          error: function(){ // 保存错误处理
            layer.msg('留言失败,请稍后重试.',{
              icon: 5,
              time: 1000
            }, function(){
              layer.confirm('确认信息无误后保存?', function(index){
                $.ajax({
                  type: 'post',
                  dataType: 'json',
                  data: data.field,
                  url: 'transport-update',
                  error: function(){
                    layer.msg('系统错误,请稍后重试.',{
                      icon: 5,
                      time: 1000
                    });
                  },
                  success: function(res){
                    if(res.code == 0){
                      layer.msg('保存运输信息成功',{
                        icon: 0,
                        time: 1000
                      });
                      layer.closeAll();
                      workflow.reload();
                    }else{
                      layer.msg(res.errMsg);
                    }
                  }
                });
              });
            })
          },
          success: function(){ // 保存成功处理
            layer.confirm('确认信息无误后保存?', function(index){
              $.ajax({
                type: 'post',
                dataType: 'json',
                data: data.field,
                url: 'transport-update',
                error: function(){
                  layer.msg('系统错误,请稍后重试.',{
                    icon: 5,
                    time: 1000
                  });
                },
                success: function(res){
                  if(res.code == 0){
                    layer.msg('保存运输信息成功',{
                      icon: 0,
                      time: 1000
                    });
                    layer.closeAll();
                    workflow.reload();
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

    window.showItems = function(id){
      table.render({
        elem: '#items',
        url: 'delivery-items?ids='+id, //数据接口
        toolbar: '#itemsBar',
        skin: 'row',
        even: true,
        cols: [[
          {field: 'project_name', title: '项目名称'},
          {field: 'brand', title: '产品名称'},
          {field: 'number', title: '数量'},
          {field: 'files', title: '图片',
            templet: function(d){
              return '<div onclick="showImg(this)"><img src="'+d.files+'"></div>'
            }
          },
          {field: 'type', title: '型号'},
          {field: 'size', title: '尺寸'},
          {field: 'material', title: '材质'},
        ]]
      });
    }

    // 附件上传事件
    upload.render({
      elem: '#attachment',
      url: '/uploads/uploads?',
      // 只允许压缩包格式
      accept: 'file',
      exts: 'zip|rar|7z',
      choose: function(obj){
        // 上传时加载 Loading
        layer.load();
      },
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      done: function(res){
        // 上传完成后关闭 Loading
        layer.closeAll('loading');
        if (res.code == 200) {
          //将图片添加到input
          $('#att').val(res.data);
          $('#fileName').html(res.data)
        } else {
          layer.msg('上传失败');
        }
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
  });
  //
  exports('delivery', {});
});
