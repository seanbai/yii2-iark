layui.define(function(exports){
  //
  layui.use(['table','jquery'], function(){
    var table = layui.table;
    var $ = layui.$;
    //
    table.render({
      elem: '#workflow',
      height: 'full-115',
      toolbar: '#toolbar',
      //url: '../../admin/json/workflow.json', //数据接口
      cellMinWidth: 100,
      page: true, //开启分页
      skin: 'line',
      skin: 'row',
      even: true,
      cols: [[ //表头
        {type:'radio'},
        {field: 'case', width: 200, title: 'Case Number',templet:'<div>GZL2018101800004</div>'},
        {field: 'date', title: 'Case Date',templet:'<div>16/07/2019</div>'},
        {field: 'status', title: 'Status',templet:'<div>Pending...</div>'},
        {field: 'link', width: 200, title: 'Link Order', templet:'<div></div>'},
        {field: 'orderdate', title: 'Order Date', templet:'<div>8</div>'},
        {field: 'offer', title: 'Offer', templet:'<div>8</div>'},
        {field: 'info', title: 'Information', templet:'<div>8</div>'},
        {field: 'price', title: 'Price', templet:'<div>100</div>'}
      ]],
      data: [{
        "id": 1,
        "num": "GZL2018101800004",
        "date": "16/07/2019",
        "status": "Pending...",
        "ignoreNum": 8,
        "order": {
          "date": "20/07/2019",
          "offer": 8,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 2,
        "num": "GZL2018101800005",
        "date": "16/07/2019",
        "status": "Lgnore",
        "order": {
          "date": "20/07/2019",
          "offer": 2,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 3,
        "num": "GZL2018101800006",
        "date": "16/07/2019",
        "status": "Quoted",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 4,
        "num": "GZL2018101800007",
        "date": "16/07/2019",
        "status": "Confirm Quote",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 5,
        "num": "GZL2018101800008",
        "date": "16/07/2019",
        "status": "In Production",
        "order": {
          "date": "20/07/2019",
          "offer": 2,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 6,
        "num": "GZL2018101800009",
        "date": "16/07/2019",
        "status": "Comfirm Balance Due",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 7,
        "num": "GZL2018101800010",
        "date": "16/07/2019",
        "status": "Pending...",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 8,
        "num": "GZL2018101800011",
        "date": "16/07/2019",
        "status": "Pending...",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 9,
        "num": "GZL2018101800012",
        "date": "16/07/2019",
        "status": "Pending...",
        "order": {
          "date": "20/07/2019",
          "offer": 1,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 10,
        "num": "GZL2018101800013",
        "date": "16/07/2019",
        "status": "Pending...",
        "order": {
          "date": "20/07/2019",
          "offer": 2,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      },{
        "id": 11,
        "num": "GZL2018101800014",
        "date": "16/07/2019",
        "status": "Pending...",
        "order": {
          "date": "20/07/2019",
          "offer": 3,
          "info": "please review my order"
        },
        "offer":{
          "date": "21/07/2019",
          "price": "€13,000",
          "rate": "7.85",
          "info": "",
          "confirm": 0,
          "cdate": ""
        },
        "done": 0
      }],
      done: function(res, curr, count){
        var vheight = $('#leftCard').height();
        $('#rightCard').height(vheight);
      }
    });
    //
    table.on('toolbar(workflow)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        /* del user */
        case 'del':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('Confirm?', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
        /* reset password */
        case 'reset':
          if(checkStatus.data.length === 0){
            layer.msg("您需要先选择一条数据");
          }else{
            layer.confirm('Confirm to reset user password?', function(index){
              obj.del();
              layer.close(index);
            });
          }
        break;
      };
    });
    //
  });
  //
  exports('workflow', {});
});
