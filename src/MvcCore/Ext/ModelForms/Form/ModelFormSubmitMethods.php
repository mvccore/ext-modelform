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

namespace MvcCore\Ext\ModelForms\Form;

use \MvcCore\Ext\ModelForms\IForm as IModelForm;

/**
 * @mixin \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form
 */
trait ModelFormSubmitMethods {
	
	/**
	 * Process standard low level submit process by parent `Submit()` method and
	 * then resolve result success state and call model instance manipulation method.
	 * @param  array $rawRequestParams optional
	 * @return array An array to list: `[$form->result, $form->data, $form->errors];`
	 */
	protected function submitModelForm (array & $rawRequestParams = []) {
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
				$this->logAndAddSubmitError($e, $clientErrorMessage, [
					isset($this->modelClassFullName) ? $this->modelClassFullName : NULL
				]);
			} catch (\Throwable $e) {
				$this->logAndAddSubmitError($e, $clientErrorMessage, [
					isset($this->modelClassFullName) ? $this->modelClassFullName : NULL
				]);
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
		if ($this->modelInstance === NULL) {
			$modelType = new \ReflectionClass($this->modelClassFullName);
			$this->modelInstance = $modelType->newInstanceWithoutConstructor();
		}
		$modelValues = $this->submitGetModelValues();
		if (count($modelValues) > 0) {
			$this->modelInstance->SetValues($modelValues, $this->modelPropsFlags);
			return $this->modelInstance->Insert($this->modelPropsFlags);
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Call model model instance `Update()` method.
	 * @return bool
	 */
	protected function submitEdit () {
		$modelValues = $this->submitGetModelValues();
		if (count($modelValues) > 0) {
			$this->modelInstance->SetValues($modelValues, $this->modelPropsFlags);
			return $this->modelInstance->Update($this->modelPropsFlags);
		} else {
			return FALSE;
		}
	}

	/**
	 * Call model model instance `Delete()` method.
	 * @return bool
	 */
	protected function submitDelete () {
		return $this->modelInstance->Delete($this->modelPropsFlags);
	}

	/**
	 * Filter all form submitted values only for values for model instance.
	 * @return array
	 */
	protected function submitGetModelValues () {
		$modelInstanceProps = array_keys($this->modelInstance->GetValues(
			$this->modelPropsFlags, TRUE
		));
		$modelValues = [];
		foreach ($modelInstanceProps as $modelInstanceProp) 
			if (array_key_exists($modelInstanceProp, $this->values)) 
				$modelValues[$modelInstanceProp] = $this->values[$modelInstanceProp];
		return $modelValues;
	}
}