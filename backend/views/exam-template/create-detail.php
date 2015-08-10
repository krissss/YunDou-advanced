<?php
/** @var $testChapters_1 \common\models\TestChapter[] */
/** @var $testChapters_2 \common\models\TestChapter[] */
/** @var $examTemplateDetails array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '模拟题管理';
$this->registerCssFile('css/yundou-exam-template.css',['depends'=>'backend\assets\AppAsset']);
?>
<div class="widget flat">
    <div class="widget-body">
        <?=Html::beginForm()?>
        <h3>专业基础</h3>
        <table class="table">
            <thead class="bordered-blue">
                <tr>
                    <td></td>
                    <td>单选</td>
                    <td>多选</td>
                    <td>判断</td>
                    <td>案例</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($testChapters_1 as $testChapter):?>
                <?php
                $value_danxuan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[1][1])?$examTemplateDetails[1][1][$testChapter->testChapterId]:'';
                $value_duoxuan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[1][2])?$examTemplateDetails[1][2][$testChapter->testChapterId]:'';
                $value_panduan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[1][3])?$examTemplateDetails[1][3][$testChapter->testChapterId]:'';
                $value_anli    = array_key_exists($testChapter->testChapterId,$examTemplateDetails[1][4])?$examTemplateDetails[1][4][$testChapter->testChapterId]:'';
                ?>
                <tr>
                    <td class="title"><?=$testChapter->name?></td>
                    <td><input class="input-sm" type="number" min="0" name="danxuan_<?=$testChapter->testChapterId?>" value="<?=$value_danxuan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="duoxuan_<?=$testChapter->testChapterId?>" value="<?=$value_duoxuan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="panduan_<?=$testChapter->testChapterId?>" value="<?=$value_panduan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="anli_<?=$testChapter->testChapterId?>" value="<?=$value_anli?>"></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <h3>管理实务</h3>
        <table class="table">
            <thead>
            <tr>
                <td></td>
                <td>单选</td>
                <td>多选</td>
                <td>判断</td>
                <td>案例</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($testChapters_2 as $testChapter):?>
                <?php
                $value_danxuan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[2][1])?$examTemplateDetails[2][1][$testChapter->testChapterId]:'';
                $value_duoxuan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[2][2])?$examTemplateDetails[2][2][$testChapter->testChapterId]:'';
                $value_panduan = array_key_exists($testChapter->testChapterId,$examTemplateDetails[2][3])?$examTemplateDetails[2][3][$testChapter->testChapterId]:'';
                $value_anli    = array_key_exists($testChapter->testChapterId,$examTemplateDetails[2][4])?$examTemplateDetails[2][4][$testChapter->testChapterId]:'';
                ?>
                <tr>
                    <td class="title"><?=$testChapter->name?></td>
                    <td><input class="input-sm" type="number" min="0" name="danxuan_<?=$testChapter->testChapterId?>" value="<?=$value_danxuan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="duoxuan_<?=$testChapter->testChapterId?>" value="<?=$value_duoxuan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="panduan_<?=$testChapter->testChapterId?>" value="<?=$value_panduan?>"></td>
                    <td><input class="input-sm" type="number" min="0" name="anli_<?=$testChapter->testChapterId?>" value="<?=$value_anli?>"></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <input type="submit" value="提交" class="btn btn-azure">
        <a href="<?=Url::to(['exam-template/index']);?>" class="btn btn-azure">取消</a>
        <?=Html::endForm(); ?>
    </div>
</div>