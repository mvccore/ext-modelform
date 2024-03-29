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
		if (!$this->DispatchStateCheck(static::DISPATCH_STATE_SUBMITTED, TRUE))
			return [$this->result, $this->values, $this->errors];
		$deleting = FALSE;
		list(
			$submitWithParams, $rawRequestParams
		) = $this->submitModelFormCompleteParams($rawRequestParams);
		$result = $this->SubmitSetStartResultState($rawRequestParams)->result;
		$deleting = ($result & IModelForm::RESULT_SUCCESS_DELETE) != 0;
		if ($deleting) 
			foreach ($this->fields as $field) $field->SetRequired(FALSE);
		if ($submitWithParams) {
			if ($deleting) foreach ($this->fields as $field) $field->SetRequired(FALSE);
			$this->SubmitAllFields($rawRequestParams);
		} else if ($this->SubmitValidateMaxPostSizeIfNecessary()) {
			$this->application->ValidateCsrfProtection();
			$this->SubmitCsrfTokens($rawRequestParams);// deprecated, but working in all browsers
			if (!$this->application->GetTerminated())
				$this->SubmitAllFields($rawRequestParams);
		}
		if ($deleting && $this->result !== self::RESULT_ERRORS) {
			$this->errors = [];
			$this->result = $result;
		}
		if (!$this->application->GetTerminated()) {
			if ($deleting)
				$this->result = IModelForm::RESULT_SUCCESS_DELETE;
			if ($this->submitHasResultManipulationFlag($this->result)) 
				$this->submitModelFormExecManipulations();
			if ($this->result === \MvcCore\Ext\IForm::RESULT_ERRORS) {
				$this->SaveSession();
			} else {
				$this->result |= self::RESULT_SUCCESS;
				$this->ClearSession();
			}
		}
		return [$this->result, $this->values, $this->errors];
	}

	/**
	 * Get raw form submit values by form method if given array is empty.
	 * @param  array $rawRequestParams optional
	 * @return array An array to list: `[$submitWithParams, $rawRequestParams];`
	 */
	protected function submitModelFormCompleteParams (array & $rawRequestParams = []) {
		$submitWithParams = count($rawRequestParams) > 0;
		if (!$submitWithParams) {
			$sourceType = $this->method === \MvcCore\Ext\IForm::METHOD_GET
				? \MvcCore\IRequest::PARAM_TYPE_QUERY_STRING
				: \MvcCore\IRequest::PARAM_TYPE_QUERY_STRING | \MvcCore\IRequest::PARAM_TYPE_INPUT; // sometimes there could be mixed GET and POST
			$paramsKeys = array_keys($this->fields);
			if ($this->csrfEnabled && count($this->csrfValue) > 0)
				$paramsKeys[] = $this->csrfValue[0];
			$rawRequestParams = $this->request->GetParams(
				FALSE, $paramsKeys, $sourceType
			);
		}
		return [$submitWithParams, $rawRequestParams];
	}

	/**
	 * Execute submit manipulation methods.
	 * @return void
	 */
	protected function submitModelFormExecManipulations () {
		$changed = FALSE;
		$clientDefaultErrorMessage = NULL;
		try {
			if ($this->isModelNew() && ($this->result & IModelForm::RESULT_SUCCESS_CREATE) != 0) {
				$clientDefaultErrorMessage = $this->defaultClientErrorMessages['create'];
				$changed = $this->submitCreate();
			} else if (($this->result & IModelForm::RESULT_SUCCESS_EDIT) != 0) {
				$clientDefaultErrorMessage = $this->defaultClientErrorMessages['edit'];
				$changed = $this->submitEdit();
			} else if (($this->result & IModelForm::RESULT_SUCCESS_DELETE) != 0) {
				$clientDefaultErrorMessage = $this->defaultClientErrorMessages['delete'];
				$changed = $this->submitDelete();
			} else if (($this->result & IModelForm::RESULT_SUCCESS_COPY) != 0) {
				$clientDefaultErrorMessage = $this->defaultClientErrorMessages['copy'];
				$changed = $this->submitCopy();
			}
			if ($this->result !== \MvcCore\Ext\IForm::RESULT_ERRORS) {
				$this->result |= \MvcCore\Ext\IForm::RESULT_SUCCESS | ($changed 
					? IModelForm::RESULT_SUCCESS_MODEL_CHANGED
					: IModelForm::RESULT_SUCCESS_MODEL_NOT_CHANGED);
			}
		} catch (\Throwable $e) {
			$this->logAndAddSubmitError($e, $clientDefaultErrorMessage, [
				isset($this->modelClassFullName) ? $this->modelClassFullName : NULL
			]);
		}
	}

	/**
	 * Create model instance if necessary by PHP reflection without calling constructor,
	 * Than call model instance `Insert()` method.
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
	 * Call model instance `Update()` method.
	 * @return bool
	 */
	protected function submitEdit () {
		if ($this->modelInstance === NULL)
			throw new \RuntimeException("Model instance to edit not initialized.");
		$modelValues = $this->submitGetModelValues();
		if (count($modelValues) > 0) {
			$this->modelInstance->SetValues($modelValues, $this->modelPropsFlags);
			return $this->modelInstance->Update($this->modelPropsFlags);
		} else {
			return FALSE;
		}
	}

	/**
	 * Call model instance `Delete()` method.
	 * @return bool
	 */
	protected function submitDelete () {
		if ($this->modelInstance === NULL)
			throw new \RuntimeException("Model instance to delete not initialized.");
		return $this->modelInstance->Delete($this->modelPropsFlags);
	}
	
	/**
	 * Call `__clone()`d model instance `Insert()` method.
	 * @return bool
	 */
	protected function submitCopy () {
		if ($this->modelInstance === NULL)
			throw new \RuntimeException("Model instance to copy not initialized.");
		$modelValues = $this->submitGetModelValues();
		if (count($modelValues) > 0)
			$this->modelInstance->SetValues($modelValues, $this->modelPropsFlags);
		$newInstance = clone $this->modelInstance;
		$result = $newInstance->Insert($this->modelPropsFlags);
		$this->modelInstance = $newInstance;
		return $result;
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
		foreach ($modelInstanceProps as $modelInstanceProp) {
			$field = $this->GetField($modelInstanceProp);
			if (
				$field === NULL || 
				!array_key_exists($modelInstanceProp, $this->values) || (
					$field instanceof \MvcCore\Ext\Forms\Fields\IVisibleField && (
						$field->GetDisabled() || $field->GetReadonly()
					)
				)
			) continue;
			$modelValues[$modelInstanceProp] = $this->values[$modelInstanceProp];
		}
		return $modelValues;
	}

	/**
	 * Check if form submit result value has model form result manipulation flag.
	 * @param  bool $submitResult 
	 * @return bool
	 */
	protected function submitHasResultManipulationFlag ($submitResult) {
		$result = FALSE;
		foreach (static::$submitResultManipulationFlags as $submitManipulationFlag) {
			if (($submitResult & $submitManipulationFlag) != 0) {
				$result = TRUE;
				break;
			}
		}
		return $result;
	}
}