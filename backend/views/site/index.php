<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Admin panel!</h1>
    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-4">
                <h2>Complains</h2>

                <p>Sometime people post offensive things....</p>

                <p><a class="btn btn-default" href="<?= \yii\helpers\Url::to(['/complaints/manage']);?>">Manage</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    viewComplaintsList: <?php var_dump(Yii::$app->user->can('viewComplaintsList'));?> <br>
    approvePost: <?php var_dump(Yii::$app->user->can('approvePost')); ?> <br>
    deleteUser: <?php var_dump(Yii::$app->user->can('deleteUser'));?> <br>
    </div>
</div>
