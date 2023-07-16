<?php

namespace xpbl4\typeahead;

use yii\web\AssetBundle;

/**
 * TypeaheadAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class TypeaheadAsset extends AssetBundle
{
    public $css = [
        'css/bootstrap-typeahead.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}
