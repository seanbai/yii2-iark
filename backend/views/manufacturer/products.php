<div class="layui-fluid" style="padding: 15px !important;">
    <table class="layui-table" lay-even="" lay-skin="row">
        <colgroup>
            <col width="60">
            <col width="100">
            <col width="100">
            <col width="60">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>Item</th>
            <th>品牌</th>
            <th>类型</th>
            <th>数量</th>
            <th width="80">价格</th>
            <th>描述</th>
            <th width="100">附件</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $key => $value) { ?>
            <tr>
                <td><?php echo $key+1 ?></td>
                <td><?php echo $value['brand'] ?></td>
                <td><?php echo $value['type'] ?></td>
                <td><?php echo $value['number'] ?></td>
                <?php if($value['price']):?>
                    <td><?php echo $value['price'] ?></td>
                <?php else:?>
                    <td><!--suppress HtmlFormInputWithoutLabel -->
                        <input placeholder="输入产品总价" type="text" name="price[<?= $value['id']?>]" value="">
                    </td>
                <?php endif;?>
                <td><?php echo $value['desc'] ?></td>
                <td><img width="20" src="<?php echo $value['files'] ?>" class="layui-btn layui-btn-xs layui-btn-warm"/></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>