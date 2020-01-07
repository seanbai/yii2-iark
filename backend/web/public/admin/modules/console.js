/**
  console page modules
**/
layui.define(function(exports){

  // 基于准备好的dom，初始化echarts实例
  var myChart = echarts.init(document.getElementById('main'));

  // 指定图表的配置项和数据
  var option = {
      tooltip: {},
      xAxis: {
          data: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Otc","Nov","Dec"],
          axisLine:{
            show: false,
            lineStyle:{
              color: '#adb5bd'
            }
          }
      },
      yAxis: {
        type: 'value',
        axisLabel: {
          formatter: '{value}K'
        },
        axisLine:{
          show: false,
          lineStyle:{
            color: '#adb5bd'
          }
        },
        splitLine:{
          lineStyle:{
            color: '#eee'
          }
        }
      },
      series: [{
          name: '销量',
          type: 'bar',
          barWidth: 20,
          data: [5, 20, 36, 10, 10, 20, 14, 24, 23, 80, 55, 32],
          itemStyle: {
            normal: {
              color: '#727CF5'
            }
          }
      }]
  };

  // 使用刚指定的配置项和数据显示图表。
  myChart.setOption(option);

  //
  layui.use(['table','element'], function(){
    var table = layui.table;
    var element = layui.element;
    // pending order
    table.render({
      elem: '#pending-order',
      //url: '../../admin/json/myorder.json', //数据接口
      cellMinWidth: 100,
      skin: 'row',
      limit: 10,
      even: true,
      cols: [[ //表头
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'ordernum', title: '单据编号'},
        {field: 'status', title: '状态',width:80},
        {field: 'pubdata', title: '创建时间',sort: true},
        {field: 'finishdata', title: '期望交付时间', sort: true},
        {field: 'owner', title: '制单人'}
      ]],
      data : [{
        "id" : 1,
        "pubdata" : "20190710",
        "finishdata" : "20191010",
        "ordernum" : "KHDHD2019070100001",
        "status" : 1,
        "owner" : "Daniel Chen",
        "telphone" : "18628077530",
        "company" : "Home Design",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "银行转账",
        "process": 20
      },{
        "id" : 2,
        "pubdata" : "20190711",
        "finishdata" : "20191010",
        "ordernum" : "KHDHD2019070100001",
        "owner" : "Daniel Chen",
        "telphone" : "18628077530",
        "company" : "Home Design",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "支付宝",
        "process": 40
      },{
        "id" : 3,
        "pubdata" : "20190711",
        "finishdata" : "20191010",
        "ordernum" : "KHDHD2019070100001",
        "owner" : "Daniel Chen",
        "telphone" : "18628077530",
        "company" : "Home Design",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "微信支付",
        "process": 10
      },{
        "id" : 4,
        "pubdata" : "20190711",
        "finishdata" : "20191010",
        "ordernum" : "KHDHD2019070100001",
        "owner" : "Daniel Chen",
        "telphone" : "18628077530",
        "company" : "Home Design",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "微信支付",
        "process": 30
      },{
        "id" : 5,
        "pubdata" : "20190711",
        "finishdata" : "20191010",
        "ordernum" : "KHDHD2019070100001",
        "owner" : "Daniel Chen",
        "telphone" : "18628077530",
        "company" : "Home Design",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "微信支付",
        "process": 80
      }],
      done: function(res, curr, count){
        element.render();
      }
    });
    // completed order
    table.render({
      elem: '#completed',
      //url: '../../admin/json/completed.json', //数据接口
      cellMinWidth: 100,
      skin: 'row',
      even: true,
      cols: [[ //表头
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'ordernum', title: '单据编号'},
        {field: 'pubdata', title: '创建时间',sort: true},
        {field: 'truedata', title: '实际交付时间',sort: true},
        {field: 'fullprice', title: '合计', sort: true},
        {field: 'address', title: '收货地址'}
      ]],

      data : [{
        "id" : 1,
        "pubdata" : "2019-07-10",
        "finishdata" : "2019-10-10",
        "truedata" : "2019-10-08",
        "ordernum" : "KHDHD2019070100001",
        "preprice" : "10,000",
        "endprice" : "90,000",
        "tax" : "18,000",
        "fullprice" : "11,8000",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "银行转账"
      },{
        "id" : 2,
        "pubdata" : "2019-07-10",
        "finishdata" : "2019-10-10",
        "truedata" : "2019-10-08",
        "ordernum" : "KHDHD2019070100001",
        "preprice" : "10,000",
        "endprice" : "90,000",
        "tax" : "18,000",
        "fullprice" : "10,0000",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "银行转账"
      },{
        "id" : 3,
        "pubdata" : "2019-07-10",
        "finishdata" : "2019-10-10",
        "truedata" : "2019-10-08",
        "ordernum" : "KHDHD2019070100001",
        "preprice" : "10,000",
        "endprice" : "90,000",
        "tax" : "18,000",
        "fullprice" : "10,0000",
        "address" : "四川省成都市高新区软件园B区",
        "statement" : "银行转账"
      }],
      done: function(res, curr, count){
        element.render();
      }
    });

  });


  exports('console', {});
})
