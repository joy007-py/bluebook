<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCssFile("/bluebook/admin/css/admin.css");

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
	
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <?= Yii::$app->session->getFlash('success') ?>
  </div>
<?php endif; ?>

<?php if ( Yii::$app->session->hasFlash('error') ): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'fullName',
                'format'    => 'text'
            ],

            [
                'attribute' =>  'email',
                'format'    => 'email',
            ],

            [
				'attribute' => 'status',
				'format' => 'raw',
				'filter'=> ['Y'=>'Active','N'=>'IN-Active'],
				'value' =>  function ($model) {
                    return ($model ->status == 'Y') ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">In-Active</span>';
                },
			],
			[
				'attribute' => 'usertype',
				'format' => 'raw',
				'filter'=> ['A'=>'ADMIN','G'=>'GENERAL'],
				'value' =>  function ($model) {
                    return ($model ->usertype == 'A') ? '<span class="label label-info">ADMIN</span>' : '<span class="label label-warning">GENERAL</span>';
                },
			],
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
