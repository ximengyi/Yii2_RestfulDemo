<?php
namespace api\controllers;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Article;
use common\models\Adminuser;
use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBasicAuth;

use yii\web\ForbiddenHttpException;
use yii\filters\RateLimiter;
/**
 * Article controller
 */
class ArticleController extends ActiveController
{
    /**
     * @inheritdoc
     */
	 public $modelClass = 'common\models\Article';
	 //基于QueryParamAuth的access——token的验证方式
   public function behaviors()
	 {
		 $behaviors = parent::behaviors();
		 $behaviors['authenticator']=[
			 'class'=>QueryParamAuth::className(),
		 ];
		 $behaciors['rateLimiter'] =[
			 'class' => RateLimiter::className(),
			 'enableRateLimitHeaders' =>true,
		 ];
		 return $behaviors;

	 }

	 // public function behaviors()
	 // {
		//  return ArrayHelper::merge(parent::behaviors(),
	 // [
		//  'authenticatior'=>[
		// 	 'class'=>HttpBasicAuth::className(),
		// 	 'auth'=>function ($username,$password){
		// 		 $user = Adminuser::find()->where(['username'=>$username])->one();
		// 		 if ($user->validatePassword($password)) {
		// 		    return $user;
		// 		 }
		// 		 return null;
		// 	 },
		//  ]
	 // ]);
   //
	 // }
	 public function checkAccess($action,$model=null,$params=[])
	 {//对activecontroller类中的默认实现的方法进行权限设置
		 if ($action==='view') {

			 if(\Yii::$app->user->can('ArticleViewer')){
	 			return  true;
	 		   }
		 }

			if ($action === 'view'||$action==='update'||$action==='delete'||$action ==='create'||$action==='index') {
						if (\Yii::$app->user->can('ArticleAdmin')) {
				  return true;
						}
			}
			throw new ForbiddenHttpException("对不起您没有进行该操作的权限");

	 }

	 public function actions()
	 {
		 $actions  = parent::actions();
		 unset($actions['index']);
		 return $actions;
	 }
	 public function actionIndex()
{
	$modelClass  = $this->modelClass;
	return new ActiveDataProvider(
		[
			'query'=>$modelClass::find()->asArray(),
			'pagination'=>['pageSize'=>5],
		]
	);
}
    public function actionSearch()
		{
			if (!Yii::$app->user->can('ArticleAdmin')) {
				 throw new ForbiddenHttpException('对不起，您没有进行该操作的权限');

			}


			return Article::find()->where(['like','title',$_POST['keyword']])->all();
			// return "helloMENG";
		}
}
