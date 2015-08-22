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
                                    <li class="username"><a><?= $userSession['nickname'] ?></a></li>
                                    <li class="email"><a><?= $userSession['email'] ?></a></li>
                                    <li>
                                        <div class="avatar-area">
                                            <img src="<?= Yii::$app->params['headPath'] ?><?= $userSession['userIcon'] ?>" class="avatar">
                                            <span id="change-avatar" class="caption">更改头像</span>
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
                    <i class="searchicon"></i>
                    <div class="searchhelper"></div>
                </div>
                <!-- Sidebar Menu -->
                <ul class="nav sidebar-menu">
                    <li>
                        <a href="#" class="menu-dropdown">
                            <i class="menu-icon fa fa-book"></i>
                                <span class="menu-text">
                                    基础数据
                                </span>
                            <i class="menu-expand"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= Url::to(['basic-data/area']) ?>">
                                    <span class="menu-text">区域类型</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['basic-data/major']) ?>">
                                    <span class="menu-text">岗位专业</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['basic-data/test-type']) ?>">
                                    <span class="menu-text">试题类型</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['basic-data/test-chapter']) ?>">
                                    <span class="menu-text">试题章节</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['basic-data/usage-mode']) ?>">
                                    <span class="menu-text">消费方式</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="menu-dropdown">
                            <i class="menu-icon fa fa-users"></i>
                                <span class="menu-text">
                                    用户管理
                                </span>
                            <i class="menu-expand"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= Url::to(['users/index']) ?>">
                                    <span class="menu-text">微信用户管理</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['users/other']) ?>">
                                    <span class="menu-text">其他用户管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li <?//= $page_id == 'true-exam' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['test-library/index']) ?>">
                            <i class="menu-icon fa fa-list-alt"></i>
                            <span class="menu-text"> 题库管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'true-exam' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['exam-template/index']) ?>">
                            <i class="menu-icon fa fa-list-ol"></i>
                            <span class="menu-text"> 模板管理 </span>
                        </a>
                    </li>
                    <li <?//= $page_id == 'simulate-exam' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['simulate-exam/index']) ?>">
                            <i class="menu-icon fa fa-folder"></i>
                            <span class="menu-text"> 课件管理 </span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="menu-dropdown">
                            <i class="menu-icon fa fa-leaf"></i>
                                <span class="menu-text">
                                    云豆管理
                                </span>
                            <i class="menu-expand"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= Url::to(['income-consume/index']) ?>">
                                    <span class="menu-text">云豆列表</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['recharge/index']) ?>">
                                    <span class="menu-text">充值设置</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['practice-price/index']) ?>">
                                    <span class="menu-text">价格管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="menu-dropdown">
                            <i class="menu-icon fa fa-money"></i>
                                <span class="menu-text">
                                    资金管理
                                </span>
                            <i class="menu-expand"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= Url::to(['pay/index']) ?>">
                                    <span class="menu-text">现金收支表</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['pay/get-money']) ?>">
                                    <span class="menu-text">云豆提现记录</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="menu-dropdown">
                            <i class="menu-icon fa fa-ticket"></i>
                                <span class="menu-text">
                                    发票管理
                                </span>
                            <i class="menu-expand"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="<?= Url::to(['invoice/index']) ?>">
                                    <span class="menu-text">发票列表</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['invoice/apply']) ?>">
                                    <span class="menu-text">发票申请</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['invoice/opener']) ?>">
                                    <span class="menu-text">发票开具</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li <?//= $page_id == 'account-index' ? 'class="active"' : ''; ?>>
                        <a href="<?= Url::to(['service/index']) ?>">
                            <i class="menu-icon fa fa-comment-o"></i>
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
