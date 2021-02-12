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

trait GettersSetters {

	/**
	 * @inheritDocs
	 * @return string
	 */
	public function GetModelClass () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->modelClassFullName;
	}
	
	/**
	 * @inheritDocs
	 * @param string $modelClassFullName
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelClass ($modelClassFullName) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->modelClassFullName = $modelClassFullName;
		return $this;
	}

	
	/**
	 * @inheritDocs
	 * @param \MvcCore\Ext\ModelForms\IModel|NULL $modelInstance 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelInstance ($modelInstance) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->modelInstance = $modelInstance;
		if ($this->modelClassFullName === NULL)
			$this->modelClassFullName = get_class($modelInstance);
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @return \MvcCore\Ext\ModelForms\IModel|NULL
	 */
	public function GetModelInstance () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->modelInstance;
	}

	
	/**
	 * @inheritDocs
	 * @return int
	 */
	public function GetModelPropsFlags () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->modelPropsFlags;
	}
	
	/**
	 * @inheritDocs
	 * @param int $modelPropsFlags
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetModelPropsFlags ($modelPropsFlags = 0) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->modelPropsFlags = $modelPropsFlags;
		return $this;
	}


	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetFieldsTypes () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->fieldsTypes;
	}

	/**
	 * @inheritDocs
	 * @param \string[] $fieldsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetFieldsTypes ($fieldsTypes) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->fieldsTypes = $fieldsTypes;
		return $this;
	}

	/**
	 * @inheritDocs
	 * @param \string[] $fieldsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddFieldsTypes ($fieldsTypes) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		if (!is_array($fieldsTypes)) $fieldsTypes = func_get_args();
		$this->fieldsTypes += $fieldsTypes;
		return $this;
	}

	
	/**
	 * @inheritDocs
	 * @return \string[]
	 */
	public function GetValidatorsTypes () {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		return $this->validatorsTypes;
	}

	/**
	 * @inheritDocs
	 * @param \string[] $validatorsTypes 
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function SetValidatorsTypes ($validatorsTypes) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		$this->validatorsTypes = $validatorsTypes;
		return $this;
	}
	
	/**
	 * @inheritDocs
	 * @param \string[] $validatorsTypes,...
	 * @return \MvcCore\Ext\ModelForms\Form
	 */
	public function AddValidatorsTypes ($validatorsTypes) {
		/** @var $this \MvcCore\Ext\ModelForms\Form|\MvcCore\Ext\Form */
		if (!is_array($validatorsTypes)) $validatorsTypes = func_get_args();
		$this->validatorsTypes += $validatorsTypes;
		return $this;
	}
}