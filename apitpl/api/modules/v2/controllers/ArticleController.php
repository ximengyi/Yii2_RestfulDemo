<?php
namespace api\modules\v2\controllers;
use yii\rest\ActiveController;
use common\models\Article;
use yii\web\Response;

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
		 $behaviors = parent::behaviors();
		 $behaviors['contentNegotiator']['formats']['text/html']=Response::FORMAT_HTML;
		 return $behaviors;
	 }
	 public function actions()
	 {
	 	$actions  = parent::actions();
	 	unset($actions['index']);
	 	return $actions;
	 }
	 public function actionIndex()
	 {
		 $aObj = Article::find()->one();
		 $result = '<h3>'.$aObj->title.'</h3><p>'.$aObj->content.'</p>';
		 return $result;

	 }
}
