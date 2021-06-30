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
	const RESULT_SUCCESS_CREATE	= 4;
	
	/**
	 * Success result form state to edit model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_EDIT	= 5;
	
	/**
	 * Success result form state to delete model instance.
	 * @var int
	 */
	const RESULT_SUCCESS_DELETE	= 6;


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
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelClassFullName ($modelClassFullName);


	/**
	 * Set model instance. When you creating new model record in database,
	 * you can set empty instance without declared primary property, when 
	 * you want to edit or delete model record already in database, you have
	 * to call this method to define model instance to init and submit model 
	 * form properly.
	 * @param  \MvcCore\Ext\ModelForms\IModel|NULL $modelInstance 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelInstance (\MvcCore\Ext\ModelForms\IModel $modelInstance);
	
	/**
	 * Get model instance. 
	 * @return \MvcCore\Ext\ModelForms\IModel|NULL
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
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelPropsFlags ($modelPropsFlags = 0);


	/**
	 * Get defined set of field classes to be able to decorate on model properties.
	 * @return \string[]
	 */
	public function GetFieldsTypes ();

	/**
	 * Set custom set of field classes to be able to decorate on model properties.
	 * All previous field classes will be replaced with given values.
	 * @param  \string[] $fieldsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetFieldsTypes ($fieldsTypes);

	/**
	 * Add custom set of field classes to be able to decorate on model properties.
	 * @param  \string[] $fieldsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddFieldsTypes ($fieldsTypes);

	
	/**
	 * Get defined set of validator classes to be able to decorate on model properties.
	 * @return \string[]
	 */
	public function GetValidatorsTypes ();

	/**
	 * Set custom set of validator classes to be able to decorate on model properties.
	 * All previous validator classes will be replaced with given values.
	 * @param  \string[] $validatorsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetValidatorsTypes ($validatorsTypes);
	
	/**
	 * Add custom set of validator classes to be able to decorate on model properties.
	 * @param  \string[] $validatorsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddValidatorsTypes ($validatorsTypes);


	/**
	 * Get model form submit button(s) field names.
	 * @return array
	 */
	public function GetSubmitNames ();

	/**
	 * Set model form submit button(s) field names.
	 * Array keys have to be: `create`, `edit` and `delete`.
	 * @param array $submitNames 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetSubmitNames ($submitNames);


	/**
	 * Get model form submit button(s) field texts.
	 * @return array
	 */
	public function GetSubmitTexts ();

	/**
	 * Set model form submit button(s) field texts.
	 * Array keys have to be: `create`, `edit` and `delete`.
	 * @param array $submitTexts 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetSubmitTexts ($submitTexts);

}