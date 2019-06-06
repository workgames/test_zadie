<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inn".
 *
 * @property int $id
 * @property string $iin
 * @property string $name_ru
 * @property string $name_kz
 * @property string $totalTaxArrear Итого задолженности в бюджет
 * @property string $totalArrear Всего задолженности (тенге)
 * @property string $pensionContributionArrear Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам
 * @property string $socialHealthInsuranceArrear Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование
 * @property string $socialContributionArrear Задолженность по социальным отчислениям
 * @property string $created_at
 * @property int $updated_at
 *
 * @property TaxOrgInfo[] $taxOrgInfos
 * @property TaxPayerInfo[] $taxPayerInfos
 */
class AInn extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'inn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['iin', 'name_ru', 'name_kz', 'created_at'], 'required'],
            [['actual_date', 'updated_at'], 'safe'],
            [['bccTotalArrear', 'iin', 'name_ru', 'name_kz', 'totalTaxArrear', 'totalArrear', 'pensionContributionArrear', 'socialHealthInsuranceArrear', 'socialContributionArrear'], 'string', 'max' => 255],
            [['iin'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'iin' => 'ИИН/БИН налогоплательщика',
            'name_ru' => 'Наименование налогоплательщика (RU)',
            'name_kz' => 'Наименование налогоплательщика (KZ)',
            'totalTaxArrear' => 'Итого задолженности в бюджет',
            'totalArrear' => 'Всего задолженности (тенге)',
            'pensionContributionArrear' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'socialHealthInsuranceArrear' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'socialContributionArrear' => 'Задолженность по социальным отчислениям',
            'actual_date' => 'По состоянию на',
            'updated_at' => 'Дата обновления',
            'bccTotalArrear' => 'Всего задолженности (тенге)'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxOrgInfos() {
        return $this->hasMany(TaxOrgInfo::className(), ['inn_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxPayerInfos() {
        return $this->hasMany(TaxPayerInfo::className(), ['inn_id' => 'id']);
    }

}
