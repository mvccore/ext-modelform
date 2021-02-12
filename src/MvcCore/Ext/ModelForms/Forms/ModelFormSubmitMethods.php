<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\ModelForms\Forms;

use \MvcCore\Ext\ModelForms\IForm as IModelForm;

trait ModelFormSubmitMethods {
	
	/**
	 * Process standard low level submit process by parent `Submit()` method and
	 * then resolve result success state and call model instance manipulation method.
	 * @param  array $rawRequestParams optional
	 * @return array An array to list: `[$form->result, $form->data, $form->errors];`
	 */
	protected function submitModelForm (array & $rawRequestParams = []) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		parent::Submit($rawRequestParams);
		if (
			$this->result >= IModelForm::RESULT_SUCCESS_CREATE && 
			$this->result <= IModelForm::RESULT_SUCCESS_DELETE
		) {
			$clientErrorMessage = NULL;
			try {
				if ($this->isModelNew() && $this->result === IModelForm::RESULT_SUCCESS_CREATE) {
					$clientErrorMessage = "Error when creating new database record `{0}`.";
					$this->submitCreate();
				} else if ($this->modelInstance !== NULL) {
					if ($this->result === IModelForm::RESULT_SUCCESS_EDIT) {
						$clientErrorMessage = "Error when saving database record `{0}`.";
						$this->submitEdit();
					} else if ($this->result === IModelForm::RESULT_SUCCESS_DELETE) {
						$clientErrorMessage = "Error when removing database record `{0}`.";
						$this->submitDelete();
					}
				}
			} catch (\Exception $e) { // backward compatibility
				$this->logAndAddSubmitError($e, $clientErrorMessage, [$this->modelClass]);
			} catch (\Throwable $e) {
				$this->logAndAddSubmitError($e, $clientErrorMessage, [$this->modelClass]);
			}
		}
		return [
			$this->result,
			$this->values,
			$this->errors,
		];
	}

	/**
	 * Create model instance if necessary by PHP reflection without calling constructor,
	 * Than call model model instance `Insert()` method.
	 * @return bool
	 */
	protected function submitCreate () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		if ($this->modelInstance === NULL) {
			$modelType = new \ReflectionClass($this->modelClass);
			$this->modelInstance = $modelType->newInstanceWithoutConstructor();
		}
		return $this->modelInstance->Insert($this->modelPropsFlags);
	}
	
	/**
	 * Call model model instance `Update()` method.
	 * @return bool
	 */
	protected function submitEdit () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->modelInstance->Update($this->modelPropsFlags);
	}

	/**
	 * Call model model instance `Delete()` method.
	 * @return bool
	 */
	protected function submitDelete () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->modelInstance->Delete($this->modelPropsFlags);
	}
}