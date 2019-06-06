<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use app\models\LoadDataBdParser;
use yii\web\Session;
use app\models\AInn;
use app\models\ATaxOrgInfo;
use app\models\ATaxPayerInfo;
use yii\data\ActiveDataProvider;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {

        $model = new \app\models\DataForm();

        if ($model->load(Yii::$app->request->post())) {
            $capcha = $this->get_capcha();
            //var_dump($capcha);
            //exit;
            $data = $this->get_dataIin($capcha[1], $capcha[0], $model->iin);

            $parser = new LoadDataBdParser($data, $model->iin);
            $data = $parser->load();

            $sesion = new Session();
            $sesion->set('data_site_iin', serialize($data));
            $this->redirect('/site/view_data');
        } else {
            
        }

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    public function actionView_data() {
        $sesion = new Session();
        $model = unserialize($sesion->get('data_site_iin'));

        return $this->render('_view_data', [
                    'model' => $model
        ]);
    }
    
    public function actionView_cart() {
        $model_iin = AInn::findOne((int)$_GET['id']);
        
        if (!$model_iin)
            throw new \yii\web\NotFoundHttpException('Данная карточка не найдена!');
        
        $model_ATaxOrgInfo = ATaxOrgInfo::findOne(['inn_id'=>$model_iin->id]);
        $model_ATaxPayerInfo = ATaxPayerInfo::findAll(['inn_id'=>$model_iin->id]);
        
        return $this->render('_view_cart',[
            'model_iin' => $model_iin,
            'model_ATaxOrgInfo' => $model_ATaxOrgInfo,
            'model_ATaxPayerInfo' => $model_ATaxPayerInfo,
        ]);
        
    }

    public function actionList() {

        $provider = new ActiveDataProvider([
            'query' => AInn::find(),
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('_list', [
                    'provider' => $provider
        ]);
    }

    public function actionSave() {
        $sesion = new Session();
        $model_data = unserialize($sesion->get('data_site_iin'));
        $parser = new LoadDataBdParser($model_data, $_GET['iin']);
        $parser->save_bd();
        $this->redirect('/site/list');
    }

    private function get_dataIin($capcha_id, $capcha_text, $iin) {
        $sourceUrl = 'http://kgd.gov.kz/apps/services/culs-taxarrear-search-web/rest/search';

        $client = new Client([
            'transport' => CurlTransport::className()
        ]);


        $json = \yii\helpers\Json::encode([
                    'captcha-id' => $capcha_id,
                    'captcha-user-value' => $capcha_text,
                    'iinBin' => $iin
        ]);
        $result = $client->createRequest()
                ->setMethod('post')
                ->setOptions(['timeout' => 460, 'connecttimeout' => 460])
                ->setUrl($sourceUrl)
                ->setHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
                    'Cookie' => 'has_js=1; kgd_spec_version=normal; kgd_spec_color=c1; kgd_spec_fontsize=font-small; kgd_spec_img=imageson; _ga=GA1.3.1510606970.1559646780; _gid=GA1.3.1532094714.1559646780; _ym_uid=1559646780728729519; _ym_d=1559646780; _ym_isad=1; _gat=1',
                    'Host' => 'kgd.gov.kz',
                    'Origin' => 'http://kgd.gov.kz',
                    'Referer' => 'http://kgd.gov.kz/ru/app/culs-taxarrear-search-web',
                ])
                ->setContent($json)
                ->send();

        return json_decode($result->content, true);
    }

    private function get_capcha() {
        $sourceUrl = 'https://kolesa-ural.ru/phantom.php?site=http://kgd.gov.kz/ru/app/culs-taxarrear-search-web';

        $client = new Client([
            'transport' => CurlTransport::className()
        ]);

        $result = $client->createRequest()
                ->setMethod('get')
                ->setUrl($sourceUrl)
                ->setOptions([
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_SSL_VERIFYHOST => false,
                ])
                ->send();

        $dom = new \PHPHtmlParser\Dom();
        $dom->load($result->content);
        $html = $dom->find('#captcha-culs-div');
        $src = $html->find('img')[0]->getAttribute('src');
        $capcha_id = $html->find('input')[0]->getAttribute('value');
        //echo $capcha_id;
        //die;

        $content = file_get_contents('http://kgd.gov.kz' . $src);
        file_put_contents(dirname(__DIR__) . '/campcha.jpg', $content);

        $capcha_txt = $this->anticapcha(dirname(__DIR__) . '/campcha.jpg');


        return [$capcha_txt, $capcha_id];
    }

    private function anticapcha($url_img) {
        include(dirname(__DIR__) . '/models/anticaptcha/anticaptcha.php');
        include(dirname(__DIR__) . '/models/anticaptcha/imagetotext.php');

        $api = new \ImageToText();
        $api->setVerboseMode(false);

        //your anti-captcha.com account key
        $api->setKey("5af392cb1a0f129a7b1d4bc5b2283112");

        $api->setFile($url_img);

        if (!$api->createTask()) {
            $api->debout("API v2 send failed - " . $api->getErrorMessage(), "red");
            return false;
        }

        $taskId = $api->getTaskId();


        if (!$api->waitForResult()) {
            //$api->debout("could not solve captcha", "red");
            //$api->debout($api->getErrorMessage());
            return NULL;
        } else {
            $captchaText = $api->getTaskSolution();
            return $captchaText;
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

}
