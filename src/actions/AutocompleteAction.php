<?php
/**
 * @author: Sergey Mashkov (serge@asse.com)
 * Date: 7/16/23 1:08 PM
 * Project: asse-db-template
 */

namespace xpbl4\typeahead\actions;

use yii\base\InvalidConfigException;

class AutocompleteAction extends \yii\base\Action
{
	public $model;

	public $field;

	public $resultFields;

	public $resultOrder = ['value' => SORT_ASC];

	public $clientIdGetParamName = 'query';

	public $searchPrefix = '';

	public $searchSuffix = '%';

	public $params;

	public function init()
	{
		if ($this->model === null) {
			throw new  InvalidConfigException(get_class($this) . '::$model must be defined.');
		}
		if ($this->field === null) {
			throw new  InvalidConfigException(get_class($this) . '::$field must be defined.');
		}
		if ($this->resultFields === null) $this->resultFields = ['value' => $this->field];
		if ($this->params === null) $this->params = 'params';

		parent::init();
	}

	public function run()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		/** @var \yii\db\ActiveRecord $model */
		$model = new $this->model;
		$params = \Yii::$app->request->get($this->params, []);

		$_term = \Yii::$app->request->get($this->clientIdGetParamName, '');
		$value = $this->searchPrefix . $_term. $this->searchSuffix;

		$query = $model->find()
			->select($this->resultFields)
			//->joinWith(['country'])
			->where(['like', $this->field, $value, false]);
		if (!empty($params)) {
			$model->setAttributes($params);
			if ($model->validate(array_keys($params))) $query->andFilterWhere($params);
			else return ['error' => $model->getErrors()];
		}

		$rows = $query->orderBy($this->resultOrder)
			->limit(20)
			->asArray()
			->all();

//		$rows = \Yii::$app->db
//			->createCommand("SELECT {$this->field} AS value FROM {$model->tableName()} WHERE {$this->field} LIKE :field ORDER BY {$this->field} LIMIT 20")
//			->bindValues([':field' => $value])
//			->queryAll();

		return $rows;
	}
}