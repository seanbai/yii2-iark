<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <link rel="stylesheet" href="/public/admin/ui/css/layui.css">
    <link rel="stylesheet" href="/public/admin/css/custom.css">
</head>
<body class="dark">

<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm12">

            <div class="layui-card">
                <div class="layui-card-header">
                    Pending Order
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="myOrder" lay-filter="myOrder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="items">Items List</button>
                            <button class="layui-btn layui-btn-sm" lay-event="status">Change Order Status</button>
                        </div>
                    </script>
                    <!-- row action -->
                    <script type="text/html" id="action">
                        <a class="layui-btn layui-btn-xs" lay-event="file-uploads">附件上传</a>
                    </script>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'mpendingorder']);
</script>
</body>
</html>

<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
    {{#  if(d.status == 81){ }}
    <span class="tag layui-bg-red">Wating For Confirm Deposit</span>
    {{# }else if(d.status == 91){ }}
    <span class="tag layui-bg-cyan">In Production</span>
    {{# }else { }}
    <span class="tag layui-bg-red">Wating For Confirm Balance</span>
    {{# } }}
</script>

<div class="layui-fluid" id="showItems" style="display:none">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm12">
            <table id="items" lay-filter="items"></table>
            <!-- -->
            <script type="text/html" id="status">
                <input type="checkbox" name="Complete" value="{{d.id}}" title="Complete" lay-filter="Complete" {{ d.id == 10006 ? 'checked' : '' }}>
            </script>
        </div>
    </div>
</div>
