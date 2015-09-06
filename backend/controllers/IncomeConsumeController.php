<?php
/** 云豆收支管理 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\UserLoginFilter;
use Yii;
use common\models\IncomeConsume;
use common\models\Users;
use common\models\UsageMode;
use yii\web\Controller;
use yii\data\Pagination;

class IncomeConsumeController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ]
        ];
    }

    /** 云豆收支记录 */
    public function actionIndex(){
        $query = IncomeConsume::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    /** 云豆收支记录查询 */
    public function actionSearch(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if ($request->isPost) {
            $type = $request->post('type');
            $content = $request->post('content');
        } else {
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if ($type || !$query) {
            switch ($type) {
                case 'usageModeName':
                    $table_a = IncomeConsume::tableName();
                    $table_b = UsageMode::tableName();
                    $query = IncomeConsume::find()
                        ->leftJoin($table_b, "$table_a.usageModeId=$table_b.usageModeId")
                        ->where(['like', "$table_b.usageModeName", $content]);
                    break;
                case 'nickname';
                    $table_a = IncomeConsume::tableName();
                    $table_b = Users::tableName();
                    $query = IncomeConsume::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'income-more':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME])
                        ->andWhere(['>=', 'bitcoin', $content]);
                    break;
                case 'income-equal':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME])
                        ->andwhere(['==', 'bitcoin', $content]);
                    break;
                case 'income-less':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME])
                        ->andWhere(['<=', 'bitcoin', $content]);
                    break;
                case 'pay-more':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME])
                        ->andWhere(['>=', 'bitcoin', $content]);
                    break;
                case 'pay-equal':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME])
                        ->andwhere(['==', 'bitcoin', $content]);
                    break;
                case 'pay-less':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME])
                        ->andWhere(['<=', 'bitcoin', $content]);
                    break;
                case 'role':
                    $role = '';
                    if ($content == 'a' || $content == 'A' || $content == 'A级') {
                        $role = Users::ROLE_A;
                    } elseif ($content == 'aa' || $content == 'AA' || $content == 'AA级') {
                        $role = Users::ROLE_AA;
                    } elseif ($content == 'aaa' || $content == 'AAA' || $content == 'AAA级') {
                        $role = Users::ROLE_AAA;
                    } elseif ($content == '管理员') {
                        $role = Users::ROLE_ADMIN;
                    }
                    $table_a = IncomeConsume::tableName();
                    $table_b = Users::tableName();
                    $query = IncomeConsume::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(["$table_b.role" => $role]);
                    break;
                case 'type':
                    $query = IncomeConsume::find()
                        ->where(['type'=>$content]);
                    break;
                default:
                    $query = IncomeConsume::find();
                    break;
            }
        }
        Yii::$app->session->setFlash('query', $query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['incomeConsumeId'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

}