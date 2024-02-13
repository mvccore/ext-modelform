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

/**
 * @mixin \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form
 */
trait GettersSetters {

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function GetModelClassFullName () {
		return $this->modelClassFullName;
	}
	
	/**
	 * @inheritDoc
	 * @param  string $modelClassFullName
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelClassFullName ($modelClassFullName) {
		$this->modelClassFullName = $modelClassFullName;
		return $this;
	}

	
	/**
	 * @inheritDoc
	 * @param  \MvcCore\Ext\ModelForms\IModel|NULL $modelInstance 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelInstance ($modelInstance) {
		$this->modelInstance = $modelInstance;
		if ($this->modelClassFullName === NULL)
			$this->modelClassFullName = get_class($modelInstance);
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @return \MvcCore\Ext\ModelForms\IModel|NULL
	 */
	public function GetModelInstance () {
		return $this->modelInstance;
	}

	
	/**
	 * @inheritDoc
	 * @return int
	 */
	public function GetModelPropsFlags () {
		return $this->modelPropsFlags;
	}
	
	/**
	 * @inheritDoc
	 * @param  int $modelPropsFlags
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelPropsFlags ($modelPropsFlags = 0) {
		$this->modelPropsFlags = $modelPropsFlags;
		return $this;
	}


	/**
	 * @inheritDoc
	 * @return array<string, string>
	 */
	public function GetFieldsTags () {
		return $this->fieldsTags;
	}

	/**
	 * @inheritDoc
	 * @param  array<string, string> $fieldsTags
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetFieldsTags ($fieldsTags) {
		$this->fieldsTags = $fieldsTags;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @param  array<string, string> $fieldsTags,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddFieldsTags ($fieldsTags) {
		if (!is_array($fieldsTags)) $fieldsTags = func_get_args();
		$this->fieldsTags = array_merge([], $this->fieldsTags, $fieldsTags);
		return $this;
	}

	
	/**
	 * @inheritDoc
	 * @return array<string, string>
	 */
	public function GetValidatorsTags () {
		return $this->validatorsTags;
	}

	/**
	 * @inheritDoc
	 * @param  array<string, string> $validatorsTags 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetValidatorsTags ($validatorsTags) {
		$this->validatorsTags = $validatorsTags;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  array<string, string> $validatorsTags,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddValidatorsTags ($validatorsTags) {
		if (!is_array($validatorsTags)) $validatorsTags = func_get_args();
		$this->validatorsTags = array_merge([], $this->validatorsTags, $validatorsTags);
		return $this;
	}


	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetSubmitNames () {
		return $this->submitNames;
	}

	/**
	 * @inheritDoc
	 * @param array $submitNames 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetSubmitNames (array $submitNames) {
		$this->submitNames = $submitNames;
		return $this;
	}


	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetSubmitTexts () {
		return $this->submitTexts;
	}

	/**
	 * @inheritDoc
	 * @param array $submitTexts 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetSubmitTexts (array $submitTexts) {
		$this->submitTexts = $submitTexts;
		return $this;
	}


	/**
	 * @inheritDoc
	 * @return array
	 */
	public function GetDefaultClientErrorMessages () {
		return $this->defaultClientErrorMessages;
	}

	/**
	 * @inheritDoc
	 * @param array $defaultClientErrorMessages 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetDefaultClientErrorMessages (array $defaultClientErrorMessages) {
		$this->defaultClientErrorMessages = $defaultClientErrorMessages;
		return $this;
	}


	/**
	 * @inheritDoc
	 * @return \string[]
	 */
	public static function GetFormsNamespaces () {
		return static::$formsNamespaces;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $formsNamespaces 
	 * @return \string[]
	 */
	public static function SetFormsNamespaces ($formsNamespaces) {
		return static::$formsNamespaces = $formsNamespaces;
	}
	
	/**
	 * @inheritDoc
	 * @param  \string[] $formsNamespaces,...
	 * @return \string[]
	 */
	public static function AddFormsNamespaces ($formsNamespaces) {
		if (!is_array($formsNamespaces)) $formsNamespaces = func_get_args();
		return static::$formsNamespaces = array_merge([], static::$formsNamespaces, $formsNamespaces);
	}

	/**
	 * @inheritDoc
	 * @return array<string, string>
	 */
	public static function GetFieldsNamespaces () {
		return static::$fieldsNamespaces;
	}

	/**
	 * @inheritDoc
	 * @param  array<string, string> $fieldsNamespaces 
	 * @return array<string, string>
	 */
	public static function SetFieldsNamespaces ($fieldsNamespaces) {
		return static::$fieldsNamespaces = $fieldsNamespaces;
	}
	
	/**
	 * @inheritDoc
	 * @param  array<string, string> $fieldsNamespaces,...
	 * @return array<string, string>
	 */
	public static function AddFieldsNamespaces ($fieldsNamespaces) {
		if (!is_array($fieldsNamespaces)) $fieldsNamespaces = func_get_args();
		return static::$fieldsNamespaces = array_merge([], static::$fieldsNamespaces, $fieldsNamespaces);
	}

}