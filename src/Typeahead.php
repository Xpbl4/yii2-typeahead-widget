<?php

namespace xpbl4\typeahead;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Typeahead renders a Twitter typeahead Bootstrap plugin.
 * @see http://twitter.github.io/typeahead.js/examples/
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\typeahead
 */
class Typeahead extends InputWidget
{
    /**
     * @var array the options for the Bootstrap TypeAhead JS plugin.
     * Please refer to the Bootstrap TypeAhead plugin Web page for possible options.
     * @see https://github.com/twitter/typeahead.js#usage
     */
    public $pluginOptions = [];
    /**
     * @var array the event handlers for the Bootstrap TypeAhead JS plugin.
     * Please refer to the Bootstrap TypeAhead plugin Web page for possible events.
     * @see https://github.com/twitter/typeahead.js/blob/master/doc/jquery_typeahead.md#custom-events
    */
    public $pluginEvents = [];
    /**
     * @var array the datasets object arrays of the Bootstrap TypeAhead Js plugin.
     * @see https://github.com/twitter/typeahead.js/blob/master/doc/jquery_typeahead.md#datasets
     */
    public $data = [];
    /**
     * @var array of [[Bloodhound]] instances. Please note, that the widget is just calling the object to return its
     * client script. In order to use its adapter, you will have to set it on the widget [[dataSets]] source option
     * and using the object instance as [[Bloodhound::getAdapter()]] method. This is required to be able to use multiple
     * datasets with bloodhound engine.
     * @see https://gist.github.com/jharding/9458772#file-remote-js
     */
    public $engines = [];

	/**
	 *
	 */
	public $helpers = [];

	public function init()
	{
		parent::init();

		if (!empty($this->engines)) {
			$_engines = [];
			foreach ($this->engines as $engine) {
				if (is_array($engine) && isset($engine['class'])) {
					$engine = Yii::createObject($engine);
				}
				$_engines[] = $engine;
			}

			$this->engines = $_engines;
		}
	}

	/**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }

    /**
     * Registers Twitter TypeAhead Bootstrap plugin and the related events
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

        TypeAheadPluginAsset::register($view);

        $id = $this->options['id'];

        $options = $this->pluginOptions !== false && !empty($this->pluginOptions)
            ? Json::encode($this->pluginOptions)
            : 'null';

	    $data_sets = [];
        foreach($this->data as $_data) {
            if(empty($_data)) continue;
            $data_sets[] = Json::encode($_data);
        }

	    $data_sets = !empty($data_sets)
            ? implode(", ", $data_sets)
            : '{}';

        foreach ($this->engines as $bloodhound) {
            if ($bloodhound instanceof Bloodhound) {
                $js[] = $bloodhound->getClientScript();
            }
        }

        foreach ($this->helpers as $helper) {
            if ($helper instanceof \yii\web\JsExpression) $js[] = $helper;
        }

        $js[] = "jQuery('#$id').typeahead($options, $data_sets);";
        foreach ($this->pluginEvents as $eventName => $handler) {
            $js[] = "jQuery('#$id').on('$eventName', $handler);";
        }

        $view->registerJs(implode("\n", $js));
    }
}
