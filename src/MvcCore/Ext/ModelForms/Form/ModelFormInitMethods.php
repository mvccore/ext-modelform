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

use \MvcCore\Ext\Forms\Fields;

/**
 * @mixin \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form
 */
trait ModelFormInitMethods {
	
	/**
	 * Initialize model form id, fields, submit buttons and initial values.
	 * @param  bool $submit 
	 * @throws \InvalidArgumentException|\RuntimeException
	 * @return void
	 */
	protected function initModelForm ($submit = FALSE) {
		if ($this->id === NULL) $this->initModelFormId($submit);
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
		$submitNames = (object) $this->submitNames;
		$submitTexts = (object) $this->submitTexts;
		if ($this->isModelNew()) {
			$create = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\ModelForms\IForm::RESULT_SUCCESS_CREATE)
				->SetName($submitNames->create)
				->SetValue($submitTexts->create);
			$this->AddField($create);
		} else {
			$edit = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\ModelForms\IForm::RESULT_SUCCESS_EDIT)
				->SetName($submitNames->edit)
				->SetValue($submitTexts->edit);
			$delete = (new Fields\SubmitButton)
				->SetFormNoValidate(TRUE)
				->SetCustomResultState(\MvcCore\Ext\ModelForms\IForm::RESULT_SUCCESS_DELETE)
				->SetName($submitNames->delete)
				->SetValue($submitTexts->delete);
			$copy = (new Fields\SubmitButton)
				->SetCustomResultState(\MvcCore\Ext\ModelForms\IForm::RESULT_SUCCESS_COPY)
				->SetName($submitNames->copy)
				->SetValue($submitTexts->copy);
			$this->AddFields($edit, $delete, $copy);
		}
	}

	/**
	 * Initialize model form id for rendered elements and session key.
	 * @param  bool $submit 
	 * @throws \InvalidArgumentException|\RuntimeException
	 * @return void
	 */
	protected function initModelFormId ($submit = FALSE) {
		$formIdScBegin = '\\' . ltrim(str_replace('_', '\\', get_class($this)), '\\');
		foreach (static::$formsNamespaces as $formsNamespace) {
			if (mb_strpos($formIdScBegin, $formsNamespace) === 0) {
				$formIdDcBegin = mb_substr($formIdScBegin, mb_strlen($formsNamespace));
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
		$modelClassFullName = $this->modelClassFullName;
		$toolClass = $this->application->GetToolClass();
		$toolClass::CheckClassInterface($modelClassFullName, $this->modelClassInterface, TRUE, TRUE);

		list (
			$primaryKeyFields, 
			$uniqueKeyFields
		) = $modelClassFullName::GetUniqueFieldsNames();

		$hasPrimaryKeyFields = is_array($primaryKeyFields) && count($primaryKeyFields) > 0;
		$hasUniqueKeyFields = is_array($uniqueKeyFields) && count($uniqueKeyFields) > 0;
		$uniqueFieldsValues = [];
		if ($hasPrimaryKeyFields) 
			$uniqueFieldsValues = $this->initModelFormIdGetUniqueValueByNames($primaryKeyFields, TRUE);
		if (count($uniqueFieldsValues) === 0 && $hasUniqueKeyFields) 
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
		$modelClassFullName = $this->modelClassFullName;
		/** @var $decoratedPropsMetaData \MvcCore\Ext\ModelForms\Models\PropertyMeta[] */
		$decoratedPropsMetaData = $modelClassFullName::GetFormsMetaData($this->modelPropsFlags);
		$attrsAnotations = $this->GetController()->GetApplication()->GetAttributesAnotations();
		foreach ($decoratedPropsMetaData as $modelPropName => $propMetaData) {
			$field = $this->initModelField2Add($modelPropName, $propMetaData, $attrsAnotations);
			if ($field !== NULL) $this->AddField($field);
		}
	}
	
	/**
	 * Create and add field by property name and field model metadata.
	 * Return field instance or null for non primary model properties.
	 * @param  string                                      $modelPropName 
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @param  bool                                        $attrsAnotations
	 * @return \MvcCore\Ext\Forms\Field|NULL
	 */
	protected function initModelField2Add ($modelPropName, $propMetaData, $attrsAnotations) {
		$toolClass = $this->application->GetToolClass();
		if ($attrsAnotations) {
			$fieldsAttrs = [];
			$reflectionObject = new \ReflectionProperty($this->modelClassFullName, $modelPropName);
			foreach (static::$fieldsNamespaces as $fieldsNamespace) {
				list($attrName, $ctorArgs) = $this->initModelFieldGetPropAttribute(
					$reflectionObject, $fieldsNamespace
				);
				if ($attrName !== NULL) {
					$fieldsAttrs[$attrName] = $ctorArgs;
				}
			}
		} else {
			$fieldsAttrs = array_filter($toolClass::GetPropertyAttrsArgs(
				$this->modelClassFullName, $modelPropName, [\MvcCore\Ext\ModelForms\IModel::PHP_DOCS_TAG_NAME_FIELD], FALSE
			), 'is_array');;
		}
		
		$fieldsAttrsCnt = count($fieldsAttrs);
		if ($fieldsAttrsCnt === 0) {
			if (!$propMetaData->IsPrimary) return NULL;
			$fieldInstance = (new \MvcCore\Ext\Forms\Fields\Hidden)
				->SetName($modelPropName);
		} else if ($fieldsAttrsCnt === 1) {
			$fieldInstance = $this->initModelFieldCreate(
				$modelPropName, $propMetaData, $fieldsAttrs, $attrsAnotations
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
		
		return $fieldInstance;
	}

	/**
	 * Create field by property name, field model metadata and form field anotation.
	 * @param  string                                      $modelPropName 
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @param  array                                       $fieldsAttrs  
	 * @param  bool                                        $attrsAnotations 
	 * @return \MvcCore\Ext\Forms\Field
	 */
	protected function initModelFieldCreate ($modelPropName, $propMetaData, $fieldsAttrs, $attrsAnotations) {
		list ($fieldFullClassName, $fieldCtorConfig) = $this->initModelFieldGetClassAndConfig(
			$modelPropName, $fieldsAttrs, $attrsAnotations
		);

		$fieldType = new \ReflectionClass($fieldFullClassName);
		$fieldCtorConfig['name'] = $modelPropName;
		if (!isset($fieldCtorConfig['required']))
			$fieldCtorConfig['required'] = !$propMetaData->AllowNulls && !$propMetaData->HasDefault;
		
		/** @var \MvcCore\Ext\Forms\Field $fieldInstance */
		$fieldInstance = $fieldType->newInstanceArgs([$fieldCtorConfig]);

		return $this->initModelFieldSetUpMetaData($fieldInstance, $propMetaData);
	}

	/**
	 * Set up into newly created form field all known special properties from metadata:
	 * @template
	 * @param  \MvcCore\Ext\Forms\Field                    $fieldInstance
	 * @param  \MvcCore\Ext\ModelForms\Models\PropertyMeta $propMetaData 
	 * @return \MvcCore\Ext\Forms\Field
	 */
	protected function initModelFieldSetUpMetaData ($fieldInstance, $propMetaData) {

		// date/time inputs:
		if (
			$propMetaData->FormatData !== NULL && 
			count($propMetaData->FormatData) > 0 && 
			$fieldInstance instanceof \MvcCore\Ext\Forms\Fields\IFormat
		) $fieldInstance->SetFormat($propMetaData->FormatData[0]);
		if (
			$propMetaData->ParserData !== NULL && 
			count($propMetaData->ParserData) > 0 &&
			isset($propMetaData->ParserData['tz']) && 
			$fieldInstance instanceof \MvcCore\Ext\Forms\Fields\ITimeZone
		) $fieldInstance->SetTimeZone($propMetaData->ParserData['tz']);

		return $fieldInstance;
	}

	/**
	 * Get property decorated field full class names and constructors configs.
	 * @param  string $modelPropName 
	 * @param  array  $fieldsAttrs 
	 * @param  bool   $attrsAnotations 
	 * @throws \InvalidArgumentException 
	 * @return array `[string $fieldFullClassName, array $fieldCtorConfig]`
	 */
	protected function initModelFieldGetClassAndConfig ($modelPropName, $fieldsAttrs, $attrsAnotations) {
		$fieldsAttrsKeys = array_keys($fieldsAttrs);
		if ($attrsAnotations) {
			$fieldFullClassName = $fieldsAttrsKeys[0];
			$fieldCtorArgs = $fieldsAttrs[$fieldFullClassName];
			$fieldCtorConfig = is_array($fieldCtorArgs) ? $fieldCtorArgs : [];
		} else {
			$fieldTag = $fieldsAttrsKeys[0];
			$fieldCtorArgs = $fieldsAttrs[$fieldTag];
			$fieldCtorArgsCnt = count($fieldCtorArgs);
			$fieldFullClassName = NULL;
			$fieldCtorConfig = [];
			if ($fieldCtorArgsCnt > 0 && $fieldCtorArgsCnt < 3) {
				$fieldTagFieldName = $fieldCtorArgs[0];
				if (isset($this->fieldsTags[$fieldTagFieldName])) {
					$fieldFullClassName = $this->fieldsTags[$fieldTagFieldName];
				} else if (class_exists($fieldTagFieldName)) {
					$fieldFullClassName = $fieldTagFieldName;
				}
				if ($fieldCtorArgsCnt > 1 && (is_array($fieldCtorArgs[1]) || $fieldCtorArgs[1] instanceof \stdClass)) 
					$fieldCtorConfig = (array) $fieldCtorArgs[1];
			}
			if ($fieldFullClassName === NULL) 
				throw new \InvalidArgumentException(
					"Decorated model form filed type `{$fieldTagFieldName}` doesn't exist on property ".
					"`{$modelPropName}` in class `{$this->modelClassFullName}`. Model property decoration ".
					'has to be in format: `@field FieldClassName({"label":"Label text",...})`.'
				);
			$fieldCtorConfig = $fieldCtorConfig instanceof \stdClass 
				? (array) $fieldCtorConfig
				: (is_array($fieldCtorConfig) ? $fieldCtorConfig : []);
		}
		return [$fieldFullClassName, $fieldCtorConfig];
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
		$validatorsClassesAndCtorsConfigs = $this->initModelFieldValidatorsClassesAndConfigs(
			$modelPropName, $attrsAnotations
		);

		$fieldValidators = [];
		if (count($validatorsClassesAndCtorsConfigs) > 0) {
			foreach	($validatorsClassesAndCtorsConfigs as $validatorFullClasName => $validatorCtorConfig) {
				$validatorType = new \ReflectionClass($validatorFullClasName);
				$validatorInstance = $validatorType->newInstanceArgs($validatorCtorConfig);
				$fieldValidators[] = $validatorInstance;
			}
		}

		return $fieldValidators;
	}

	/**
	 * Get property decorated validators full class names and constructor arguments.
	 * @param  string $modelPropName
	 * @param  bool   $attrsAnotations
	 * @return array                   Keys are validator classes full names,
	 *                                 values are validators constructor config arrays.
	 */
	protected function initModelFieldValidatorsClassesAndConfigs ($modelPropName, $attrsAnotations) {
		if ($attrsAnotations) {
			$validatorsClassesAndConfigs = [];
			$reflectionObject = new \ReflectionProperty($this->modelClassFullName, $modelPropName);
			foreach (static::$validatorsNamespaces as $validatorsNamespace) {
				list($attrName, $ctorArgs) = $this->initModelFieldGetPropAttribute(
					$reflectionObject, $validatorsNamespace
				);
				if ($attrName !== NULL) {
					$validatorsClassesAndConfigs[$attrName] = $ctorArgs;
				}
			}
			
			foreach ($validatorsClassesAndConfigs as $validatorFullClassName => $validatorCtorArgs) 
				$validatorsAttrs[$validatorFullClassName] = (
					isset($validatorCtorArgs[0]) && is_array($validatorCtorArgs[0])
				) 
					? $validatorCtorArgs[0] 
					: [];

		} else {
			$validatorsAttrs = array_filter(\MvcCore\Tool::GetPropertyAttrsArgs(
				$this->modelClassFullName, $modelPropName, [\MvcCore\Ext\ModelForms\IModel::PHP_DOCS_TAG_NAME_VALIDATOR], FALSE
			), 'is_array');

			$validatorsClassesAndConfigs = [];
			foreach ($validatorsAttrs as $validatorFullClasName => $validatorCtorArgs) {
				$length = count($validatorCtorArgs);
				for ($i = 0; $i < $length; $i++) {
					$validatorClassOrCtorArgs = $validatorCtorArgs[$i];
					if (is_string($validatorClassOrCtorArgs)) {
						if (isset($this->validatorsTags[$validatorClassOrCtorArgs])) {
							$validatorFullClassName = $this->validatorsTags[$validatorClassOrCtorArgs];
						} else if (class_exists($validatorClassOrCtorArgs)) {
							$validatorFullClassName = $validatorClassOrCtorArgs;
						} else {
							throw new \InvalidArgumentException(
								"Decorated model form validator type `{$validatorClassOrCtorArgs}` doesn`t exist on property ".
								"`{$modelPropName}` in class `{$this->modelClassFullName}`. Model property decoration ".
								'has to be in format: `@validator ValidatorClassName({"option":"value",...})`.'
							);
						}
						$validatorCtorConfig = [];
						if ($i + 1 < $length) {
							$nextIndex = $i + 1;
							if (!is_string($validatorCtorArgs[$nextIndex])) {
								$validatorCtorConfig = $validatorCtorArgs[$nextIndex];
								if ($validatorCtorConfig instanceof \stdClass)
									$validatorCtorConfig = (array) $validatorCtorConfig;
								$i++;
							}
						}
						$validatorsClassesAndConfigs[$validatorFullClassName] = $validatorCtorConfig;
					}
				}
			}
		}
		
		return $validatorsClassesAndConfigs;
	}

	
	/**
	 * @inheritDoc
	 * @param  \ReflectionProperty $reflectionObject 
	 * @param  string              $attrClassNamespace
	 * @return array
	 */
	protected function initModelFieldGetPropAttribute ($reflectionObject, $attrClassNamespace) {
		$attrName = NULL;
		$ctorArgs = NULL;
		foreach ($reflectionObject->getAttributes() as $allAttr) {
			$attrNameLocal = '\\' . ltrim($allAttr->getName(), '\\');
			if (mb_strpos($attrNameLocal, $attrClassNamespace) === 0) {
				$attrName = $attrNameLocal;
				$ctorArgs = $allAttr->getArguments();
				break;
			}
		}
		return [$attrName, $ctorArgs];
	}

}