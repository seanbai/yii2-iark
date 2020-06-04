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
            <th>序号</th>
            <th>品牌</th>
            <th>数量</th>
            <th width="100">样式图片</th>
            <th>型号</th>
            <th>产品尺寸</th>
            <th>材质</th>
            <th width="100">附件</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $key => $value) { ?>
            <tr>
                <td><?php echo $key ?></td>
                <td><?php echo $value['brand'] ?></td>
                <td><?php echo $value['number'] ?></td>
                <td><img width="75" height="16" src="<?php echo $value['files'] ?>" class="layui-btn layui-btn-xs layui-btn-warm"/></td>
                <td><?php echo $value['type'] ?></td>
                <td><?php echo $value['size'] ?></td>
                <td><?php echo $value['material'] ?></td>
                <td><a href="<?php echo $value['att']?>"><?php echo $value['att']?></a></td>
                <td><?php echo $value['desc'] ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>