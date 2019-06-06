<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

//echo '<pre>';
//print_r($model);
//echo '</pre>';


?>

<div>
    <h1>Полученные данные</h1>
    <?= Html::a('Список сохраненых данных', '/site/list', ['class' => 'btn btn-danger']) ?>
    <br />
    <br />
    <fieldset>
        <legend>Общая информация</legend>
        <?=
        DetailView::widget([
            'model' => $model['data_iin'],
            'attributes' => [
                [
                    'attribute' => 'nameRu',
                    'label' => 'Наименование налогоплательщика',
                ],
                [
                    'attribute' => 'iinBin',
                    'label' => 'ИИН/БИН налогоплательщика',
                ],
                [
                    'attribute' => 'totalArrear',
                    'label' => 'Всего задолженности (тенге)',
                ],
                [
                    'attribute' => 'totalTaxArrear',
                    'label' => 'Итого задолженности в бюджет',
                ],
                [
                    'attribute' => 'pensionContributionArrear',
                    'label' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
                ],
                [
                    'attribute' => 'socialContributionArrear',
                    'label' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
                ],
                [
                    'attribute' => 'socialHealthInsuranceArrear',
                    'label' => 'Задолженность по социальным отчислениям',
                ],
            ],
        ])
        ?>

    </fieldset>

    <fieldset>
        <legend>Таблица задолженностей по органам государственных доходов</legend>
        <?=
        DetailView::widget([
            'model' => $model['data_taxOrgInfo'],
            'attributes' => [
                [
                    'attribute' => 'nameRu',
                    'label' => 'Наименование',
                ],
                [
                    'attribute' => 'totalArrear',
                    'label' => 'Всего задолженности',
                ],
                [
                    'attribute' => 'reportAcrualDate',
                    'label' => 'По состоянию на',
                    'value' => function ($data) {
                        return date('Y-m-d H:i:s', $data['reportAcrualDate'] / 1000);
                    },
                ],
                [
                    'attribute' => 'totalTaxArrear',
                    'label' => 'Итого задолженности в бюджет',
                ],
                [
                    'attribute' => 'pensionContributionArrear',
                    'label' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
                ],
                [
                    'attribute' => 'socialContributionArrear',
                    'label' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
                ],
                [
                    'attribute' => 'socialHealthInsuranceArrear',
                    'label' => 'Задолженность по социальным отчислениям',
                ],
            ],
        ])
        ?>

    </fieldset>    

    <fieldset>
        <legend>Таблица задолженностей по налогоплательщику и его структурным подразделениям</legend>
        <?=
        DetailView::widget([
            'model' => $model['data_taxPayerInfo'],
            'attributes' => [
                [
                    'attribute' => 'nameRu',
                    'label' => 'Наименование налогоплательщика',
                ],
                [
                    'attribute' => 'iinBin',
                    'label' => 'ИИН/БИН налогоплательщикаа',
                ],
                [
                    'attribute' => 'totalArrear',
                    'label' => 'Всего задолженности (тенге)',
                ],
            ],
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
            <?php foreach ($model['data_taxPayerInfo']['bccArrearsInfo'] as $key => $item) : ?>
            <tr>
                <td><?=$item['bcc'].' '.$item['bccNameRu']; ?></td>
                <td><?=$item['taxArrear']; ?></td>
                <td><?=$item['poenaArrear']; ?></td>
                <td><?=$item['percentArrear']; ?></td>
                <td><?=$item['fineArrear']; ?></td>
                <td><?=$item['totalArrear']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <?=Html::a('Сохранеить результат в БД', '/site/save?iin='.$model['data_iin']['iinBin'], ['class'=>'btn btn-primary']) ?>
    </div>
    
</div>

