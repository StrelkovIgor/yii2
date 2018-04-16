<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('auth','title');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
	<?php if($referral){ ?>
		<div class="row">
			<label class="col-lg-2 control-label" for="registration-email">Реферальная ссылка</label>
			<div class="col-lg-10">
				<?=$referral->getAttribute('email')?> (<?=$referral->getAttribute('login')?>)
			</div>
		</div>
	<?php } ?>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'login')->textInput(['autofocus' => true]) ?>
		
		<?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
		
		<?= $form->field($model, 'repeat_password')->passwordInput() ?>
		<?php if(isset($model->referral)){ ?>
		<?= $form->field($model, 'referral', ['template' => "{input}"])->hiddenInput(['value'=>$model->referral]) ?>
		<?php } ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton(Yii::t('auth','registration'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
