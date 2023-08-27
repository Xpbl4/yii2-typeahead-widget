# Yii2 Typeahead widget

[![Latest Version](https://img.shields.io/github/tag/Xpbl4/yii2-typeahead-widget.svg?style=flat-square&label=release)](https://github.com/Xpbl4/yii2-typeahead-widget/releases)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/Xpbl4/yii2-typeahead-widget.svg?style=flat-square)](https://packagist.org/packages/Xpbl4/yii2-typeahead-widget)

Typeahead widget allows to create typeahead dropdown lists 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xpbl4/yii2-typeahead-widget "*"
```

or add

```
"xpbl4/yii2-typeahead-widget": "*"
```

to the require section of your `composer.json` file.

## Usage

Once the extension is installed, simply use it in your code by:

```php
<?php
$engine = new \xpbl4\typeahead\Bloodhound([
    'name' => 'countriesEngine',
    'pluginOptions' => [
        'datumTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
        'queryTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
        'remote' => [
            'url' => Url::to(['country/autocomplete', 'query'=>'QRY']),
        ]
    ]
]);

echo \xpbl4\typeahead\Typeahead::widget([
    'id' => 'exampleInput',
    'name' => 'test',
    'options' => ['class' => 'form-control', 'prompt' => 'Start type to find...'],
    'engines' => [ $engine ],
    'pluginOptions' => [
        'highlight' => true,
        'minLength' => 3
    ],
    'pluginEvents' => [
        'typeahead:selected' => 'function (e, o) { console.log("event \'selected\' occured on " + o.value + "."); }'
    ],
    'data' => [
        [
            'name' => 'countries',
            'displayKey' => 'value',
            'source' => $engine->getAdapterScript()
        ]
    ]
]);
?>
```

Note the use of the custom `wildcard`. It is required as if we use `typeahead.js` default's wildcard (`%QUERY`), Yii2 will automatically URL encode it thus making the wrong configuration for token replacement.

The results need to be JSON encoded as specified on the [plugin documentation](https://github.com/twitter/typeahead.js#datum). The following is an example of a custom `Action` class that you could plug to any `Controller`:

And how to add action on your `Controller` class:

```php
public function actions()
{
	return [
		'autocomplete' => [
			'class' => 'xpbl4\typeahead\actions\AutocompleteAction',
			'model' => Country::tableName(),
			'field' => 'name'
		]
	];
}
```
