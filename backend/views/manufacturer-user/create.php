
<style>
    body{
        background-color: #FFFFFF;
    }
</style>

<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md12">

            <form class="layui-form layui-form-pane" action="" method="post">
                <!-- 报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">供货商名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入供货商名称" class="layui-input">
                    </div>
                </div>
                <!-- 报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">签约时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="date" lay-verify="date" autocomplete="off" placeholder="请输入供货商名称" class="layui-input" id="date">
                    </div>
                </div>
                <!-- 定金 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">联系人</label>
                    <div class="layui-input-block">
                        <input type="text" name="contact" lay-verify="required" autocomplete="off" placeholder="供货商联系人" class="layui-input">
                    </div>
                </div>
                <!-- 定金确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-block">
                        <input type="number" name="tel" lay-verify="required" autocomplete="off" placeholder="联系人电话" class="layui-input">
                    </div>
                </div>
                <!-- 开启生产状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">电子邮件</label>
                    <div class="layui-input-block">
                        <input type="email" name="mail" lay-verify="email" autocomplete="off" placeholder="联系人邮箱" class="layui-input">
                    </div>
                </div>
                <!-- 尾款申请 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">所在城市</label>
                    <div class="layui-input-block">
                        <input type="text" name="city" lay-verify="required" autocomplete="off" placeholder="供货商所在城市" class="layui-input">
                    </div>
                </div>
                <!-- 允许发货 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">详细地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="address" lay-verify="required" autocomplete="off" placeholder="详细地址" class="layui-input">
                    </div>
                </div>
                <!-- 订单完成 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">登录用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" lay-verify="required" autocomplete="off" placeholder="供货商登录系统时的用户名" class="layui-input">
                    </div>
                </div>
                <!-- 订单完成 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">登录密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="登录密码" class="layui-input">
                    </div>
                </div>
                <!-- 提交表单 -->
                <div class="layui-form-item">
                    <button class="layui-btn" lay-submit="" lay-filter="create">确认创建新账户</button>
                </div>
            </form>

        </div>
    </div>
</div>


<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'manufacturer']);
</script>