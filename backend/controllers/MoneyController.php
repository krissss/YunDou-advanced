<?php

namespace backend\controllers;

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
            ],
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

    /** 充值查询 */
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
                case 'userId':
                    $query = Money::find()
                        ->Where(['userId'=>$content]);
                    break;
                case 'nickname';
                    $table_a = Money::tableName();
                    $table_b = Users::tableName();
                    $query = Money::find()
                        ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'money-more':
                    $query = Money::find()
                        ->Where(['>', 'money', $content]);
                    break;
                case 'money-equal':
                    $query = Money::find()
                        ->where(['=', 'money', $content]);
                    break;
                case 'money-less':
                    $query = Money::find()
                        ->Where(['<', 'money', $content]);
                    break;
                case 'bitcoin-more':
                    $query = Money::find()
                        ->Where(['>', 'bitcoin', $content]);
                    break;
                case 'bitcoin-equal':
                    $query = Money::find()
                        ->where(['=', 'bitcoin', $content]);
                    break;
                case 'bitcoin-less':
                    $query = Money::find()
                        ->Where(['<', 'bitcoin', $content]);
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
                        ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
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
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }
}