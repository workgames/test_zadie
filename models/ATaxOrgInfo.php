<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "taxOrgInfo".
 *
 * @property int $id
 * @property int $inn_id
 * @property string $title_ru
 * @property string $title_kz
 * @property string $charCode
 * @property string $reportAcrualDate
 * @property string $totalArrear Всего задолженности (тенге)
 * @property string $totalTaxArrear Итого задолженности в бюджет
 * @property string $pensionContributionArrear Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам
 * @property string $socialHealthInsuranceArrear Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование
 * @property string $socialContributionArrear Задолженность по социальным отчислениям
 *
 * @property Inn $inn
 */
class ATaxOrgInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'taxOrgInfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inn_id', 'title_ru', 'title_kz', 'reportAcrualDate'], 'required'],
            [['inn_id'], 'default', 'value' => null],
            [['inn_id'], 'integer'],
            [['reportAcrualDate'], 'safe'],
            [['title_ru', 'title_kz', 'totalArrear', 'totalTaxArrear', 'pensionContributionArrear', 'socialHealthInsuranceArrear', 'socialContributionArrear'], 'string', 'max' => 255],
            [['charCode'], 'string', 'max' => 150],
            [['inn_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inn::className(), 'targetAttribute' => ['inn_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn_id' => 'Inn ID',
            'title_ru' => 'Наименование(Ru)',
            'title_kz' => 'Наименование(Kz)',
            'charCode' => 'Char Code',
            'reportAcrualDate' => 'По состоянию на',
            'totalArrear' => 'Всего задолженности (тенге)',
            'totalTaxArrear' => 'Итого задолженности в бюджет',
            'pensionContributionArrear' => 'Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам',
            'socialHealthInsuranceArrear' => 'Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование',
            'socialContributionArrear' => 'Задолженность по социальным отчислениям',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInn()
    {
        return $this->hasOne(Inn::className(), ['id' => 'inn_id']);
    }
}
