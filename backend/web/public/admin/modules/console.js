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

    table.render({
      elem: '#my-order',
      url: '../../admin/json/myorder.json', //数据接口
      cellMinWidth: 100,
      skin: 'row',
      even: true,
      cols: [[ //表头
        {field: 'id', title: 'ID', width:80, sort: true},
        {field: 'ordernum', title: '单据编号'},
        {field: 'status', title: '状态',width:80},
        {field: 'pubdata', title: '创建时间',sort: true},
        {field: 'finishdata', title: '期望交付时间', sort: true},
        {field: 'owner', title: '制单人'}
      ]],
      done: function(res, curr, count){
        element.render();
      }
    });
  });


  exports('console', {});
})
