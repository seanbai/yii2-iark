layui.define(function(exports){
  //
  layui.use(['table','laydate','form','jquery'], function(){
    var table = layui.table;
    var laydate = layui.laydate;
    var form = layui.form;
    var $ = layui.jquery;

    laydate.render({
      elem: '#date'
    });
    //
    var tableIns = table.render({
      elem: '#manufacturer',
      height: 'full-115',
      toolbar: '#toolbar',
      //url: '../../admin/json/manufacturer.json', //数据接口
      where: {limit: 10},
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'brand', title: '供货商名称'},
        {field: 'boss', title: '联系人'},
        {field: 'phone', title: '联系电话'},
        {field: 'mail', title: '邮件'},
        {field: 'city', title: '城市'},
        {field: 'address', title: '地址'},
        {field: 'username', title: '用户名'},
        {field: 'contract', title: '签约时间'}
      ]],
      data : [{
        "id" : 1,
        "brand" : "AGRESTI",
        "boss" : "Robert",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "robert@agresti.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "agresti",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 2,
        "brand" : "ALGALA LUX",
        "boss" : "Daniel",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "daniel@algala-lux.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "algala",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 3,
        "brand" : "Arflex",
        "boss" : "Jessica",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "jessica@arflex.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "arflex",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 4,
        "brand" : "Angelo Cappellini",
        "boss" : "Perker",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "parker@angelo-cappellini.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "angelo",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 5,
        "brand" : "Arketipo",
        "boss" : "Simon",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "simon@arketipo.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "arketipo",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 6,
        "brand" : "Avenanti",
        "boss" : "Jean",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "jean@arketipo.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "avenanti",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 7,
        "brand" : "AGRESTI",
        "boss" : "Robert",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "robert@agresti.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "agresti",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 8,
        "brand" : "ALGALA LUX",
        "boss" : "Daniel",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "daniel@algala-lux.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "algala",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 9,
        "brand" : "Arflex",
        "boss" : "Jessica",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "jessica@arflex.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "arflex",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 10,
        "brand" : "Angelo Cappellini",
        "boss" : "Perker",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "parker@angelo-cappellini.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "angelo",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 11,
        "brand" : "Arketipo",
        "boss" : "Simon",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "simon@arketipo.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "arketipo",
        "password" : "123456",
        "contract": "2019-11-30"
      },{
        "id" : 12,
        "brand" : "Avenanti",
        "boss" : "Jean",
        "city" : "Italia - Rome",
        "phone" : "340-293-393",
        "mail" : "jean@arketipo.com",
        "address" : "2635 W 26th St, Erie, PA 16506",
        "username" : "avenanti",
        "password" : "123456",
        "contract": "2019-11-30"
      }]
    });
    //
    table.on('toolbar(manufacturer)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* del user */
        case 'create':
          layer.open({
            type: 2,
            title: 'Change Status',
            area: ['640px', '610px'],
            content: 'create.html',
            resize: false
          });
        break;
        /* reset password */
        case 'disabled':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('确认要停用此账户么？', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
      };
    });
    // 提交表单
    form.on('submit(create)', function(data){
      console.log(data.field);
      // ajax 提交表单
      $.ajax({
        type: 'post',
        dataType: 'json',
        data: data.field,
        url: "....",
        error: function(){ // 保存错误处理
          layer.msg('系统错误，请稍后重试');
        },
        success: function(){ // 保存成功处理
          // 成功提示
          layer.msg('已成功创建新的供应商账户');
          // 表格重载
          tableIns.reload({
            where: {limit: 10},
            page: {curr: 1}
          });

          // 关闭弹层
          // var index = parent.layer.getFrameIndex(window.name);
          // parent.layer.close(index);
        }
      });
      return false;
    })
  });
  //
  exports('manufacturer', {});
});
