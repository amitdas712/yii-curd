<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;


class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	 public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if (Yii::$app->request->post()) {
			
			$model->username = $_REQUEST['LoginForm']['username'];
			$model->password = $_REQUEST['LoginForm']['password'];
            print"<pre>";
			//print_r($_POST);die;
			$model->Login();
			return $this->goBack();
        }

       $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	
	
	
	
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {	
			
		$user = User::find()->asArray()->all();
		//print"<pre>";	
		//print_r($customer);die;
			
		return $this->render('index',['user' => $user]);
    }

  
    public function actionCreate()
    {
     $model = new User();
		//
		//print_r($model);die;
		
        if (Yii::$app->request->post()) {
			
			$model->name = $_REQUEST['User']['name'];
			$model->email = $_REQUEST['User']['email'];
			$model->location = $_REQUEST['User']['location'];
			//print"<pre>";
			//print_r($model);die;
			
			$model->save();

		   return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
		
		//print_r($id); die;
		$data = User::findOne($id);
		$data->delete();

        return $this->redirect(['index']);
		
		
		
    }
	
	public function actionEdit($id)
    {
		
		$model = User::findOne($id);

        if (Yii::$app->request->post()) {
            
			$model->name = $_REQUEST['User']['name'];
			$model->email = $_REQUEST['User']['email'];
			$model->location = $_REQUEST['User']['location'];
			$model->save();
			return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }
	
   
}
