<?php
/** @var $message_type string */
/** @var $message string */
?>
<?php if($message_type == 'success'):?>
    <div class="alert alert-success fade in">
        <i class="fa-fw fa fa-check"></i>
        <strong><?=$message?></strong>
    </div>
<?php elseif($message_type == 'error'): ?>
    <div class="alert alert-danger fade in">
        <i class="fa-fw fa fa-times"></i>
        <strong><?=$message?>!</strong>
    </div>
<?php elseif($message_type == 'warning'): ?>
    <div class="alert alert-warning fade in">
        <i class="fa-fw fa fa-warning"></i>
        <strong><?=$message?></strong>
    </div>
<?php elseif($message_type == 'info'): ?>
    <div class="alert alert-info fade in">
        <i class="fa-fw fa fa-info"></i>
        <strong><?=$message?></strong>
    </div>
<?php else: ?>
<h1>消息类型未定义</h1>
<?php endif; ?>
