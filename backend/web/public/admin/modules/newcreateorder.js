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
        // id 前台排序，方便调整顺序后打印，不用同步到数据库
        {field: 'id', title: '排序', edit: 'text', width:80, sort: true},
        {field: 'brand', title: '名称'},
        {field: 'qty', title: '数量'},
        {field: 'image', title: '样式图片',
          templet: function(d){
            return '<div onclick="showImg(this)"><img src="'+d.image+'"></div>';
          }
        },
        {field: 'supplier_name', title: '供应商'},
        {field: 'model', title: '型号'},
        {field: 'size', title: '图纸尺寸'},
        {field: 'material', title: '材质'},
        {field: 'att', title: '附件',
          templet: function(d){
            var att = d.att;
            if(!att){
              return '';
            }else{
              return '<div><a href="'+d.att+'">'+d.att+'</a></div>';
            }
          }
        },
        {field: 'desc', title: '备注'},
        {fixed: 'right', title:'操作', toolbar: '#delete', width:140}
      ]],
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

    // 排序单元格可编辑
    items.on('edit(items)', function(obj){
      var value = obj.value,
          data = obj.data,
          field = obj.field;
      layer.msg('[ID: '+ data.id +'] ' + field + ' 排序更改为：'+ value);
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
            resize: false,
            success: function(){
              $('#addItems')[0].reset();
            }
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
             layer.msg('系统错误,请稍后重试.',{icon:6});
          },
          success: function(res){ // 保存成功处理
            if(res.errCode == 0){
              layer.msg('删除成功');
              tableIns.reload();// 表格重载
            }else{
              layer.msg(res.errMsg,{icon:6});
            }
          }
        })
      }else{
        $('#addItems')[0].reset();
        // 编辑事件
        layer.open({
          type: 1,
          title: '修改产品信息',
          area: ['90%', 'auto'],
          content: $('#addItem'),
          resize: false,
          success: function(){
            // 弹层打开成功将行数据赋值给表单
            form.val("edit",data);
            //$('#username').attr("disabled","disabled").addClass('layui-disabled');
          }
        });
      }
    });

    // 图片上传事件
    upload.render({
      elem: '#image-up',
      url: '/uploads/upload?',
      data: {
        "_csrf": $('meta[name=csrf-token]').attr('content')
      },
      choose: function(obj){
        // 上传时加载 Loading
        layer.load();
        // 上传按钮旁显示 - 直接前端方法
        obj.preview(function(index, file, result){
          $('#demo1').attr('src', result); //图片链接（base64）
        });
      },
      done: function(res, index, upload){
        layer.closeAll('loading');
         if (res.code == 200) {
           //将图片添加到input
           $('#image').attr('value',res.data);
         } else {
           layer.msg('上传失败');
         }
      }
    });

    // 附件上传事件
    upload.render({
      elem: '#attachment',
      url: '/uploads/upload?',
      // 只允许压缩包格式
      accept: 'file',
      exts: 'zip|rar',
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
          $('#att').attr('value',res.data);
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
          layer.msg('系统错误,请稍后重试.',{
            icon: 2,
            time: 2000
          });
        },
        success: function(res){
           if(res.errCode == 1001){
             //验证信息异常
             layer.msg(res.errMsg,{
               icon: 2,
               time: 2000
             });
             $('#addItems')[0].reset();
           }else{
             // 成功提示
             layer.msg('保存成功!您可以继续添加产品.',{
               icon: 6,
               time: 2000
             });
             // 保留弹层同时清空表单缓存
             $('#addItems')[0].reset();
             //layer.closeAll();
             // 表格重载
             tableIns.reload();
           }
        }
      });
      return false;
    })
  });
  //
  exports('newcreateorder', {});
});
