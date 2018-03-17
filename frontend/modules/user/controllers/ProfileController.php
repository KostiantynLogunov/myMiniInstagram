<?php

namespace frontend\modules\user\controllers;


//use Faker\Factory;
use frontend\models\User;
use frontend\modules\user\models\forms\PictureForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfileController extends Controller
{
    public function actionView($nickname)
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        $modelPicture = new PictureForm();

        return $this->render('view', [
            'user'=>$this->findUser($nickname),
            'currentUser' => $currentUser,
            'modelPicture' => $modelPicture,
            ]);
    }

    /**
     * @param string|integer $nickname
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    private function findUser($nickname)
    {
        if ($user = User::find()->where(['nickname'=>$nickname])->orWhere(['id'=>$nickname])->one())
        {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionSubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        $currentUser->followUser($user);

        return $this->redirect(['/user/profile/view', 'nickname'=>$user->getNickname()]);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    private function findUserById($id)
    {
        if ($user = User::findOne($id)) {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        $currentUser->unfollowUser($user);

        return $this->redirect(['/user/profile/view', 'nickname'=>$user->getNickname()]);
    }

    /**
     *
     */
    public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate())
        {
            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);

            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true,
                    'pictureUri' =>Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors'=>$model->getErrors()];
    }

    /*public function actionGenerate()
    {
        $faker = Factory::create();

        for ($i = 0; $i<1000; $i++)
        {
            $user = new User([
                'username'=>$faker->name,
                'email'=>$faker->email,
                'about'=>$faker->text(200),
                'nickname'=>$faker->regexify('[A-Za-z0-9_]{5,15}'),
                'auth_key'=>Yii::$app->security->generateRandomString(),
                'password_hash'=>Yii::$app->security->generateRandomString(),
                'created_at'=>$time = time(),
                'updated_at'=>$time,
            ]);
            $user->save(false);
        }
    }*/
}