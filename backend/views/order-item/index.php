<?php
// 定义标题和面包屑信息
$this->title = 'Purchaser Management';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var user = <?=\yii\helpers\Json::encode($user); ?>;

    var m = meTables({
        title: "Purchaser Management",
        bCheckbox: false,
        buttons: {
            "updateAll": {bShow: false},
            "create": {bShow: false},
            "deleteAll": {bShow: false},
            "export":{bShow: false}
        },
        operations: {
            buttons: {
                "update": {"bShow": true},
                "delete": {"bShow": false},
                "img-view": {
                    "bShow": true,
                    "title": "查看附件",
                    "button-title": "查看附件",
                    "className": "btn-info",
                    "cClass": "img-view",
                    "icon": "fa-search-plus",
                    "sClass": "green"
                }
            }
        },
        table: {
            "aoColumns": [
                {"title": "id", "data": "id", "sName": "id","edit": {"type": "hidden"}, "bSortable": false},
                {"title": "品牌", "data": "brand", "sName": "brand", "bSortable": false},
                {"title": "数量", "data": "number", "sName": "number", "bSortable": false},
                {"title": "类型", "data": "type", "sName": "type", "bSortable": false},
                {"title": "描述", "data": "desc", "sName": "desc", "bSortable": false},
                {
                    "title": "供货商",
                    "data": "pricing_id",
                    "sName": "pricing_id",
                    "value": user,
                    "edit": {"type": "select"},
                    "search": {"type": "select"},
                    "bSortable": false,
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(user, [], data, ' '));
                    }
                },
                {"title": "价格", "data": "price", "sName": "price",  "edit": {"type": "text"}, "bSortable": false},
                {
                    "title": "附件路径",
                    "isHide":true,
                    "data": "files",
                    "sName": "files",
                    "bSortable": false,
                },
                {"title": "创建时间", "data": "create_time", "sName": "create_time",  "isHide": false, "bSortable": false, },
            ]       
        },
    });

    $(function(){
         m.init();

        $(document).on('click', '.img-view', function () {
            var img_data = m.table.data()[$(this).attr('table-data')];
            if (!img_data) return null;
            window.open(img_data.files);
        });

        function printJsonString(obj, prefix=''){
            var html = '[<br />';
            for (i in obj) {
                html = html + prefix + "&emsp;&emsp;" + i + " => ";
                if(typeof obj[i] == 'object'){
                    html += printJsonString(obj[i], prefix + '&emsp;&emsp;');
                }else{
                    html = html + obj[i] + "<br/>";
                }
            }
            html = html + prefix + ']<br />';
            return html;
        }

        //日期插件
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#search-input-updated_at'
                ,type: 'datetime'
                ,range: true
                ,format: 'yyyy-MM-dd HH:mm:ss'
                ,done: function(value, date, endDate){
                    $('#search-updated_at').val(value);
                    $('#search-updated_at').blur();
                }
            });
        });
    });
</script>




<?php $this->endBlock(); ?>