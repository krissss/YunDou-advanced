<?php

namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\UserLoginFilter;
use common\models\Money;
use Yii;
use common\models\Users;
use yii\web\Controller;
use yii\data\Pagination;

class MoneyController extends Controller
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

    /** 资金收支首页 */
    public function actionIndex(){
        $query = Money::find();
        //print_r($query) ;exit;
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

    /** 资金查询 */
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
                case 'type':
                    $query = Money::find()
                        ->where(['type'=>$content]);
                    break;
                case 'userId':
                    $query = Money::find()
                        ->Where(['userId'=>$content]);
                    break;
                case 'nickname';
                    $table_a = Money::tableName();
                    $table_b = Users::tableName();
                    $query = Money::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'pay-more':
                    $query = Money::find()
                        ->Where(['>=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_PAY]);
                    break;
                case 'pay-equal':
                    $query = Money::find()
                        ->where(['=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_PAY]);
                    break;
                case 'pay-less':
                    $query = Money::find()
                        ->Where(['<=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_PAY]);
                    break;
                case 'withdraw-more':
                    $query = Money::find()
                        ->Where(['>=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_WITHDRAW]);
                    break;
                case 'withdraw-equal':
                    $query = Money::find()
                        ->where(['=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_WITHDRAW]);
                    break;
                case 'withdraw-less':
                    $query = Money::find()
                        ->Where(['<=', 'money', $content])
                        ->andWhere(['type'=>Money::TYPE_WITHDRAW]);
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
                    $table_a = Money::tableName();
                    $table_b = Users::tableName();
                    $query = Money::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(["$table_b.role" => $role]);
                    break;
                default:
                    $query = Money::find();
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
            ->orderBy(['moneyId'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }
}