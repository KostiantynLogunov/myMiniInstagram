<?php

namespace frontend\modules\post\models\forms;

use frontend\models\events\PostCreatedEvent;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;
use Yii;
use yii\base\Model;


class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGTH = 1000;
    const EVENT_POST_CREATED = 'post_created';

    public $picture;
    public $description;

    private $user;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize()],
            [['description'], 'string', 'max'=>self::MAX_DESCRIPTION_LENGTH],
        ];
    }

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
    }

    public function resizePicture()
    {
        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save();
    }

    public function save()
    {
        if ($this->validate()) {

            $post = new Post();
            $post->description = $this->description;
            $post->created_at = time();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            if ($post->save(false)) {
                $event = new PostCreatedEvent();
                $event->user = $this->user;
                $event->post = $post;
                $this->trigger(self::EVENT_POST_CREATED, $event);
                return true;
            }
        }
        return false;
    }


    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    public function getMaxFileSize()
    {
        return (integer) Yii::$app->params['maxFileSize'];
    }
}