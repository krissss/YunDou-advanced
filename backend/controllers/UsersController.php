<?php

namespace backend\controllers;

use backend\functions\CommonFunctions;
use Yii;
use common\models\Users;
use common\models\Province;
use common\models\MajorJob;
use backend\models\forms\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
//use server\Spreadsheet_Excel_Reader;
use yii\web\UploadedFile;

class UsersController extends Controller
{

    public function actionIndex()
    {
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query->where('weixin is not null')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    public function actionOther(){
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $users = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('other',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    public function actionSearchWx(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query){
            switch ($type) {
                case 'province':
                    $table_a = Users::tableName();
                    $table_b = Province::tableName();
                    $query = Users::find()
                        ->leftJoin($table_b, "$table_a.provinceId=$table_b.provinceId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'majorJob':
                    $table_a = Users::tableName();
                    $table_b = MajorJob::tableName();
                    $query = Users::find()
                        ->leftJoin($table_b, "$table_a.majorJobId='.$table_b.majorJobId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                case 'role':
                    if ($content == 'A' || $content == 'A级') {
                        $role = Users::ROLE_A;
                    } elseif ($content == 'AA' || $content == 'AA级') {
                        $role = Users::ROLE_AA;
                    } elseif ($content == 'AAA' || $content == 'AAA级') {
                        $role = Users::ROLE_AAA;
                    } elseif ($content == '管理员') {
                        $role = Users::ROLE_ADMIN;
                    } else {
                        CommonFunctions::createAlertMessage("该等级用户不存在", 'warning');
                        return $this->redirect(['users/index']);
                    }
                    $query = Users::find()
                        ->where(['role'=>$role]);
                    break;
                default:
                    $query = Users::find();
                    break;
            }
            $query = $query->andWhere('weixin is not null');    //添加微信用户条件，位置不可改
        }

        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $user = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'users' => $user,
            'pages' => $pagination
        ]);
    }


public function actionView($userId)
    {
        return $this->render('view', [
            'model' => $this->findModel($userId),
        ]);
    }

    public function actionCreate()
    {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'userId' => $model->userId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($userId)
    {
        $model = $this->findModel($userId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'userId' => $model->userId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($userId)
    {
        $model=users::findOne($userId);
        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($userId)
    {
        if (($model = Users::findOne($userId)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpusers()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {                
                $model->file->saveAs('xls/' . $model->file->baseName . '.' . $model->file->extension);
                Yii::$app->session->setFlash("xlsname",$model->file->baseName);
                return $this->redirect(['users/import']);
            }
        }

        return $this->render('upusers', ['model' => $model]);
        /*if(Yii::$app->request->post()){
            $session = Yii::$app->session;
            if (isset($session['usersPath'])) {
                include('../web/server/do.php');
                unset($session['usersPath']);
            }else{
                echo "<script>alert('请选择数据文件!');</script>";
                return $this->render('upusers'
                  
                );
            }
        }else{
            return $this->render('upusers'
            );
        }*/
    }
    public function actionImport() {  
            /*if(Yii::$app->request->post())
            {
                echo 1;
            }
            else{

        return $this->render('import');   
    }*/
        include_once("server/reader.php");
        $xlsname = Yii::$app->session->getFlash("xlsname");
        $file_name = $xlsname.".xls";
        $xls = new Spreadsheet_Excel_Reader();
        $xls->setOutputEncoding('utf-8');
        //echo "xls/".$file_name;
        //exit;
        $xls->read("xls/".$file_name);
        $data_values = "";
        for ($i=2; $i<=$xls->sheets[0]['numRows']; $i++) {
            //$userId = $xls->sheets[0]['cells'][$i][1];
            $username = $xls->sheets[0]['cells'][$i][1];
            $password = $xls->sheets[0]['cells'][$i][2];
            $email = $xls->sheets[0]['cells'][$i][3];
            $cellphone = $xls->sheets[0]['cells'][$i][4];
            $weixin = $xls->sheets[0]['cells'][$i][5];
            $majorJobId = $xls->sheets[0]['cells'][$i][6];
            $nickname = $xls->sheets[0]['cells'][$i][7];
            $realname = $xls->sheets[0]['cells'][$i][8];
            $introduce = $xls->sheets[0]['cells'][$i][9];
            $bitcoin = $xls->sheets[0]['cells'][$i][10];
            $province = $xls->sheets[0]['cells'][$i][11];
            $city = $xls->sheets[0]['cells'][$i][12];
            $company = $xls->sheets[0]['cells'][$i][13];
            $address = $xls->sheets[0]['cells'][$i][14];
            $registerDate = $xls->sheets[0]['cells'][$i][15];
            $role = $xls->sheets[0]['cells'][$i][16];
            $recommendUserID = $xls->sheets[0]['cells'][$i][17];
            $remark = $xls->sheets[0]['cells'][$i][18];
            $data_values .= "('$username','$password','$email','$cellphone','$weixin','$majorJobId','$nickname','$realname','$introduce','$bitcoin','$province','$city','$company','$address','$registerDate','$role','$recommendUserID','$remark'),";
        }

        // print_r($data_values);
        return $this->render('import');

    } 


    public function actionImportin(){

        return $this->redirect(['users/index']);
    }


         public function actionSearch1()
    {
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' =>100,
            'totalCount' =>$query->count()
        ]);
      $users = $query ->where(['role' => 1])->offset($pagination->offset)
                ->limit($pagination->limit)->all();
               
        return $this->render('index',[
            'user' => $users,
            'pages' => $pagination
        ]);

    }
    public function actionSearch2()
    {
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' =>100,
            'totalCount' =>$query->count()
        ]);
      $users = $query ->where(['province' =>1])->offset($pagination->offset)
                ->limit($pagination->limit)->all();
               
        return $this->render('index',[
            'user' => $users,
            'pages' => $pagination
        ]);

    }
             public function actionSearch3()
    {
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' =>100,
            'totalCount' =>$query->count()
        ]);
      $users = $query ->where(['province' => 2])->offset($pagination->offset)
                ->limit($pagination->limit)->all();
               
        return $this->render('index',[
            'user' => $users,
            'pages' => $pagination
        ]);

    }
             public function actionSearch4()
    {   

        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' =>100,
            'totalCount' =>$query->count()
        ]);
      $users = $query ->where(['role' => 1])->offset($pagination->offset)
                ->limit($pagination->limit)->all();
               
        return $this->render('index',[
            'user' => $users,
            'pages' => $pagination
        ]);

    }




}
