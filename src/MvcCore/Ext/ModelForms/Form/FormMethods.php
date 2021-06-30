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
trait FormMethods {
	
	/**
	 * @inheritDocs
	 * @param  bool $submit `TRUE` if form is submitting, `FALSE` otherwise by default.
	 * @throws \RuntimeException No form id property defined or Form id `...` already defined.
	 * @return void
	 */
	public function Init ($submit = FALSE) {
		
		// To define model class is necessary for each model form initialization.
		//$this->SetModelClassFullName(\App\Models\MyModelClass::class);
		//$this->SetModelInstance(new \App\Models\MyModelClass);
			
		// This method is optional:
		//$this->SetModelPropsFlags(\MvcCore\IModel::PROPS_PROTECTED |\MvcCore\IModel::PROPS_INHERIT);

		$this->initModelForm($submit);
	}

	/**
	 * @inheritDocs
	 * @param  array $rawRequestParams optional
	 * @return array An array to list: `[$form->result, $form->data, $form->errors];`
	 */
	public function Submit (array & $rawRequestParams = []) {
		$submitResult = $this->submitModelForm($rawRequestParams);
		if ($this->result !== self::RESULT_ERRORS) {
			$this->result = self::RESULT_SUCCESS;
			$this->ClearSession();
		}
		return $submitResult;
	}
}