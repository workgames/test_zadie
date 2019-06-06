<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Тестовое задание';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Тестовое задание!</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'iin')->textInput(['autofocus' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('Запросить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                <?= Html::a('Список сохраненых данных', '/site/list', ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
