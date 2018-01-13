<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\ApiLoginForm;
use api\models\ApiSignupForm;

/**
 * Site controller
 */
class AdminuserController extends ActiveController
{
         public $modelClass = 'common\models\Adminuser';

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
       $model = new ApiLoginForm();
      // $model->username = $_POST['username'];
      // $model->password = $_POST['password'];
      $model->load(Yii::$app->getRequest()->getBodyParams(),'');
      if ($model->login()) {
      return ['access_token'=>$model->login()];
    }else {
       $model->validate();
       return $model;
      }
    }
    public function actionSignup()
    {
       $model = new ApiSignupForm();
       $model->load(Yii::$app->getRequest()->getBodyParams(),'');
      if ($model->signup()) {
      return ['result'=>'注册成功'];
    }else {
       $model->validate();
       return $model;
      }
    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
