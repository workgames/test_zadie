<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of LoadDataBdParser
 *
 * @author vasilievvm
 */
use app\models\AInn;
use app\models\ATaxOrgInfo;
use app\models\ATaxPayerInfo;

class LoadDataBdParser {

    private $data;
    private $iin;

    public function __construct(Array $data, $iin) {
        $this->data = $data;
        $this->iin = $iin;
    }

    /**
     * Загрузка полученных данных
     * @param array $data
     */
    public function load() {
        return $this->get_data();
    }

    public function save_bd() {
        $model_inn = AInn::findOne(['iin' => $this->iin]);
        $is_new_iin = false;

        if (!$model_inn) {
            $model_inn = new AInn();
            $is_new_iin = true;
        }

        $model_inn->iin = $this->data['data_iin']['iinBin'];
        $model_inn->name_ru = $this->data['data_iin']['nameRu'];
        $model_inn->name_kz = $this->data['data_iin']['nameKk'];
        $model_inn->totalTaxArrear = $this->data['data_iin']['totalTaxArrear'];
        $model_inn->totalArrear = $this->data['data_iin']['totalArrear'];
        $model_inn->bccTotalArrear = $this->data['data_taxPayerInfo']['totalArrear'];
        $model_inn->pensionContributionArrear = $this->data['data_iin']['pensionContributionArrear'];
        $model_inn->socialHealthInsuranceArrear = $this->data['data_iin']['socialHealthInsuranceArrear'];
        $model_inn->socialContributionArrear = $this->data['data_iin']['socialContributionArrear'];
        $model_inn->actual_date = date('Y-m-d H:i:s', $this->data["data_taxOrgInfo"]['reportAcrualDate'] / 1000);
        $model_inn->updated_at = date('Y-m-d H:i:s');
        $model_inn->save(false);

        if (!$model_inn->isNewRecord) {
            ATaxOrgInfo::deleteAll(['inn_id' => $model_inn->id]);
            ATaxPayerInfo::deleteAll(['inn_id' => $model_inn->id]);
        }

        $ATaxOrgInfo = new ATaxOrgInfo();
        $ATaxOrgInfo->inn_id = $model_inn->id;
        $ATaxOrgInfo->title_ru = $this->data["data_taxOrgInfo"]['nameRu'];
        $ATaxOrgInfo->title_kz = $this->data["data_taxOrgInfo"]['nameKk'];
        $ATaxOrgInfo->charCode = $this->data["data_taxOrgInfo"]['charCode'];
        $ATaxOrgInfo->reportAcrualDate = date('Y-m-d H:i:s', $this->data["data_taxOrgInfo"]['reportAcrualDate'] / 1000);
        $ATaxOrgInfo->totalArrear = $this->data["data_taxOrgInfo"]['totalArrear'];
        $ATaxOrgInfo->totalTaxArrear = $this->data["data_taxOrgInfo"]['totalTaxArrear'];
        $ATaxOrgInfo->pensionContributionArrear = $this->data["data_taxOrgInfo"]['pensionContributionArrear'];
        $ATaxOrgInfo->socialHealthInsuranceArrear = $this->data["data_taxOrgInfo"]['socialContributionArrear'];
        $ATaxOrgInfo->socialContributionArrear = $this->data["data_taxOrgInfo"]['socialHealthInsuranceArrear'];
        $ATaxOrgInfo->save(false);



        foreach ($this->data["data_taxPayerInfo"]["bccArrearsInfo"] as $item) {
            $ATaxPayerInfo = new ATaxPayerInfo();
            $ATaxPayerInfo->inn_id = $model_inn->id;
            $ATaxPayerInfo->bccNameRu = $item['bccNameRu'];
            $ATaxPayerInfo->bccNameKz = $item['bccNameKz'];
            $ATaxPayerInfo->bcc = $item['bcc'];
            $ATaxPayerInfo->taxArrear = $item['taxArrear'];
            $ATaxPayerInfo->poenaArrear = $item['poenaArrear'];
            $ATaxPayerInfo->percentArrear = $item['percentArrear'];
            $ATaxPayerInfo->fineArrear = $item['fineArrear'];
            $ATaxPayerInfo->totalArrear = $item['totalArrear'];
            $ATaxPayerInfo->save(false);
        }
    }

    private function get_data() {
        $data_iin = [];
        $data_taxOrgInfo = [];
        $data_taxPayerInfo = [];


        foreach ($this->data as $key => $item_iin) {
            if ($key != 'taxOrgInfo')
                $data_iin[$key] = $item_iin;
        }


        foreach ($this->data['taxOrgInfo'][0] as $key_org => $item_taxOrgInfo) {
            if ($key_org != 'taxPayerInfo')
                $data_taxOrgInfo[$key_org] = $item_taxOrgInfo;
        }

        foreach ($this->data['taxOrgInfo'][0]['taxPayerInfo'][0] as $key_bccArrearsInfo => $value) {
            $data_taxPayerInfo[$key_bccArrearsInfo] = $value;
        }


        return ['data_iin' => $data_iin, 'data_taxOrgInfo' => $data_taxOrgInfo, 'data_taxPayerInfo' => $data_taxPayerInfo];
    }

}
