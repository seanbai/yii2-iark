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
            <th>类型</th>
            <th>数量</th>
            <th>描述</th>
            <th width="100">附件</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $key => $value) { ?>
            <tr>
                <td><?php echo $key ?></td>
                <td><?php echo $value['brand'] ?></td>
                <td><?php echo $value['type'] ?></td>
                <td><?php echo $value['number'] ?></td>
                <td><?php echo $value['desc'] ?></td>
                <td><a href="<?php echo $value['files'] ?>" class="layui-btn layui-btn-xs layui-btn-warm">附件</a></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>