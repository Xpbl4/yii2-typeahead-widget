<?php

namespace xpbl4\typeahead;

use yii\web\AssetBundle;

/**
 * TypeaheadPluginAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class TypeaheadPluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/typeahead.js/dist';
    public $js = [
        'typeahead.bundle.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
	    'xpbl4\typeahead\TypeaheadAsset',
	    'xpbl4\typeahead\HandlebarsPluginAsset',
    ];
}
