<?php

namespace backend\modules\customer\controllers;

use common\models\Users;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use common\models\IncomeConsume;
use yii\data\Pagination;

class BitcoinController extends Controller
{
    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $query = IncomeConsume::find()->where(['userId'=>$user['userId']])->orWhere(['fromUserId'=>$user['userId']]);
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

    /** 查询 */
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
            $user = Yii::$app->session->get('user');

            switch ($type) {
                case 'income':
                    if($content == 'my'){
                        $query = IncomeConsume::find()
                            ->where(['type'=>IncomeConsume::TYPE_INCOME,'userId'=>$user['userId']]);
                    }elseif($content == 'others'){
                        $query = IncomeConsume::find()
                            ->where(['type'=>IncomeConsume::TYPE_INCOME,'FromUserId'=>$user['userId']]);
                    }
                    break;
                case 'consume':
                    if($content == 'my'){
                        $query = IncomeConsume::find()
                            ->where(['type'=>IncomeConsume::TYPE_CONSUME,'userId'=>$user['userId']]);
                    }
                    break;
                case 'userId':
                    $query = IncomeConsume::find()
                        ->where(['userId'=>$user['userId']])
                        ->orWhere(['fromUserId'=>$user['userId']])
                        ->andWhere(['userId'=>$content]);
                    break;
                case 'nickname';case 'realname':
                    $table_a = IncomeConsume::tableName();
                    $table_b = Users::tableName();
                    $query = IncomeConsume::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(["$table_a.userId"=>$user['userId']])
                        ->orWhere(["$table_a.fromUserId"=>$user['userId']])
                        ->andWhere(['like', "$table_b.$type", $content]);
                    break;
                default:
                    $query = IncomeConsume::find()->where(['userId'=>$user['userId']])->orWhere(['fromUserId'=>$user['userId']]);
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