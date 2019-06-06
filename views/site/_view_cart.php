<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

//echo '<pre>';
//print_r($model_ATaxOrgInfo);
//echo '</pre>';
?>

<div>
    <h1>Данные налогоплательщика</h1>
    <fieldset>
        <legend>Общая информация</legend>
        <?=
        DetailView::widget([
            'model' => $model_iin,
            'attributes' => [
                'name_ru',
                'iin',
                'totalArrear',
                'totalTaxArrear',
                'pensionContributionArrear',
                'socialHealthInsuranceArrear',
                'socialContributionArrear'
            ]
        ])
        ?>

    </fieldset>

    <fieldset>
        <legend>Таблица задолженностей по органам государственных доходов</legend>
        <?=
        DetailView::widget([
            'model' => $model_ATaxOrgInfo,
            'attributes' => [
                'title_ru',
                'reportAcrualDate',
                'totalArrear',
                'totalTaxArrear',
                'pensionContributionArrear',
                'socialHealthInsuranceArrear',
                'socialContributionArrear',
            ],
        ])
        ?>

    </fieldset>    
    <fieldset>
        <legend>Таблица задолженностей по налогоплательщику и его структурным подразделениям</legend>
        <?=
        DetailView::widget([
            'model' => $model_iin,
            'attributes' => [
                'name_ru',
                'iin',
                'bccTotalArrear',
            ]
        ])
        ?>
    </fieldset>      
    <h4>Подробнее</h4>
    <div class="">
        <table class="table table-info table-bordered">
            <tr>
                <th id="" style="width:20%">КБК</th>
                <th id="" style="width:15%">Задолженность по платежам, учет по которым ведется в органах государственных доходов</th>
                <th id="" style="width:15%">Задолженность по сумме пени</th>
                <th id="" style="width:15%">Задолженность по сумме процентов</th>
                <th id="" style="width:15%">Задолженность по сумме штрафа</th>
                <th id="" style="width:15%">Всего задолженности</th>    
            </tr>
            <?php foreach ($model_ATaxPayerInfo as $item) : ?>
                <tr>
                    <td><?= $item->bcc . ' ' . $item->bccNameRu; ?></td>
                    <td><?= $item->taxArrear; ?></td>
                    <td><?= $item->poenaArrear; ?></td>
                    <td><?= $item->percentArrear; ?></td>
                    <td><?= $item->fineArrear; ?></td>
                    <td><?= $item->totalArrear; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>