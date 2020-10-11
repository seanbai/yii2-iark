<?php

$this->title = '管理员个人信息';

?>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">Reset Password</div>
                <div class="layui-card-body">
                    <!-- form -->
                    <form class="layui-form layui-form-pane" lay-filter="">
                        <!-- form item -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">当前密码</label>
                            <div class="layui-input-block">
                                <!-- 隐藏当前用户ID -->
                                <input type="hidden" name="id" id="id" value="<?php echo $uid ?>">
                                <input type="password" id="old" name="old" lay-verify="required" autocomplete="off" placeholder="当前密码" class="layui-input">
                            </div>
                        </div>
                        <!-- form item -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">新密码</label>
                            <div class="layui-input-block">
                                <input type="password" id="new" name="new" lay-verify="required" autocomplete="off" placeholder="新密码" class="layui-input">
                            </div>
                        </div>
                        <!-- form item -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">确认新密码</label>
                            <div class="layui-input-block">
                                <input type="password" id="new2" name="new2" lay-verify="required" autocomplete="off" placeholder="确认新密码" class="layui-input">
                            </div>
                        </div>
                        <!-- form item -->
                        <div class="layui-form-item">
                            <button type="submit" class="layui-btn" lay-submit="" lay-filter="setmypass">保存</button>
                        </div>
                    </form>
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
    }).use(['index', 'setmypass']);
</script>

</body>
</html>

