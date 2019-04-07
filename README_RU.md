# Модуль скидки  [EN description](README.md)

Модуль скидок для "Pixelion CMS"

[![Latest Stable Version](https://poser.pugx.org/panix/mod-discounts/v/stable)](https://packagist.org/packages/panix/mod-discounts) [![Total Downloads](https://poser.pugx.org/panix/mod-discounts/downloads)](https://packagist.org/packages/panix/mod-discounts) [![Monthly Downloads](https://poser.pugx.org/panix/mod-discounts/d/monthly)](https://packagist.org/packages/panix/mod-discounts) [![Daily Downloads](https://poser.pugx.org/panix/mod-discounts/d/daily)](https://packagist.org/packages/panix/mod-discounts) [![Latest Unstable Version](https://poser.pugx.org/panix/mod-discounts/v/unstable)](https://packagist.org/packages/panix/mod-discounts) [![License](https://poser.pugx.org/panix/mod-discounts/license)](https://packagist.org/packages/panix/mod-discounts)


## Установка

Предпочтительным способом установки этого модуля является [composer](http://getcomposer.org/download/).

#### Либо запустите

```
php composer.phar require --prefer-dist panix/mod-discounts "*"
```

или добавить

```
"panix/mod-discounts": "*"
```

в раздел require `composer.json` файла.

#### Добавить в веб конфигурацию.
```
'modules' => [
    'discounts' => ['class' => 'panix\discounts\Module'],
],
```

#### Миграция
```
php yii migrate --migrationPath=vendor/panix/mod-discounts/migrations
```


## См. также
- [Pixelion CMS](https://pixelion.com.ua)
- [Модуль сравнения Github](https://https://github.com/andrtechno/mod-compare)
- [Модуль желайний Github](https://https://github.com/andrtechno/mod-wishlist)
- [Модуль карзина Github](https://https://github.com/andrtechno/mod-cart)
- [Модуль магазин Github](https://https://github.com/andrtechno/mod-shop)

------------------------

<i>Content Management System "Pixelion CMS"</i>  
[www.pixelion.com.ua](https://pixelion.com.ua)