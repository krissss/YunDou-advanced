<?php
/** @var $info \common\models\Info */

use yii\helpers\Url;

?>
<div class="modal fade info_modal" tabindex="-1" role="dialog" aria-labelledby="报名信息">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">报名信息</h4>
            </div>
            <div class="padding-10">
                <table class="table table-hover table-responsive table-striped">
                    <tbody>
                    <tr>
                        <td>身份证</td>
                        <td><?=$info->IDCard?></td>
                    </tr>
                    <tr>
                        <td>姓名</td>
                        <td><?=$info->realName?></td>
                    </tr>
                    <tr>
                        <td>密码</td>
                        <td><?=$info->password?></td>
                    </tr>
                    <tr>
                        <td>手机号</td>
                        <td><?=$info->cellphone?></td>
                    </tr>
                    <tr>
                        <td>学历</td>
                        <td><?=$info->education?></td>
                    </tr>
                    <tr>
                        <td>所学专业</td>
                        <td><?=$info->major?></td>
                    </tr>
                    <tr>
                        <td>参加工作时间</td>
                        <td><?=$info->workTime?></td>
                    </tr>
                    <tr>
                        <td>技术职称</td>
                        <td><?=$info->technical?></td>
                    </tr>
                    <tr>
                        <td>报考专业岗位</td>
                        <td><?=$info->signUpMajor?></td>
                    </tr>
                    <tr>
                        <td>工作单位</td>
                        <td><?=$info->company?></td>
                    </tr>
                    <tr>
                        <td>密码找回问题</td>
                        <td><?=$info->findPasswordQuestion?></td>
                    </tr>
                    <tr>
                        <td>问题答案</td>
                        <td><?=$info->findPasswordAnswer?></td>
                    </tr>
                    <tr>
                        <td>个人照片</td>
                        <td>
                            <img src="../../frontend/web/<?=$info->headImg?>" alt="" height="50">
                            <a class="btn btn-default pull-right" href="<?=Url::to(['sign-up/download','file'=>$info->headImg])?>" target="_blank">下载</a>
                        </td>
                    </tr>
                    <tr>
                        <td>身份证正面</td>
                        <td>
                            <img src="../../frontend/web/<?=$info->IDCardImg1?>" alt="" height="50">
                            <a class="btn btn-default pull-right" href="<?=Url::to(['sign-up/download','file'=>$info->IDCardImg1])?>" target="_blank">下载</a>
                        </td>
                    </tr>
                    <tr>
                        <td>身份证反面</td>
                        <td>
                            <img src="../../frontend/web/<?=$info->IDCardImg2?>" alt="" height="50">
                            <a class="btn btn-default pull-right" href="<?=Url::to(['sign-up/download','file'=>$info->IDCardImg2])?>" target="_blank">下载</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    <button class="btn btn-primary sign-up-ok" data-id="<?=$info->infoId?>">填报完成</button>
                    <div class="pull-right">
                        问题说明：<input type="text" name="replyContent" class="reply_content_<?=$info->infoId?>">
                        <button class="btn btn-warning sign-up-error" data-id="<?=$info->infoId?>">填报有问题</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


