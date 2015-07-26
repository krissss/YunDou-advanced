<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

//$userSession = \common\models\Users::findOne(1);
$userSession = Yii::$app->session->get('user');
$imagePath = Yii::$app->params['imagePath'];

$str_emptyContainer = 'empty-container';
$page_id = isset($this->params['breadcrumbs']['id']) ? $this->params['breadcrumbs']['id'] : '';

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="description" content="云宝云豆在线学习平台"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= $imagePath ?>favicon.png" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?php if ($page_id != $str_emptyContainer)://空的容器去除所有body以下样式  ?>
    <!-- loading-container -->
    <div class="loading-container">
        <div class="loading-progress">
            <div class="rotator">
                <div class="rotator">
                    <div class="rotator colored">
                        <div class="rotator">
                            <div class="rotator colored">
                                <div class="rotator colored"></div>
                                <div class="rotator"></div>
                            </div>
                            <div class="rotator colored"></div>
                        </div>
                        <div class="rotator"></div>
                    </div>
                    <div class="rotator"></div>
                </div>
                <div class="rotator"></div>
            </div>
            <div class="rotator"></div>
        </div>
    </div>
    <!-- /loading-container  -->
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <!-- navbar-container -->
            <div class="navbar-container">
                <!-- logo -->
                <div class="navbar-header pull-left">
                    <a href="<?= Url::to(['site/index']) ?>" class="navbar-brand">
                        <small>
                            <img src="<?= $imagePath ?>logo.png" alt=""/>
                        </small>
                    </a>
                </div>
                <!-- /logo -->
                <!-- 菜单缩放 -->
                <div class="sidebar-collapse" id="sidebar-collapse">
                    <i class="collapse-icon fa fa-bars"></i>
                </div>
                <!-- /菜单缩放 -->
                <!-- 用户 -->
                <div class="navbar-header pull-right">
                    <div class="navbar-account">
                        <ul class="account-area">
                            <li>
                                <a class="login-area dropdown-toggle" data-toggle="dropdown">
                                    <div class="avatar" title="查看信息">
                                        <img src="<?= Yii::$app->params['headPath'] ?><?= $userSession['userIcon'] ?>">
                                    </div>
                                    <section>
                                        <h2>
                                            <span class="profile"><?= $userSession['username'] ?></span>
                                        </h2>
                                    </section>
                                </a>
                                <!--下拉-->
                                <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                    <li class="username"><a><?= $userSession['username'] ?></a></li>
                                    <li class="email"><a><?= $userSession['email'] ?></a></li>
                                    <li>
                                        <div class="avatar-area">
                                            <img src="<?= Yii::$app->params['headPath'] ?><?= $userSession['userIcon'] ?>" class="avatar">
                                            <span id="change-avatar" class="caption" data-href="<?=Url::to(['account/index','#'=>'avatar'])?>">更改头像</span>
                                        </div>
                                    </li>
                                    <li class="edit">
                                        <a href="<?= Url::to(['account/index']) ?>" class="pull-left">个人中心</a>
                                        <a href="javascript:void(0);" class="pull-right">设置</a>
                                    </li>
                                    <li class="dropdown-footer">
                                        <a href="<?= Url::to(['site/logout']) ?>">
                                            退出登录
                                        </a>
                                    </li>
                                </ul>
                                <!--/下拉-->
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /用户 -->
            </div>
            <!-- /navbar-container -->
        </div>
    </div>
    <!-- /Navbar -->
    <!-- Main Container -->
    <div class="main-container">
        <!-- Page Container -->
        <div class="page-container">
            <!-- Page Sidebar -->
            <div class="page-sidebar" id="sidebar">
                <div class="sidebar-header-wrapper">
                    <input type="text" class="searchinput"/>
                    <i class="searchicon fa fa-search"></i>
                    <div class="searchhelper">搜索</div>
                </div>
                <!-- Sidebar Menu -->
                <ul class="nav sidebar-menu">
                    <li <?//= $page_id == 'index' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['site/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-home"></i>
                            <span class="menu-text"> 系统信息 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'index' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['site/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-home"></i>
                            <span class="menu-text"> 基础数据 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'single-test' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['single-test/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-pencil"></i>
                            <span class="menu-text"> 用户管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'true-exam' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['true-exam/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-list-alt"></i>
                            <span class="menu-text"> 题库管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'simulate-exam' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['simulate-exam/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-time"></i>
                            <span class="menu-text"> 课件管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'wrong-test' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['wrong-test/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-file"></i>
                            <span class="menu-text"> 云豆管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'history' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['history/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-calendar"></i>
                            <span class="menu-text"> 资金管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'analysis' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['analysis/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-list-alt"></i>
                            <span class="menu-text"> 发票管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'account-index' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['account/index']) ?>">
                            <i class="menu-icon glyphicon glyphicon-user"></i>
                            <span class="menu-text"> 咨询管理 </span>
                        </a>
                    </li>
                </ul>
                <!-- /Sidebar Menu -->
            </div>
            <!-- /Page Sidebar -->
            <!-- Page Content -->
            <div class="page-content">
                <!-- Page Header -->
                <div class="page-header position-relative">
                    <!--Header Buttons-->
                    <div class="header-buttons">
                        <a class="sidebar-toggler" href="javascript:void(0);">
                            <i class="fa fa-arrows-h"></i>
                        </a>
                        <a class="refresh" id="refresh-toggler" href="javascript:document.location.reload();">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                        <a class="fullscreen" id="fullscreen-toggler" href="javascript:void(0);">
                            <i class="glyphicon glyphicon-fullscreen"></i>
                        </a>
                    </div>
                    <!--Header Buttons End-->
                </div>
                <!-- /Page Header -->
                <!-- Page Body -->
                <div class="page-body">
                    <?php endif;//空的容器去除所有body以下样式结束 ?>
                    <?= $content ?>
                    <?php if ($page_id != $str_emptyContainer)://空的容器去除所有body以下样式  ?>
                </div>
                <!-- /Page Body -->
            </div>
            <!-- /Page Content -->
        </div>
        <!-- /Page Container -->
    </div>
    <!-- Main Container -->

    <!--
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?//= date('Y') ?></p>
            <p class="pull-right"><?//= Yii::powered() ?></p>
        </div>
    </footer>
    -->
    <?php endif;//空的容器去除所有body以下样式结束 ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
