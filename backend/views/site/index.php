<?php
/* @var $this yii\web\View */

$this->title = '系统信息';
?>
<table class="table table-hover table-bordered table-responsive">
    <thead>
        <tr>
            <th class="">服务器信息</th>
            <th class=""></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>服务器计算机名</td>
            <td><?=php_uname()?></td>
        </tr>
        <tr>
            <td>php版本</td>
            <td><?=PHP_VERSION?></td>
        </tr>
        <tr>
            <td>服务器IP地址</td>
            <td><?=GetHostByName($_SERVER['SERVER_NAME'])?></td>
        </tr>
        <tr>
            <td>服务器域名</td>
            <td><?=$_SERVER["HTTP_HOST"]?></td>
        </tr>
        <tr>
            <td>服务器端口</td>
            <td><?=$_SERVER['SERVER_PORT']?></td>
        </tr>
        <tr>
            <td>服务器当前时间</td>
            <td><?=date("Y-m-d H:i:s l");?></td>
        </tr>
        <tr>
            <td>浏览器信息</td>
            <td><?=$_SERVER['HTTP_USER_AGENT']?></td>
        </tr>
    </tbody>
</table>