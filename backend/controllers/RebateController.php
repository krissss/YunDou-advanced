<?php
/** 充值返点控制器 */
namespace backend\controllers;

use backend\filters\UserLoginFilter;
use backend\models\forms\AddPracticeForm;
use backend\models\forms\AddRebateForm;
use yii\web\Controller;
use common\functions\CommonFunctions;
use common\models\Scheme;
use Yii;
use yii\base\Exception;

class RebateController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    /** 充值设置 */
    public function actionIndex(){
        $schemes = Scheme::findAllRebateScheme();
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
                $addRebateForm = AddRebateForm::initWithId($schemeId);
            }else{
                $addRebateForm = new AddRebateForm();
            }
            return $this->renderAjax('rebate-form',[
                'addRebateForm' => $addRebateForm
            ]);
        }
        CommonFunctions::createAlertMessage("非法获取","error");
        return $this->redirect(['rebate/index']);
    }

    /** 生成方案 */
    public function actionGenerate(){
        $request = Yii::$app->request;
        $addRebateForm = new AddRebateForm();
        if($addRebateForm->load(Yii::$app->request->post()) && $addRebateForm->validate()) {
            if ($request->post("state") == "able") {
                if($addRebateForm->recordOne(true)){  //返回值为true则为存在冲突
                    return $this->redirect(['rebate/index']);
                }
            } else {
                $addRebateForm->recordOne();
            }
            CommonFunctions::createAlertMessage("充值返点方案设置成功","success");
            return $this->redirect(['rebate/index']);
        }
        CommonFunctions::createAlertMessage("生成充值返点方案失败，参数不全或存在非法字段","error");
        return $this->redirect(['rebate/index']);
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
            return $this->redirect(['rebate/index']);
        }else{
            CommonFunctions::createAlertMessage("方案启用状态下，删除出错",'error');
            return $this->redirect(['rebate/index']);
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
            case 'name':case 'state':
            $query = Scheme::find()
                ->where(['like', $type, $content]);
            break;
            default:
                $query = Scheme::find();
                break;
        }
        $schemes = $query
            ->andWhere(['usageModeId'=>Scheme::USAGE_REBATE])
            ->all();
        return $this->render('index',[
            'schemes' => $schemes,
        ]);
    }
}