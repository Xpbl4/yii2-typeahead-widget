<?php

namespace xpbl4\typeahead;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Bloodhound is a helper class to configure Bloodhound suggestion engines.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\typeahead
 */
class Bloodhound extends BaseObject
{
    /**
     * @var string the engine js name
     */
    public $name;
    /**
     * @var array the configuration of Bloodhound suggestion engine.
     * @see https://github.com/twitter/typeahead.js/blob/master/doc/bloodhound.md#options
     */
    public $pluginOptions = [];

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->name === null) {
            throw new InvalidConfigException("'name' cannot be null.");
        }
        parent::init();
    }

    /**
     * Returns the engine adapter. To be used to configure [[TypeAhead::dataSets]] `source` option.
     * @return JsExpression
     */
    public function getAdapterScript()
    {
        return new JsExpression("{$this->name}.ttAdapter()");
    }

    /**
     * Returns the javascript initialization code
     * @return string
     */
    public function getClientScript()
    {
	    $pluginOptions = ArrayHelper::merge([], $this->pluginOptions);
        return "var {$this->name} = new Bloodhound(".Json::encode($pluginOptions).");\n{$this->name}.initialize();";
    }
}
