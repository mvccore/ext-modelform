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
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_INITIALIZED) 
			$this->Init(TRUE);
		if ($this->dispatchState < \MvcCore\IController::DISPATCH_STATE_PRE_DISPATCHED) 
			$this->PreDispatch(TRUE);
		if (!$rawRequestParams) 
			$rawRequestParams = $this->request->GetParams(FALSE);
		$this->SubmitSetStartResultState($rawRequestParams);
		$deleting = FALSE;
		if ($this->SubmitValidateMaxPostSizeIfNecessary()) {
			$deleting = $this->result === IModelForm::RESULT_SUCCESS_DELETE;
			if ($deleting) 
				foreach ($this->fields as $field) 
					$field->SetRequired(FALSE);
			$this
				->SubmitCsrfTokens($rawRequestParams)
				->SubmitAllFields($rawRequestParams);
		}
		if ($deleting)
			$this->result = IModelForm::RESULT_SUCCESS_DELETE;
		if (
			$this->result >= IModelForm::RESULT_SUCCESS_CREATE && 
			$this->result <= IModelForm::RESULT_SUCCESS_DELETE
		) {
			$clientDefaultErrorMessage = NULL;
			try {
				if ($this->isModelNew() && $this->result === IModelForm::RESULT_SUCCESS_CREATE) {
					$clientDefaultErrorMessage = $this->defaultClientErrorMessages['create'];
					$this->submitCreate();
				} else if ($this->modelInstance !== NULL) {
					if ($this->result === IModelForm::RESULT_SUCCESS_EDIT) {
						$clientDefaultErrorMessage = $this->defaultClientErrorMessages['edit'];
						$this->submitEdit();
					} else if ($this->result === IModelForm::RESULT_SUCCESS_DELETE) {
						$clientDefaultErrorMessage = $this->defaultClientErrorMessages['delete'];
						$this->submitDelete();
					}
				}
			} catch (\Exception $e) { // backward compatibility
				$this->logAndAddSubmitError($e, $clientDefaultErrorMessage, [
					isset($this->modelClassFullName) ? $this->modelClassFullName : NULL
				]);
			} catch (\Throwable $e) {
				$this->logAndAddSubmitError($e, $clientDefaultErrorMessage, [
					isset($this->modelClassFullName) ? $this->modelClassFullName : NULL
				]);
			}
		}
		$values = $this->values;
		$errors = $this->errors;
		if ($this->result === \MvcCore\Ext\IForm::RESULT_ERRORS) {
			$this->SaveSession();
		} else {
			$this->ClearSession();
		}
		return [
			$this->result,
			$values,
			$errors,
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