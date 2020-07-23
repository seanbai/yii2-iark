<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    Purchaser Management
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="purchaser" lay-filter="purchaser"></table>
                    <!-- tool bar -->
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="add">添加采购商账户</button>
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


<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'purchaser']);
</script>


<!-- 数据表格状态格式化 -->
<script type="text/html" id="userStatus">
    {{#  if(d.status == 0){ }}
    <span class="tag layui-bg-cyan">正常</span>
    {{# }else { }}
    <span class="tag layui-bg-red">已停用</span>
    {{# } }}
</script>

<div style="display:none; padding: 20px;" id="addForm">
    <div class="layui-fluid">
        <form class="layui-form layui-form-pane" id="addNew" lay-filter="addNew">
            <div class="layui-form-item">
                <label class="layui-form-label">采购商名称*</label>
                <div class="layui-input-block">
                    <input type="text" name="name" id="name" lay-verify="required" autocomplete="off" placeholder="请输入采购商名称…" class="layui-input">
                </div>
            </div>
            <!-- 用户名 和 默认密码123456 -->
            <div class="layui-form-item">
                <label class="layui-form-label">用户名*</label>
                <div class="layui-input-block">
                    <input type="text" name="username" id="username" lay-verify="required" autocomplete="off" placeholder="登录用户名，密码默认123456" class="layui-input">
                    <!-- 隐藏密码，让初始创建默认密码123456 -->
                    <input type="hidden" name="password" id="password" value="123456">
                    <input type="hidden" name="repassword" id="repassword" value="123456">
                    <!-- 隐藏id，用于编辑账户信息时传值 -->
                    <input type="hidden" name="role" value="buyer">
                    <input type="hidden" name="id" id="id" value="">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">设计师*</label>
                <div class="layui-input-block">
                    <select name="designer" lay-verify="required">
                        <option value="0" selected="">请选择设计师</option>
                        <option value="46">Dylan</option>
                        <option value="58">Lalay</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">联系人*</label>
                <div class="layui-input-block">
                    <input type="text" name="contact" id="contact" lay-verify="required" autocomplete="off" placeholder="请输入采购商名称…" class="layui-input">
                </div>
            </div>
            <!--  -->
            <div class="layui-form-item">
                <label class="layui-form-label">联系电话*</label>
                <div class="layui-input-block">
                    <input type="text" name="phone" id="phone" lay-verify="required" autocomplete="off" placeholder="联系电话用于订单及时沟通" class="layui-input">
                </div>
            </div>
            <!--  -->
            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-block">
                    <input type="text" name="email" id="email" lay-verify="email" autocomplete="off" placeholder="联系人邮箱" class="layui-input">
                </div>
            </div>
            <!--  -->
            <div class="layui-form-item">
                <label class="layui-form-label">通讯地址</label>
                <div class="layui-input-block">
                    <input type="text" name="address" id="address" autocomplete="off" placeholder="联系地址…" class="layui-input">
                </div>
            </div>
            <!-- 提交表单 -->
            <div class="layui-form-item">
                <button id="layui-btn" class="layui-btn" lay-submit="" lay-filter="create">保存</button>
            </div>
        </form>
    </div>
</div>