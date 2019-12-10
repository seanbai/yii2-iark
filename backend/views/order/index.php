<?php
// 定义标题和面包屑信息
$this->title = 'My Order';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var user = <?=\yii\helpers\Json::encode($user); ?>,
        order_status = <?=\yii\helpers\Json::encode($status); ?>,
        pay = <?=\yii\helpers\Json::encode($pay); ?>;


    var m = meTables({
        title: "My Order",
        bCheckbox: false,
        buttons: {
            "updateAll": {bShow: false},
            "create": {bShow: false},
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
                {
                    "title": "状态",
                    "data": "order_status",
                    "sName": "order_status",
                    "bSortable": false,
                    "edit": {"type": "text"},
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(order_status, [], data, ' '));
                    }
                },
                {"title": "期望交付时间", "data": "date", "edit": {"type": "text"}, "sName": "delivery_time", "bSortable": false},
                {
                    "title": "支付方式",
                    "data": "currency",
                    "edit": {"type": "text"},
                    "sName": "currency",
                    "bSortable": false,
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(pay, [], data, ' '));
                    }
                },
                {
                    "title": "申请人",
                    "data": "user",
                    "sName":  "user",
                    "edit": {"type": "text"},
                    "bSortable": false,
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(user, [], data, ' '));
                    }
                },
                {"title": "创建时间", "data": "create_time", "sName": "create_time",  "isHide": false, "bSortable": false, },
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




<?php $this->endBlock(); ?>