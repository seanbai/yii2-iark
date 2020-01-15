<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    Manufacturer Management
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="manufacturer" lay-filter="manufacturer"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="create">添加供货商账户</button>
                        </div>
                    </script>
                    <!-- 行编辑 -->
                    <script type="text/html" id="editTool">
                        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                        <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="disable">停用</a>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/html" id="userStatus">
    {{#  if(d.status == 0){ }}
    <span class="tag layui-bg-cyan">正常</span>
    {{# }else { }}
    <span class="tag layui-bg-red">已停用</span>
    {{# } }}
</script>


<div id="editForm" style="display:none;padding: 20px;">
    <div class="layui-fluid">
        <form class="layui-form layui-form-pane" id="edit" lay-filter='edit'>
            <!-- 供货商名称 -->
            <div class="layui-form-item">
                <label class="layui-form-label">供货商名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入供货商名称" class="layui-input">
                </div>
            </div>
            <!-- 登录用户名 -->
            <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="username" id="username" lay-verify="required" autocomplete="off" placeholder="登录用户名，密码默认123456" class="layui-input">
                    <input type="hidden" name="password" value="123456">
                    <input type="hidden" name="repassword"  value="123456">
                    <input type="hidden" name="id" value="">
                </div>
            </div>
            <!-- 报价 -->
            <div class="layui-form-item">
                <label class="layui-form-label">签约时间</label>
                <div class="layui-input-block">
                    <input type="text" name="time" id="time" lay-verify="date" autocomplete="off" placeholder="请输入供货商名称" class="layui-input" id="date">
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
                    <input type="number" name="phone" lay-verify="required" autocomplete="off" placeholder="联系人电话" class="layui-input">
                </div>
            </div>
            <!-- 开启生产状态 -->
            <div class="layui-form-item">
                <label class="layui-form-label">电子邮件</label>
                <div class="layui-input-block">
                    <input type="email" name="email" lay-verify="email" autocomplete="off" placeholder="联系人邮箱" class="layui-input">
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

            <input type="hidden" name="role" value="manufacturer">

            <!-- 提交表单 -->
            <div class="layui-form-item">
                <button id="layui-btn" class="layui-btn" lay-submit="" lay-filter="create">保存</button>
            </div>
        </form>
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