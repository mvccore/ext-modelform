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

namespace MvcCore\Ext\ModelForms;

interface IForm {
	
	/**
	 * Success result form state to create model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_CREATE				= 8;
	
	/**
	 * Success result form state to edit model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_EDIT				= 16;
	
	/**
	 * Success result form state to delete model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_DELETE				= 32;
	
	/**
	 * Success result form state to copy model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_COPY				= 64;
	
	/**
	 * Success result form state when model instance has been change.
	 * @var int
	 */
	const RESULT_SUCCESS_MODEL_CHANGED		= 128;

	/**
	 * Success result form state when model instance has without changes.
	 * (form has been submitted without changed values).
	 * @var int
	 */
	const RESULT_SUCCESS_MODEL_NOT_CHANGED	= 256;


	/**
	 * Get model class full name.
	 * @return string
	 */
	public function GetModelClassFullName ();
	
	/**
	 * Set model class full name. 
	 * When you creating new model record in database without configured 
	 * form model instance, you have to call this method to configure 
	 * at least model class type to init and submit model form properly.
	 * @param  string $modelClassFullName
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetModelClassFullName ($modelClassFullName);


	/**
	 * Set model instance. When you creating new model record in database,
	 * you can set empty instance without declared primary property, when 
	 * you want to edit or delete model record already in database, you have
	 * to call this method to define model instance to init and submit model 
	 * form properly.
	 * @param  ?\MvcCore\Ext\ModelForms\IModel $modelInstance 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetModelInstance (\MvcCore\Ext\ModelForms\IModel $modelInstance);
	
	/**
	 * Get model instance. 
	 * @return ?\MvcCore\Ext\ModelForms\IModel
	 */
	public function GetModelInstance ();

	
	/**
	 * Get default model properties flags.
	 * @return int
	 */
	public function GetModelPropsFlags ();
	
	/**
	 * Set default model properties flags.
	 * This method is optional, it's not necessary to define model properties flags,
	 * if there are defined default model properties flags in model class.
	 * @param  int $modelPropsFlags
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetModelPropsFlags ($modelPropsFlags = 0);


	/**
	 * Get defined set of field classes to be able to decorate on model properties.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @return array<string, string>
	 */
	public function GetFieldsTags ();

	/**
	 * Set custom set of field classes to be able to decorate on model properties.
	 * All previous field classes will be replaced with given values.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @param  array<string, string> $fieldsTags 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetFieldsTags ($fieldsTags);

	/**
	 * Add custom set of field classes to be able to decorate on model properties.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @param  array<string, string> $fieldsTags,...
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function AddFieldsTags ($fieldsTags);

	
	/**
	 * Get defined set of validator classes to be able to decorate on model properties.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @return array<string, string>
	 */
	public function GetValidatorsTags ();

	/**
	 * Set custom set of validator classes to be able to decorate on model properties.
	 * All previous validator classes will be replaced with given values.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @param  array<string, string> $validatorsTags 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetValidatorsTags ($validatorsTags);
	
	/**
	 * Add custom set of validator classes to be able to decorate on model properties.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @param  array<string, string> $validatorsTags,...
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function AddValidatorsTags ($validatorsTags);


	/**
	 * Get model form submit button(s) field names.
	 * @return array
	 */
	public function GetSubmitNames ();

	/**
	 * Set model form submit button(s) field names.
	 * Array keys have to be: `create`, `edit` and `delete`.
	 * @param array $submitNames 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetSubmitNames (array $submitNames);


	/**
	 * Get model form submit button(s) field texts.
	 * @return array
	 */
	public function GetSubmitTexts ();

	/**
	 * Set model form submit button(s) field texts.
	 * Array keys have to be: `create`, `edit` and `delete`.
	 * @param array $submitTexts 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetSubmitTexts (array $submitTexts);


	/**
	 * Get default client error message texts. Replacement `{0}` 
	 * is always replaced by managed model full class name.
	 * returned array have keys `create`, `edit` and `delete` 
	 * with not translated error message texts.
	 * @return array
	 */
	public function GetDefaultClientErrorMessages ();

	/**
	 * Set default client error message texts. Replacement `{0}` 
	 * is always replaced by managed model full class name.
	 * Given array must have keys `create`, `edit` and `delete` 
	 * with not translated error message texts.
	 * @param array $defaultClientErrorMessages 
	 * @return \MvcCore\Ext\ModelForms\IForm
	 */
	public function SetDefaultClientErrorMessages (array $defaultClientErrorMessages);


	/**
	 * Get default form namespaces to have shorter form id session key.
	 * @return \string[]
	 */
	public static function GetFormsNamespaces ();

	/**
	 * Set default form namespaces to have shorter form id session key.
	 * All previous default form namespaces will be replaced with given values.
	 * @param  \string[] $formsNamespaces 
	 * @return \string[]
	 */
	public static function SetFormsNamespaces ($formsNamespaces);
	
	/**
	 * Add default form namespaces to have shorter form id session key.
	 * @param  \string[] $formsNamespaces,...
	 * @return \string[]
	 */
	public static function AddFormsNamespaces ($formsNamespaces);
	
	/**
	 * Get form field base namespaces to create fields instances by decorated class names.
	 * Field will be created by class existence in this namespaces order.
	 * This array is used only for newer way how to decorate properties by PHP attributes.
	 * @return array<string, string>
	 */
	public static function GetFieldsNamespaces ();

	/**
	 * Set form field base namespaces to create fields instances by decorated class names.
	 * All previous field base namespaces will be replaced with given values.
	 * Field will be created by class existence in this namespaces order.
	 * This array is used only for newer way how to decorate properties by PHP attributes.
	 * @param  array<string, string> $fieldsNamespaces 
	 * @return array<string, string>
	 */
	public static function SetFieldsNamespaces ($fieldsNamespaces);
	
	/**
	 * Add form field base namespace to create fields instances by decorated class names.
	 * Field will be created by class existence in this namespaces order.
	 * This array is used only for newer way how to decorate properties by PHP attributes.
	 * @param  array<string, string> $fieldsNamespaces,...
	 * @return array<string, string>
	 */
	public static function AddFieldsNamespaces ($fieldsNamespaces);

}