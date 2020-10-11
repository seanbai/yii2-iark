<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?= Yii::$app->name . Html::encode($this->title) ?></title>
    <meta name="description" content="3 styles with inline editable feature"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head(); ?>
    <!-- ace styles -->
    <link rel="stylesheet" href="/public/assets/css/ace.min.css" id="main-ace-style"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ace-part2.min.css"/>
    <![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ace-ie.min.css"/>
    <![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="/public/assets/js/ace-extra.min.js"></script>
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
    <script src="/public/assets/js/html5shiv.min.js"></script>
    <script src="/public/assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            overflow: hidden
        }

        .me-breadcrumb {
            display: block;
            max-width: 100%;
            max-height: 41px;
            overflow: hidden
        }

        .me-breadcrumb div {
            color: #585858;
            float: left;
            height: 100%;
            display: inline-block;
            padding-left: 15px;
            padding-right: 15px;
            border-right: 1px solid #e2e2e2
        }

        .me-breadcrumb div.me-window {
            padding: 0;
            border-right: none
        }

        .me-breadcrumb div.me-window div {
            padding-right: 8px
        }

        .me-breadcrumb div.me-window a {
            margin-left: 5px
        }

        .me-breadcrumb div.active, .me-breadcrumb div.options:hover {
            color: #428bca;
            font-weight: 700;
            background-color: #fff
        }

        .me-breadcrumb div a {
            color: red
        }

        .me-breadcrumb div.options a {
            color: #428bca;
            font-size: 14px
        }

        .me-breadcrumb div span {
            cursor: pointer
        }

        #nav-search span a#window-refresh {
            font-size: 20px
        }

        .iframe {
            -webkit-transition: all .3s ease-out 0s;
            transition: all .3s ease-out 0s
        }

        .breadcrumbs-fixed + .page-content {
            padding-top: 41px
        }

        #page-content {
            overflow-y: hidden;
            padding-right: 0;
            padding-bottom: 0;
            padding-left: 0
        }
    </style>
    <link rel="stylesheet" href="/public/admin/ui/css/layui.css">
    <link rel="stylesheet" href="/public/admin/css/custom.css" id="main-ace-style"/>
    <link rel="stylesheet" href="/public/admin/css/ace-home.css" />
</head>

<body class="ace-skin layui-layout-body dark">


<?php $this->beginBody() ?>

<div class="layui-layout layui-layout-admin">
    <div id="navbar-container" class="layui-header">
        <div class="navbar-header-logo pull-left">
            <a href="/">
                <span>Order System</span>
            </a>
        </div>

        <div class="navbar-header pull-right">
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <a href="javascript:;">My Account</a>
                    <dl class="layui-nav-child">
                        <dd><a href="/admin/reset" target="iframe-index">Reset Password</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <?= Html::beginForm(['/site/logout'], 'post'); ?>
                    <?= Html::submitButton(
                        ' Sign Out ',
                        ['class' => 'btn btn-link logout sign-out']
                    ) ?>
                    <?= Html::endForm(); ?>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container main-container-fixed" id="main-container">

    <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar responsive sidebar-fixed">
        <!--左侧导航栏信息-->
        <?php
        try {
            echo \backend\widgets\Nav::widget([
                'options' => [
                    'id' => 'nav-list-main',
                    'class' => 'nav nav-list',
                ],
                'labelName' => 'menu_name',
                'items' => $this->params['menus'],
                'itemsName' => 'child'
            ]);
        } catch (\Exception $e) {

        }
        ?>

        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'collapsed')
            } catch (e) {
            }
        </script>
    </div>



    <!--主要内容信息-->
    <div class="main-content">
        <!--头部可固定导航信息-->
        <div class="page-content" id="page-content">
            <iframe class="active iframe" name="iframe-index" id="iframe-index" width="100%" height="100%"
                    src="<?= Url::toRoute(['site/system']) ?>" frameborder="0"></iframe>
        </div>
    </div>
</div>
<!-- 公共的JS文件 -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/public/assets/js/jquery.min.js'>" + "<" + "/script>");
</script>
<!-- <![endif]-->
<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/public/assets/js/jquery1x.min.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='/public/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>
<script src="/public/assets/js/bootstrap.min.js"></script>
<!--[if lte IE 8]>
<script src="/public/assets/js/excanvas.min.js"></script>

<![endif]-->
<?php $this->endBody() ?>

<script type="text/javascript">
    authHeight();
    var $windowDiv = $("#me-window"),
        $divContent = $("#page-content"),
        intSize = <?=Yii::$app->params['iframeNumberSize']?>;

    function authHeight() {
        $("#page-content").css("height", $(window).height() - $("#page-content").offset()["top"] - $(".footer").innerHeight() + "px")
    }

    function addDiv(strId, strTitle) {
        $windowDiv.find("div.active").removeClass("active");
        if ($windowDiv.find("div:not(div.hide)").size() >= intSize) {
            $windowDiv.find("div:not(div.hide):first").addClass("hide");
            $("#window-prev").removeClass("hide");
        }
        var html = '<div class="me-div active" data-id="' + strId + '"><span>' + strTitle + '</span><a href="javascript:;" class="me-window-close"><i class="ace-icon fa fa-times me-i-close"></i></a></div>';
        $windowDiv.append(html);
    }

    function addIframe(strId, strUrl, strTitle) {
        strId = "iframe-" + strId;
        $divContent.find("iframe.active").removeClass("active").addClass("hide");
        $windowDiv.find("div.active").removeClass("active");
        if ($divContent.find("#" + strId).size() > 0) {
            $divContent.find("#" + strId).addClass("active").removeClass("hide");
            $windowDiv.find("div[data-id=" + strId + "]").addClass("active");
        } else {
            var strIframe = '<iframe id="' + strId + '" name="' + strId + '" ' + 'width="100%" class="active iframe" height="100%" src="' + strUrl + '" frameborder="0"></iframe>';
            addDiv(strId, $.trim(strTitle));
            $("#page-content").append(strIframe);
        }
    }

    $(function () {
        $(window).resize(function () {
            authHeight()
        });
        $("#window-refresh").click(function (evt) {
            evt.preventDefault();
            var objActive = $("#page-content iframe.active").get(0);
            if (objActive) {
                objActive.contentWindow.location.reload()
            }
        });
        $(document).on("click", "#me-window span", function () {
            $("#me-window").find("div.active").removeClass("active");
            $("#page-content").find("iframe.active").removeClass("active").addClass("hide");
            $("#" + $(this).parent().addClass("active").attr("data-id")).removeClass("hide").addClass("active")
        });
        $(document).on("click", "a.me-window-close", function (evt) {
            evt.preventDefault();
            var $parent = $(this).parent("div"),
                isHasActive = $parent.hasClass("active"),
                $next = $windowDiv.find("div:not(div.hide):last").next("div");
            if ($next.size() > 0) {
                $next.removeClass("hide");
                if (isHasActive) {
                    $divContent.find("#" + $next.addClass("active").attr("data-id")).removeClass("hide").addClass("active")
                }
            } else {
                $windowDiv.find("div:not(div.hide):first").prev("div").removeClass("hide");
                if (isHasActive || $windowDiv.find("div.active").size() <= 0) {
                    $divContent.find("#" + $parent.prev("div").addClass("active").removeClass("hide").attr("data-id")).removeClass("hide").addClass("active")
                }
            }

            $parent.remove();
            $("#" + $parent.attr("data-id")).remove();
            var intShowDiv = $windowDiv.find("div:not(div.hide)").size();
            if ($windowDiv.find("div:not(div.hide):last").next("div").size() <= 0 || intShowDiv < intSize) {
                $("#window-next").addClass("hide")
            }

            if ($windowDiv.find("div:not(div.hide):first").prev("div").size() <= 0 || intShowDiv < intSize) {
                $("#window-prev").addClass("hide")
            }
        });
        $("#nav-list-main").find("a").click(function (e) {
            e.preventDefault();
            if ($(this).attr("href") != "#") {
                addIframe($(this).attr("data-id"), $(this).prop("href"), $(this).text());
                var $parent = $(this).closest("li").parent();
                if ($parent.hasClass("nav-list")) {
                    $parent.children("li").removeClass("active");
                    $parent.find("li.hsub ul.submenu").hide().removeClass("open active").find("li").removeClass("active")
                } else if ($parent.hasClass("submenu")) {
                    $parent.find("li.active").removeClass("active");
                    $parent.parent("li").siblings("li").removeClass("active")
                }
                $(this).closest("li").addClass("active")
            }
        });
        $("#window-prev").click(function () {
            if ($windowDiv.find("div:not(div.hide):first").prev("div").size() > 0) {
                $windowDiv.find("div:not(div.hide):first").prev("div").removeClass("hide");
                $windowDiv.find("div:not(div.hide):last").addClass("hide");
                $("#window-next").removeClass("hide");
                if ($windowDiv.find("div:not(div.hide):first").prev("div").size() <= 0) {
                    $(this).addClass("hide")
                }
            } else {
                if ($windowDiv.find("div.hide").size() > 0) {
                    $("#window-next").removeClass("hide")
                }
            }
        });

        $("#window-next").click(function () {
            if ($windowDiv.find("div:not(div.hide):last").next("div").size() >= 1) {
                $windowDiv.find("div:not(div.hide):last").next("div").removeClass("hide");
                $windowDiv.find("div:not(div.hide):first").addClass("hide");
                $("#window-prev").removeClass("hide");
                if ($windowDiv.find("div:not(div.hide):last").next("div").size() <= 0) {
                    $(this).addClass("hide")
                }
            } else {
                if ($windowDiv.find("div.hide").size() > 0) {
                    $("#window-prev").removeClass("hide")
                }
            }
        });

        $(".window-iframe").click(function (e) {
            e.preventDefault();
            if ($(this).attr("data-id")) {
                addIframe($(this).attr("data-id"), $(this).attr("data-url"), $(this).attr("title"))
            }
        })
    });
</script>

<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/'
    }).extend({
        index: 'lib/index'
    }).use('index');
</script>
</body>
</html>
<?php $this->endPage() ?>

