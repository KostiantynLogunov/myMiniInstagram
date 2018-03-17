<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'picture',
                'format' => 'raw',
                'value' => function ($user) {
                    return Html::img($user->getImage(), ['width'=>'50px']);
                },
            ],
            'username',
            'email:email',
            'created_at:datetime',
            [
                'attribute' => 'roles',
                'value' => function ($user) {
                    return implode(',', $user->getRoles());
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
