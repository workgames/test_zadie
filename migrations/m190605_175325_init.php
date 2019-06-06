<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190605_175325_init
 */
class m190605_175325_init extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //Общая информация
        $this->createTable('inn', [
            'id' => $this->primaryKey(),
            'iin' => $this->string()->notNull()->unique(),
            'name_ru' => $this->string()->notNull(),
            'name_kz' => $this->string()->notNull(),
            'bccTotalArrear' => $this->string()->comment('Всего задолженностей по налогоплательщику и его структурным подразделениям')->defaultValue(0),
            'totalTaxArrear' => $this->string()->comment('Итого задолженности в бюджет')->defaultValue(0),
            'totalArrear' => $this->string()->comment('Всего задолженности (тенге)')->defaultValue(0),
            'pensionContributionArrear' => $this->string()->comment('Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам')->defaultValue(0),
            'socialHealthInsuranceArrear' => $this->string()->comment('Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование')->defaultValue(0),
            'socialContributionArrear' => $this->string()->comment('Задолженность по социальным отчислениям')->defaultValue(0),
            'actual_date' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        //Таблица задолженностей по органам государственных доходов
        $this->createTable('taxOrgInfo', [
            'id' => $this->primaryKey(),
            'inn_id' => $this->integer(11)->notNull(),
            'title_ru' => $this->string()->notNull(),
            'title_kz' => $this->string()->notNull(),
            'charCode' => $this->string(150)->null(),
            'reportAcrualDate' => $this->dateTime()->notNull(),
            'totalArrear' => $this->string()->comment('Всего задолженности (тенге)')->defaultValue(0),
            'totalTaxArrear' => $this->string()->comment('Итого задолженности в бюджет')->defaultValue(0),
            'pensionContributionArrear' => $this->string()->comment('Задолженность по обязательным пенсионным взносам, обязательным профессиональным пенсионным взносам')->defaultValue(0),
            'socialHealthInsuranceArrear' => $this->string()->comment('Задолженность по отчислениям и (или) взносам на обязательное социальное медицинское страхование')->defaultValue(0),
            'socialContributionArrear' => $this->string()->comment('Задолженность по социальным отчислениям')->defaultValue(0),
        ]);

        $this->createIndex(
                'innid-taxOrgInfo', 'taxOrgInfo', 'inn_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
                'fk-innid-taxOrgInfo', 'taxOrgInfo', 'inn_id', 'inn', 'id', 'CASCADE'
        );
        
        
        //Таблица задолженностей по налогоплательщику и его структурным подразделениям
        $this->createTable('taxPayerInfo', [
            'id' => $this->primaryKey(),
            'inn_id' => $this->integer(11)->notNull(),
            'bccNameRu' => $this->string()->notNull(),
            'bccNameKz' => $this->string()->notNull(),
            'bcc' => $this->string(150)->null(),
            'taxArrear' => $this->string()->comment('Задолженность по платежам, учет по которым ведется в органах государственных доходов')->defaultValue(0),
            'poenaArrear' => $this->string()->comment('Задолженность по сумме пени')->defaultValue(0),
            'percentArrear' => $this->string()->comment('Задолженность по сумме процентов')->defaultValue(0),
            'fineArrear' => $this->string()->comment('Задолженность по сумме штрафа')->defaultValue(0),
            'totalArrear' => $this->string()->comment('Всего задолженности')->defaultValue(0),
        ]);   
        
        
        $this->createIndex(
                'innid-taxPayerInfo', 'taxPayerInfo', 'inn_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
                'fk-innid-taxPayerInfo', 'taxPayerInfo', 'inn_id', 'inn', 'id', 'CASCADE'
        );        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('taxPayerInfo');
        $this->dropTable('taxOrgInfo');
        $this->dropTable('inn');
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190605_175325_init cannot be reverted.\n";

      return false;
      }
     */
}
