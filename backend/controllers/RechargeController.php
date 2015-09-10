<?php
/** 充值方案设置 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\DevelopFilter;
use backend\filters\ManagerFilter;
use backend\filters\UserLoginFilter;
use backend\models\forms\AddRechargeForm;
use common\functions\CommonFunctions;
use common\models\Scheme;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

class RechargeController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ],[
                'class' => ManagerFilter::className(),
                'except'=>['index','search']
            ],[
                'class' => DevelopFilter::className(),
            ]
        ];
    }

    /** 充值设置 */
    public function actionIndex(){
        $schemes = Scheme::findAllPayScheme();
        return $this->render('index', [
            'schemes' => $schemes,
        ]);
    }

    /** 增加或更新方案 */
    public function actionAddUpdate(){
        $request = Yii::$app->request;
        if($request->isPost){
            $schemeId = $request->post("schemeId");
            if($schemeId){
                $addRechargeForm = AddRechargeForm::initWithId($schemeId);
            }else{
                $addRechargeForm = new AddRechargeForm();
            }
            return $this->renderAjax('recharge-form',[
                'addRechargeForm' => $addRechargeForm
            ]);
        }
        CommonFunctions::createAlertMessage("非法获取","error");
        return $this->redirect(['recharge/index']);
    }

    /** 生成充值方案 */
    public function actionGenerate(){
        $request = Yii::$app->request;
        $addRechargeForm = new AddRechargeForm();
        if($addRechargeForm->load(Yii::$app->request->post()) && $addRechargeForm->validate()) {
            if ($request->post("state") == "able") {
                if($addRechargeForm->recordOne(true)){  //返回值为true则为存在冲突
                    return $this->redirect(['recharge/index']);
                }
            } else {
                $addRechargeForm->recordOne();
            }
            CommonFunctions::createAlertMessage("充值方案设置成功","success");
            return $this->redirect(['recharge/index']);
        }
        CommonFunctions::createAlertMessage("生成充值方案失败，参数不全或存在非法字段","error");
        return $this->redirect(['recharge/index']);
    }

    /** 修改启用状态 */
    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $newState = $request->post('newState');
            $schemeId = intval($request->post('id'));
            if($newState == 'open'){
                $newState = Scheme::STATE_ABLE;
                $result = Scheme::updateState($schemeId,$newState);
                if($result){    //冲突错误
                    return $result;
                }
                return 'open';
            }elseif($newState == 'close'){
                $newState = Scheme::STATE_DISABLE;
                Scheme::updateState($schemeId,$newState);
                return 'close';
            }else{
                return '状态非法';
            }
        }else{
            throw new Exception('非ajax提交');
        }
    }

    /** 删除 */
    public function actionDelete($id){
        /** @var  $scheme \common\models\Scheme */
        $scheme = Scheme::findOne($id);
        if($scheme && $scheme->state==Scheme::STATE_DISABLE){ //非启用状态才能删除
            $scheme->delete();
            CommonFunctions::createAlertMessage("删除方案成功",'success');
            return $this->redirect(['recharge/index']);
        }else{
            CommonFunctions::createAlertMessage("方案启用状态下，删除出错",'error');
            return $this->redirect(['recharge/index']);
        }
    }

    /** 查询 */
    public function actionSearch(){
        $request = Yii::$app->request;
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        switch ($type) {
            case 'state':
                $query = Scheme::find()
                    ->where(['like', $type, $content]);
                break;
            default:
                $query = Scheme::find();
                break;
        }
        $schemes = $query
            ->andWhere(['usageModeId'=>Scheme::USAGE_PAY])
            ->all();
        return $this->render('index',[
            'schemes' => $schemes,
        ]);
    }

}