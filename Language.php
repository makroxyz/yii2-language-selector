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
     * @var string query param name 
     */
    public $queryParam = 'lang';
    
    private $_key;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (!isset(Yii::$app->params['languages'])) {
            throw new \yii\base\InvalidConfigException("You must define Yii::\$app->params['languages'] array");
        }
        
        $request = Yii::$app->getRequest();
        $lang = $request->get($this->queryParam);
        $this->_key = 'language.' . $this->queryParam;
        if ($lang !== null) {
            Yii::$app->session->set($this->_key, $lang);
            Yii::$app->language = $lang;
        } elseif (Yii::$app->session->get($this->_key) === null) {
            $preferredLang = $request->getPreferredLanguage(array_keys(Yii::$app->params['languages']));
            if ($preferredLang !== null) {
                Yii::$app->session->set($this->_key, $preferredLang);
                Yii::$app->language = $preferredLang;
            } else {
                 Yii::$app->session->set($this->_key, Yii::$app->language);
            }
        } else {
            Yii::$app->language = Yii::$app->session->get($this->_key);
        }
    }
    
    public function url($lang) 
    {
        $resolve = Yii::$app->request->resolve();
        $route = "/" . $resolve[0];
        $params = $resolve[1];
        
        $params['lang'] = $lang;
        return Url::toRoute(array_merge([$route], $params));
    }
    
    public function getMenuItems()
    {
        $subItems = [];
        foreach (Yii::$app->params['languages'] as $lang => $desc) {
            if (Yii::$app->session->get($this->_key) == $lang) {
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
