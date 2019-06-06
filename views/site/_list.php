<?php

use yii\helpers\Html;
use yii\grid\GridView;
?>

<div>
    <h1>Список сохраенных данных ИИН/БИН налогоплательщика</h1>
    <?php
    echo GridView::widget([
        'dataProvider' => $provider,
        'emptyText' => 'Ничего не найдено',
        'columns' => [
            [
                'attribute' => 'iin',
                'format' => 'html',
                'value' => function ($data) {
                    $url = \yii\helpers\Url::to(['view_cart', 'id' => $data->id]);
                    return Html::a($data->iin, $url);
                }
            ],
            [
                'attribute' => 'name_ru',
                'format' => 'text'
            ],
            [
                'attribute' => 'name_kz',
                'format' => 'text'
            ],
            [
                'attribute' => 'totalArrear',
                'format' => 'text'
            ],
            [
                'attribute' => 'actual_date',
                'format' => 'text'
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'text'
            ],
            [// Здесь начинается описание колонки действий
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'headerOptions' => ['width' => '70px;'],
                'buttons' => [
                    'view' => function($url, $model) {
                        $url = \yii\helpers\Url::to(['view_cart', 'id' => $model->id]);
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', $url);
                    },
                ]
            ]
        ],
    ]);
    ?>
</div>

