<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
?>

<div class="post-default-index">

    <div class="row">
        <div class="col-md-12">
            <?php if ($post->user): ?>
                <?= $post->user->username;?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>" width="500"/>
        </div>
        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

    </div>

</div>

<hr>

<div class="col-md-12">
    Likes: <span class="likes-count"><?= $post->countLikes(); ?></span>

    <a href="#" class="btn btn-primary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?= $post->id; ?>">
        Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
    </a>
    <a href="#" class="btn btn-primary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
        Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
    </a>
</div>

<?php $this->registerJsFile('@web/js/like.js', [
    'depends' => JqueryAsset::className(),
]);
