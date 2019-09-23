<?php
// 定义标题和面包屑信息
$this->title = 'My Order';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var user = <?=\yii\helpers\Json::encode($user); ?>

    var m = meTables({
        title: "My Order",
        bCheckbox: false,
        buttons: {
            "updateAll": {bShow: false},
            "create": {
                bShow: true,
            }
            ,
            "deleteAll": {bShow: false},
            "export":{bShow: false}
        },
        operations: {
            buttons: {
                "update": {"bShow": false},
                "delete": {"bShow": false}
            }
        },
        table: {
            "aoColumns": [
                {"title": "id", "data": "id", "sName": "id", "bSortable": false},
                {"title": "单据编号", "data": "order_number", "sName": "order_number","edit": {"type": "text"},   "bSortable": false},
                {"title": "状态", "data": "status", "sName": "status", "bSortable": false},
                {"title": "进度", "data": "status", "sName": "status", "bSortable": false},
                {"title": "期望交付时间", "data": "delivery_time", "edit": {"type": "text"}, "sName": "delivery_time", "bSortable": false, "createdCell" : meTables.dateTimeString},
                {"title": "支付方式", "data": "payment_method",  "edit": {"type": "text"}, "sName": "payment_method", "bSortable": false},
                {
                    "title": "制单人",
                    "data": "order_user",
                    "sName":  "order_user",
                    "edit": {"type": "select"},
                    "search": {"type": "select","id":"order_user"},
                    "bSortable": false,
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(user, [], data, ' '));
                    }

                },
                {"title": "创建时间", "data": "create_time", "sName": "create_time",  "isHide": true, "bSortable": false, "createdCell" : meTables.dateTimeString},
            ]       
        },
        sModal: "#add-modal",
        sFormId: "#edit-form-sean",
        editFormParams: {				// 编辑表单配置
            bMultiCols: false,          // 是否多列
            iColsLength: 3,             // 几列
            aCols: [3, 9],              // label 和 input 栅格化设置
            sModalClass: "",			// 弹出模块框配置
            sModalDialogClass: "sean-from"		// 弹出模块的class
        },
    });

    $(function(){
         m.init();


    });
</script>


<div id="from" class="add-order-from ">
    <div class="from none">
        <input name="id" value="1">
        <input name="name" value="1">
        <input name="user" value="1">
        <input name="from" value="1">
    </div>
</div>


<?php $this->endBlock(); ?>