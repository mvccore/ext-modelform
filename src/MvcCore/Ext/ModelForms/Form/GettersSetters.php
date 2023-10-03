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
	 * @return \string[]
	 */
	public function GetFieldsTypes () {
		return $this->fieldsTypes;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $fieldsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetFieldsTypes ($fieldsTypes) {
		$this->fieldsTypes = $fieldsTypes;
		return $this;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $fieldsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddFieldsTypes ($fieldsTypes) {
		if (!is_array($fieldsTypes)) $fieldsTypes = func_get_args();
		$this->fieldsTypes += $fieldsTypes;
		return $this;
	}

	
	/**
	 * @inheritDoc
	 * @return \string[]
	 */
	public function GetValidatorsTypes () {
		return $this->validatorsTypes;
	}

	/**
	 * @inheritDoc
	 * @param  \string[] $validatorsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetValidatorsTypes ($validatorsTypes) {
		$this->validatorsTypes = $validatorsTypes;
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @param  \string[] $validatorsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddValidatorsTypes ($validatorsTypes) {
		if (!is_array($validatorsTypes)) $validatorsTypes = func_get_args();
		$this->validatorsTypes += $validatorsTypes;
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
}