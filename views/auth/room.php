<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = Yii::t('auth','room');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
	
	<div class="row">
		<?php if($userReferral){?>
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Вас приглосил</h3>
				</div>
				<div class="panel-body">
					<?=$userReferral->getAttribute('email')?> (<?=$userReferral->getAttribute('login')?>)
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-6 col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Реферальные пользователи</h3>
				</div>
				<div class="panel-body">
					<?php if($myReferral){ ?>
					<ul class="list-group">
						<?php foreach($myReferral as $user){ ?>
						
							<li class="list-group-item"><?=$user->getAttribute('email')?> (<?=$user->getAttribute('login')?>)</li>
						
					<?php } ?>
					</ul>
						<?= LinkPager::widget(['pagination' => $pagination]) ?>
					<?php }else{ ?>
						Пользователей не найдено
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Ссылка</h3>
				</div>
				<div id="gen_url" class="panel-body">
					<?php if(isset($url)){ ?>
						<?=Url::to([$url], 'http'); ?>
					<?php }else{ ?>
					<a href="/auth/url" class="btn btn-primary">Генерировать ссылку</a>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>