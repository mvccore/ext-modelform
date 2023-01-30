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
	 * @param  string     $clientDefaultErrorMessage 
	 * @param  array      $replacements
	 * @return void
	 */
	protected function logAndAddSubmitError ($error, $clientDefaultErrorMessage, $replacements) {
		$prevError = $error->getPrevious();
		if ($prevError === NULL && $clientDefaultErrorMessage !== NULL) {
			$clientErrorMessage = $clientDefaultErrorMessage;
			$errorToLog = $error;
		} else {
			$clientErrorMessage = $error->getMessage();
			$errorToLog = $prevError !== NULL ? $prevError : $error;
		}

		/** @var \MvcCore\Debug $debugClass */
		$debugClass = $this->controller->GetApplication()->GetDebugClass();
		$debugClass::Log($errorToLog);

		if ($this->controller->GetEnvironment()->IsDevelopment() && $debugClass::GetDebugging())
			$debugClass::Exception($errorToLog);

		$this->result = \MvcCore\Ext\IForm::RESULT_ERRORS;

		if ($this->translate) 
			$clientErrorMessage = $this->Translate($clientErrorMessage);
		$viewClass = $this->GetViewClass();
		$clientErrorMessage = $viewClass::Format($clientErrorMessage, $replacements);

		$this->AddError($clientErrorMessage);
	}
}