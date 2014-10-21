<?php

namespace makroxyz\language;

use Yii;
use yii\base\Component;
use yii\helpers\Url;

/**
 * Language selector component
 * You must define available languages in Yii::$app->params['languages'] as code => description
 * [
 *    'it' => 'Italiano',
 *    'en' => 'English',
 * ]
 */
class Language extends Component
{
    /**
     * @var string template for menu label.
     * code - language code
     * desc - language description
     */
    public $menuTemplate = '{desc}';
    
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (!isset(Yii::$app->params['languages'])) {
            throw new \yii\base\InvalidConfigException("You must define Yii::$app->params['languages'] array");
        }
        
        $request =Yii::$app->getRequest();
        $lang = $request->get('lang');
        if ($lang !== null) {
            Yii::$app->session->set('lang', $lang);
            Yii::$app->language = $lang;
        } elseif (Yii::$app->session->get('lang') === null) {
            $preferredLang = $request->getPreferredLanguage(array_keys(Yii::$app->params['languages']));
            if ($preferredLang !== null) {
                Yii::$app->session->set('lang', $preferredLang);
                Yii::$app->language = $preferredLang;
            } else {
                 Yii::$app->session->set('lang', Yii::$app->language);
            }
        } else {
            Yii::$app->language = Yii::$app->session->get('lang');
        }
    }
    
    public function url($lang) 
    {
        $resolve = Yii::$app->request->resolve();
        $route = $resolve[0];
        $params = $resolve[1];
        
        $params['lang'] = $lang;
        return Url::toRoute(array_merge([$route], $params));
    }
    
    public function getMenuItems()
    {
        $subItems = [];
        foreach (Yii::$app->params['languages'] as $lang => $desc) {
            if (Yii::$app->session->get('lang') == $lang) {
                $item = ['label' => strtr($this->menuTemplate, [
                    '{lang}' => $lang,
                    '{desc}' => $desc,
                ]), 'url' => '#'];
            } else {
                $subItems[] = ['label' => $desc, 'url' => $this->url($lang)];
            }
        }
        $item['items'] = $subItems;
        return $item;
    }
}
