<?php
namespace api\controllers;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Article;
use yii\helpers\ArrayHelper;
/**
 * Article controller
 */
class ArticleController extends ActiveController
{
    /**
     * @inheritdoc
     */
	 public $modelClass = 'common\models\Article';
   public function behaviors()
	 {
		 return ArrayHelper::merge(parent::behaviors(),
   [
		 'authenticatior'=>[
			 'class'=>QueryParamAuth::className()
		 ]
	 ]);

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
			return Article::find()->where(['like','title',$_POST['keyword']])->all();
			// return "helloMENG";
		}
}
