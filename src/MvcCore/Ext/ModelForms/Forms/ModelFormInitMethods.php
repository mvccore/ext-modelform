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

use \MvcCore\Ext\Forms\Fields;

trait ModelFormInitMethods {
	
	/**
	 * Initialize model form id, fields, submit buttons and initial values.
	 * @param  bool $submit 
	 * @throws \InvalidArgumentException|\RuntimeException
	 * @return void
	 */
	protected function initModelForm ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->initModelFormId($submit);
		parent::Init($submit);
		$this->initModelFields($submit);
		$this->initModelButtons($submit);
		$this->initModelValues($submit);
	}
	
	/**
	 * Initialize model form submit buttons.
	 * @param  bool $submit 
	 * @return void
	 */
	protected function initModelButtons ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$submitNames = (object) $this->submitNames;
		$submitTexts = (object) $this->submitTexts;
		if ($this->isModelNew()) {
			$create = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\IForm::RESULT_SUCCESS)
				->SetName($submitNames->create)
				->SetValue($submitTexts->create);
			$this->AddField($create);
		} else {
			$save = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\IForm::RESULT_SUCCESS)
				->SetName($submitNames->edit)
				->SetValue($submitTexts->edit);
			$remove = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\IForm::RESULT_SUCCESS)
				->SetName($submitNames->delete)
				->SetValue($submitTexts->delete);
			$this->AddFields($save, $remove);
		}
	}

	/**
	 * Initialize model form id for rendered elements and session key.
	 * @param  bool $submit 
	 * @throws \InvalidArgumentException|\RuntimeException
	 * @return void
	 */
	protected function initModelFormId ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$formIdScBegin = str_replace('_', '\\', get_class($this));
		foreach (static::$formNamespaces as $formNamespace) {
			if (mb_strpos($formIdScBegin, $formNamespace) === 0) {
				$formIdDcBegin = mb_substr($formIdScBegin, mb_strlen($formNamespace));
				break;
			}
		}
		$formIdDcBegin = mb_strtolower(str_replace('\\', '-', $formIdDcBegin));
		if ($this->isModelNew()) {
			$this->initModelFormIdSetUp($formIdDcBegin);
		} else {
			$uniqueStrValues = $this->initModelFormIdGetUniqueValue();
			$uniqueStrValue = count($uniqueStrValues) === 1
				? $uniqueStrValues[0]
				: implode('-', $uniqueStrValues);
			$this->initModelFormIdSetUp($formIdDcBegin, $uniqueStrValue);
		}
	}
	
	/**
	 * Try to complete `\strings[]` array with non `NULL` unique values 
	 * for model instance from primary key fields or any unique key field.
	 * @throws \RuntimeException
	 * @return \string[]
	 */
	protected function initModelFormIdGetUniqueValue () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$modelClassFullName = $this->modelClassFullName;
		$toolClass = $this->application->GetToolClass();
		$toolClass::CheckClassInterface($modelClassFullName, $this->modelClassInterface, TRUE, TRUE);

		list (
			$primaryKeyFields, 
			$uniqueKeyFields
		) = $modelClassFullName::GetUniqueFieldsNames();

		if (count($primaryKeyFields) > 0) 
			$uniqueFieldsValues = $this->initModelFormIdGetUniqueValueByNames($primaryKeyFields, TRUE);
		if (count($uniqueFieldsValues) === 0 && count($uniqueKeyFields) > 0) 
			$uniqueFieldsValues = $this->initModelFormIdGetUniqueValueByNames($primaryKeyFields, FALSE);

		if (count($uniqueFieldsValues) === 0) throw new \RuntimeException(
			"Form model `{$modelClassFullName}` has no primary or unique field(s) with initialized value(s)."
		);
		
		return $uniqueFieldsValues;
	}

	/**
	 * Try to complete array with non `NULL` unique values 
	 * for model instance by given properties names array.
	 * Second argument is boolean for primary key fields.
	 * @param  \string[] $fieldsNames 
	 * @param  bool      $primaryKeys 
	 * @return \string[]
	 */
	protected function initModelFormIdGetUniqueValueByNames ($fieldsNames, $primaryKeys) {
		$uniqueFieldsValues = [];
		$modelClassFullName = $this->modelClassFullName;
		$phpWithTypes = PHP_VERSION_ID >= 70400;
		foreach ($fieldsNames as $fieldName) {
			$prop = new \ReflectionProperty($modelClassFullName, $fieldName);
			$prop->setAccessible(TRUE);
			$uniqueFieldValue = NULL;
			if ($phpWithTypes) {
				if ($prop->isInitialized($this->modelInstance))
					$uniqueFieldValue = $prop->getValue($this->modelInstance);
			} else {
				$uniqueFieldValue = $prop->getValue($this->modelInstance);
			}
			if ($uniqueFieldValue !== NULL) {
				$uniqueFieldsValues[] = (string) $uniqueFieldValue;
				if (!$primaryKeys) break;
			}
		}
		return $uniqueFieldsValues;
	}

	/**
	 * Set up model form id with resolved unique value.
	 * BE CAREFULL! Automatically determinated `$primaryFieldValue` 
	 * will be rendered into output and sent to client. If you want 
	 * to specify different unique form id, overwrite this method 
	 * or method `initModelFormId()` in extended class.
	 * @param  string $formIdBegin 
	 * @param  mixed  $primaryFieldValue 
	 * @return void
	 */
	protected function initModelFormIdSetUp ($formIdBegin, $primaryFieldValue = NULL) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		if ($primaryFieldValue === NULL) {
			$this->SetId($formIdBegin . '-new');
		} else {
			$this->SetId($formIdBegin . '-edit-' . $primaryFieldValue);
		}
	}
	
	/**
	 * Init model form initial values from model instance for edit or delete.
	 * @param  bool $submit 
	 * @return void
	 */
	protected function initModelValues ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		if (!$this->isModelNew() && !$submit) {
			$formValues = $this->GetValues();
			if (!$formValues && $this->modelInstance !== NULL) {
				$modelClassFullName = $this->modelClassFullName;
				$modelValues = $this->modelInstance->GetValues(
					$modelClassFullName::GetDefaultPropsFlags() | \MvcCore\IModel::PROPS_NAMES_BY_CODE
				);
				$this->SetValues($modelValues);
			}
		}
	}

	/**
	 * Initialize all model form fields by it's properties (and model flags),
	 * by given model instance or by model full class name.
	 * @param  bool $submit 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	protected function initModelFields ($submit = FALSE) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$modelClassFullName = $this->modelClassFullName;
		/** @var $decoratedPropsMetaData \MvcCore\Ext\ModelForms\Models\PropertyMeta[] */
		$decoratedPropsMetaData = $modelClassFullName::GetFormsMetaData($this->modelPropsFlags);
		$toolClass = $this->GetController()->GetApplication()->GetToolClass();
		$attrsAnotations = $toolClass::GetAttributesAnotations();
		foreach ($decoratedPropsMetaData as $modelPropName => $propMetaData) 
			$this->initModelFieldAdd($modelPropName, $propMetaData, $attrsAnotations);
	}
	
	/**
	 * Create and add field by property name and field model metadata.
	 * Return field instance or null for non primary model properties.
	 * @param  string                                      $modelPropName 
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @param  bool                                        $attrsAnotations
	 * @return \MvcCore\Ext\Forms\Field|NULL
	 */
	protected function initModelFieldAdd ($modelPropName, $propMetaData, $attrsAnotations) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$fieldsAttrs = array_filter(\MvcCore\Tool::GetPropertyAttrsArgs(
			$this->modelClassFullName, $modelPropName, $this->fieldsTypes, $attrsAnotations
		), 'is_array');

		$fieldsAttrsCnt = count($fieldsAttrs);
		if ($fieldsAttrsCnt === 0) {
			if (!$propMetaData->IsPrimary) return NULL;
			$fieldInstance = (new \MvcCore\Ext\Forms\Fields\Hidden)
				->SetName($modelPropName);
		} else if ($fieldsAttrsCnt === 1) {
			$fieldInstance = $this->initModelFieldCreate(
				$modelPropName, $propMetaData, $fieldsAttrs
			);
		} else {
			throw new \InvalidArgumentException(
				"Model property can not have multiple fields defined ".
				"(model: `{$this->modelClassFullName}`, property: `{$modelPropName}`)."
			);
		}
			
		$fieldValidators = $this->initModelFieldValidators(
			$modelPropName, $propMetaData, $fieldInstance, $attrsAnotations
		);
		if ($fieldValidators)
			$fieldInstance->AddValidators($fieldValidators);

		$this->AddField($fieldInstance);

		return $fieldInstance;
	}

	/**
	 * Create field by property name, field model metadata and form field anotation.
	 * @param  string                                      $modelPropName 
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @param  array                                       $fieldsAttrs 
	 * @return \MvcCore\Ext\Forms\Field
	 */
	protected function initModelFieldCreate ($modelPropName, $propMetaData, $fieldsAttrs) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$fieldFullClassName = array_key_first($fieldsAttrs);
		$fieldCtorArgs = $fieldsAttrs[$fieldFullClassName];
		$fieldCtorConfig = isset($fieldCtorArgs[0]) ? $fieldCtorArgs[0] : [];

		$fieldType = new \ReflectionClass($fieldFullClassName);
		$fieldCtorConfig['name'] = $modelPropName;
		$fieldCtorConfig['required'] = !$propMetaData->AllowNulls && !$propMetaData->HasDefault;

		/** @var $fieldInstance \MvcCore\Ext\Forms\Field */
		$fieldInstance = $fieldType->newInstanceArgs([$fieldCtorConfig]);
		
		return $fieldInstance;
	}

	/**
	 * Create field validators by property name, field model metadata and field instance.
	 * @param  string                                      $modelPropName
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @param  \MvcCore\Ext\Forms\Field                    $fieldInstance
	 * @param  bool                                        $attrsAnotations
	 * @return \MvcCore\Ext\Forms\Validator[]
	 */
	protected function initModelFieldValidators ($modelPropName, $propMetaData, $fieldInstance, $attrsAnotations) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$validatorsAttrs = array_filter(\MvcCore\Tool::GetPropertyAttrsArgs(
			$this->modelClassFullName, $modelPropName, $this->validatorsTypes, $attrsAnotations
		), 'is_array');
		
		$fieldValidators = [];
		if (count($validatorsAttrs) > 0) {
			foreach ($validatorsAttrs as $validatorFullClasName => $validatorCtorArgs) {
				$validatorCtorConfig = isset($validatorCtorArgs[0]) ? $validatorCtorArgs[0] : [];
				$validatorType = new \ReflectionClass($validatorFullClasName);
				$validatorInstance = $validatorType->newInstanceArgs([$validatorCtorConfig]);
				$fieldValidators[] = $validatorInstance;
			}
		}

		return $fieldValidators;
	}
}