<?php
/** @var  $user  \frontend\models\User */
/** @var  $modelPicture frontend\modules\user\models\forms\PictureForm */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>
<h3><?= Html::encode($user->username); ?></h3>
<p><?php echo  HtmlPurifier::process($user->about); ?></p>
<hr>

<img src="<?= $user->getPicture(); ?>"  id="profile-picture" width="300">

<?php if ($currentUser && $currentUser->equals($user)): ?>
    <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
    <div class="alert alert-danger display-none" id="profile-image-fail"></div>

    <?= FileUpload::widget([
        'model' => $modelPicture,
        'attribute' => 'picture',
        'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
        'options' => ['accept' => 'image/*'],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                console.log(data.result.success);
                if (data.result.success) {
                    $ ("#profile-image-success").show();
                    $ ("#profile-image-fail").hide();
                    $ ("#profile-picture").attr("src", data.result.pictureUri);
                } else {
                    $ ("#profile-image-fail").html(data.result.errors.picture).show();
                    $ ("#profile-image-success").hide();
                }
            }',
        ],
    ]); ?>
    <hr>

<?php else: ?>

    <a href="<?php echo Url::to(['/user/profile/subscribe', 'id'=>$user->getId()]); ?>" class="btn btn-info">Subscribe</a>
    <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id'=>$user->getId()]); ?>" class="btn btn-info">Unsubscribe</a>
    <hr>


    <?php if ($currentUser): ?>
        <h5>Freinds, who also following <?= Html::encode($user->username)?>:</h5>
        <div class="row">
            <?php foreach ($currentUser->getMutualSubscriptionTo($user) as $item): ?>
                <div class="col-md-12">
                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname'=>($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                        <?= Html::encode($item['username']); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif;?>

<?php endif; ?>
<hr>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1">
        Subscriptions: <?= $user->countSubscriptions(); ?>
    </button>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
        Followers: <?= $user->countFollowers(); ?>
    </button>

    <!-- Modal1 -->
    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($user->getSubscriptions() as $subscription): ?>
                            <div class="col-md-12">
                                <a href="<?php echo Url::to(['/user/profile/view', 'nickname'=>($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                                    <?= Html::encode($subscription['username']); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal2 -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $followers): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname'=>($followers['nickname']) ? $followers['nickname'] : $followers['id']]); ?>">
                                <?= Html::encode($followers['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<br>
