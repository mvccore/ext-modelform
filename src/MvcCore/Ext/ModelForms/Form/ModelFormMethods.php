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
trait ModelFormMethods {
	
	/**
	 * Return if form creates new model instance or not.
	 * @return bool
	 */
	protected function isModelNew () {
		if ($this->modelIsNew === NULL)
			$this->modelIsNew = (
				$this->modelInstance === NULL || (
					$this->modelInstance !== NULL && $this->modelInstance->IsNew()
				)
			);
		return $this->modelIsNew;
	}

	/**
	 * Log given `\Throwable` by MvcCore debug class and 
	 * add (optionally translated) form error message displayed to client.
	 * @param  \Throwable $error 
	 * @param  string     $clientMsg 
	 * @param  array      $replacements
	 * @return void
	 */
	protected function logAndAddSubmitError ($error, $clientMsg, $replacements) {
		$debugClass = $this->controller->GetApplication()->GetDebugClass();
		$debugClass::Log($error);

		$this->result = \MvcCore\Ext\IForm::RESULT_ERRORS;

		if ($this->translate) 
			$clientMsg = $this->Translate($clientMsg);
		$formViewClass = $this->GetViewClass();
		$clientMsg = $formViewClass::Format($clientMsg, $replacements);
		$this->AddError($clientMsg);
	}
}