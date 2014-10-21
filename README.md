yii2-language-selector
======================
Component for Yii2 language selector

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist makroxyz/yii2-language-selector "*"
```

or add

```
"makroxyz/yii2-language-selector": "*"
```

to the require section of your `composer.json` file.


Usage
-----
Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['lang'],
    'components' => [
        'lang' => 'makroxyz\language\Language',
        // ...
    ],
    ...
];
```

then you can call 
```php
Yii::$app->lang->getMenuItems()
```
to obtain language dropdown items for use in your yii\widgets\Menu or yii\bootstrap\Nav
