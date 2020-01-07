<div class="layui-fluid">
    <form class="layui-form layui-form-pane" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">采购商名称*</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入采购商名称…" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">联系人*</label>
            <div class="layui-input-block">
                <input type="text" name="contact" lay-verify="required" autocomplete="off" placeholder="请输入采购商名称…" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话*</label>
            <div class="layui-input-block">
                <input type="text" name="tel" lay-verify="required" autocomplete="off" placeholder="联系电话用于订单及时沟通" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">用户名*</label>
            <div class="layui-input-block">
                <input type="text" name="username" lay-verify="required" autocomplete="off" placeholder="采购商登录系统时的用户名" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">登录密码*</label>
            <div class="layui-input-block">
                <input type="text" name="password" lay-verify="required" autocomplete="off" placeholder="登录密码" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="email" lay-verify="email" autocomplete="off" placeholder="联系人邮箱" class="layui-input">
            </div>
        </div>
        <!--  -->
        <div class="layui-form-item">
            <label class="layui-form-label">通讯地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" autocomplete="off" placeholder="请输入采购商名称…" class="layui-input">
            </div>
        </div>
        <!-- 提交表单 -->
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="create">确认创建新账户</button>
        </div>
    </form>
</div>


<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'purchaser']);
</script>