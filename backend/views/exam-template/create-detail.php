<?php
/** @var $examTemplate \common\models\ExamTemplate */
/** @var $testChapters_1 \common\models\TestChapter[] */
/** @var $testChapters_2 \common\models\TestChapter[] */
/** @var $examTemplateDetails array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '模拟题管理';

$examTemplatePa1 = explode('|',$examTemplate->pa1);
$number_pa1 = $examTemplatePa1[0];
$score_pa1 = $examTemplatePa1[1];
$examTemplatePa2 = explode('|',$examTemplate->pa2);
$number_pa2 = $examTemplatePa2[0];
$score_pa2 = $examTemplatePa2[1];
$examTemplatePa3 = explode('|',$examTemplate->pa3);
$number_pa3 = $examTemplatePa3[0];
$score_pa3 = $examTemplatePa3[1];
$examTemplatePa4 = explode('|',$examTemplate->pa4);
$number_pa4 = $examTemplatePa4[0];
$score_pa4 = $examTemplatePa4[1];

$examTemplatePb1 = explode('|',$examTemplate->pb1);
$number_pb1 = $examTemplatePb1[0];
$score_pb1 = $examTemplatePb1[1];
$examTemplatePb2 = explode('|',$examTemplate->pb2);
$number_pb2 = $examTemplatePb2[0];
$score_pb2 = $examTemplatePb2[1];
$examTemplatePb3 = explode('|',$examTemplate->pb3);
$number_pb3 = $examTemplatePb3[0];
$score_pb3 = $examTemplatePb3[1];
$examTemplatePb4 = explode('|',$examTemplate->pb4);
$number_pb4 = $examTemplatePb4[0];
$score_pb4 = $examTemplatePb4[1];

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
                    <td><input class="input-sm btn_p pa1" data-p="pa1" type="number" min="0" name="danxuan_<?=$testChapter->testChapterId?>" value="<?=$value_danxuan?>"></td>
                    <td><input class="input-sm btn_p pa2" data-p="pa2" type="number" min="0" name="duoxuan_<?=$testChapter->testChapterId?>" value="<?=$value_duoxuan?>"></td>
                    <td><input class="input-sm btn_p pa3" data-p="pa3" type="number" min="0" name="panduan_<?=$testChapter->testChapterId?>" value="<?=$value_panduan?>"></td>
                    <td><input class="input-sm btn_p pa4" data-p="pa4" type="number" min="0" name="anli_<?=$testChapter->testChapterId?>" value="<?=$value_anli?>"></td>
                </tr>
                <?php endforeach;?>
                <tr>
                    <td>小计</td>
                    <td>
                        <span class="total_pa1"><?=$number_pa1?></span>题
                        <input type="hidden" name="number_pa1" value="<?=$number_pa1?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pa1" value="<?=$score_pa1?>">分
                    </td>
                    <td>
                        <span class="total_pa2"><?=$number_pa2?></span>题
                        <input type="hidden" name="number_pa2" value="<?=$number_pa2?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pa2" value="<?=$score_pa2?>">分
                    </td>
                    <td>
                        <span class="total_pa3"><?=$number_pa3?></span>题
                        <input type="hidden" name="number_pa3" value="<?=$number_pa3?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pa3" value="<?=$score_pa3?>">分
                    </td>
                    <td>
                        <span class="total_pa4"><?=$number_pa4?></span>题
                        <input type="hidden" name="number_pa4" value="<?=$number_pa4?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pa4" value="<?=$score_pa4?>">分
                    </td>
                </tr>
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
                    <td><input class="input-sm btn_p pb1" data-p="pb1" type="number" min="0" name="danxuan_<?=$testChapter->testChapterId?>" value="<?=$value_danxuan?>"></td>
                    <td><input class="input-sm btn_p pb2" data-p="pb2" type="number" min="0" name="duoxuan_<?=$testChapter->testChapterId?>" value="<?=$value_duoxuan?>"></td>
                    <td><input class="input-sm btn_p pb3" data-p="pb3" type="number" min="0" name="panduan_<?=$testChapter->testChapterId?>" value="<?=$value_panduan?>"></td>
                    <td><input class="input-sm btn_p pb4" data-p="pb4" type="number" min="0" name="anli_<?=$testChapter->testChapterId?>" value="<?=$value_anli?>"></td>
                </tr>
            <?php endforeach;?>
                <tr>
                    <td>小计</td>
                    <td>
                        <span class="total_pb1"><?=$number_pb1?></span>题
                        <input type="hidden" name="number_pb1" value="<?=$number_pb1?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pb1" value="<?=$score_pb1?>">分
                    </td>
                    <td>
                        <span class="total_pb2"><?=$number_pb2?></span>题
                        <input type="hidden" name="number_pb2" value="<?=$number_pb2?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pb2" value="<?=$score_pb2?>">分
                    </td>
                    <td>
                        <span class="total_pb3"><?=$number_pb3?></span>题
                        <input type="hidden" name="number_pb3" value="<?=$number_pb3?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pb3" value="<?=$score_pb3?>">分
                    </td>
                    <td>
                        <span class="total_pb4"><?=$number_pb4?></span>题
                        <input type="hidden" name="number_pb4" value="<?=$number_pb4?>">
                        <input class="input-sm" type="number" min="0" max="200" name="score_pb4" value="<?=$score_pb4?>">分
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="提交" class="btn btn-azure">
        <a href="<?=Url::to(['exam-template/index']);?>" class="btn btn-azure">取消</a>
        <?=Html::endForm(); ?>
    </div>
</div>