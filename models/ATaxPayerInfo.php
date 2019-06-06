<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "taxPayerInfo".
 *
 * @property int $id
 * @property int $inn_id
 * @property string $bccNameRu
 * @property string $bccNameKz
 * @property string $bcc
 * @property string $taxArrear Задолженность по платежам, учет по которым ведется в органах государственных доходов
 * @property string $poenaArrear Задолженность по сумме пени
 * @property string $percentArrear Задолженность по сумме процентов
 * @property string $fineArrear Задолженность по сумме штрафа
 * @property string $totalArrear Всего задолженности
 *
 * @property Inn $inn
 */
class ATaxPayerInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'taxPayerInfo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inn_id', 'bccNameRu', 'bccNameKz'], 'required'],
            [['inn_id'], 'default', 'value' => null],
            [['inn_id'], 'integer'],
            [['bccNameRu', 'bccNameKz', 'taxArrear', 'poenaArrear', 'percentArrear', 'fineArrear', 'totalArrear'], 'string', 'max' => 255],
            [['bcc'], 'string', 'max' => 150],
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
            'bccNameRu' => 'Bcc Name Ru',
            'bccNameKz' => 'Bcc Name Kz',
            'bcc' => 'Bcc',
            'taxArrear' => 'Задолженность по платежам, учет по которым ведется в органах государственных доходов',
            'poenaArrear' => 'Задолженность по сумме пени',
            'percentArrear' => 'Задолженность по сумме процентов',
            'fineArrear' => 'Задолженность по сумме штрафа',
            'totalArrear' => 'Всего задолженности',
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
