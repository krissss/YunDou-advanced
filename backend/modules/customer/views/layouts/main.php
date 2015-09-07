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
    <?//360使用急速模式?>
    <meta name="renderer" content="webkit">
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
                    <a href="<?= Url::to(['/site/index']) ?>" class="navbar-brand">
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
                        <div class="user-info">
                            <p>
                                <i class="fa fa-user"></i><?= $userSession['nickname'] ?> |
                                <a href="<?= Url::to(['/site/logout']) ?>"><i class="fa fa-sign-out"></i>退出登录</a>
                            </p>
                        </div>
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
                    <i class="searchicon"></i>
                    <div class="searchhelper"></div>
                </div>
                <!-- Sidebar Menu -->
                <ul class="nav sidebar-menu">
                    <li>
                        <a href="<?= Url::to(['default/index']) ?>">
                            <i class="menu-icon fa fa-info"></i>
                            <span class="menu-text"> 我的信息 </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['user/index']) ?>">
                            <i class="menu-icon fa fa-list-alt"></i>
                            <span class="menu-text"> 关联用户管理 </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['bitcoin/index']) ?>">
                            <i class="menu-icon fa fa-leaf"></i>
                            <span class="menu-text"> 云豆收支 </span>
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
    <?php endif;//空的容器去除所有body以下样式结束 ?>
    <footer class="footer">
        <div class="container">
            <p class="text-align-center">版权所有：南京云宝网络有限公司@2015 | <a href="http://www.miitbeian.gov.cn">苏ICP备15038143-2号</a></p>
        </div>
    </footer>
    <?php if ($page_id != $str_emptyContainer)://空的容器去除所有body以下样式  ?>
    <?php endif;//空的容器去除所有body以下样式结束 ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
