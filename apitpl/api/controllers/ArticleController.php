<?php
namespace api\controllers;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Article;
use common\models\Adminuser;
use yii\helpers\ArrayHelper;
use yii\filters\auth\HttpBasicAuth;
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
		 return ArrayHelper::merge(parent::behaviors(),
   [
		 'authenticatior'=>[
			 'class'=>QueryParamAuth::className()
		 ]
	 ]);

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
			return Article::find()->where(['like','title',$_POST['keyword']])->all();
			// return "helloMENG";
		}
}
