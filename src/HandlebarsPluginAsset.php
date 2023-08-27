<?php

namespace xpbl4\typeahead;

use yii\web\AssetBundle;

/**
 * HandlebarsPluginAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class HandlebarsPluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/components/handlebars.js/';
    public $js = [
	    'handlebars.js',
    ];
}
