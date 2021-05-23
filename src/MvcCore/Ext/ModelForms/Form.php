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

use \MvcCore\Ext\Forms\Fields;

/**
 * @mixin \MvcCore\Ext\ModelForms\Form\Features
 */
class		Form
extends		\MvcCore\Ext\Form
implements	\MvcCore\Ext\ModelForms\IForm {

	use		\MvcCore\Ext\ModelForms\Form\Features;
	
	/**
	 * MvcCore Extension - ModelForm - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.1.8';

	/**
	 * @inheritDocs
	 * @param  bool $submit      `TRUE` if form is submitting, `FALSE` otherwise by default.
	 * @throws \RuntimeException No form id property defined or Form id `...` already defined.
	 * @return void
	 */
	public function Init ($submit = FALSE): void {
		parent::Init($submit = FALSE);
		$this->SetModelPropsFlags(\MvcCore\IModel::PROPS_PROTECTED |\MvcCore\IModel::PROPS_INHERIT);
		$this->initModelForm($submit);
		if ($this->modelInstance === NULL) {
			$create = (new Fields\SubmitButton)
				->SetName('create')
				->SetValue($this->submitsTexts[0]);
			$this->AddField($create);
		} else {
			$save = (new Fields\SubmitButton)
				->SetName('save')
				->SetValue($this->submitsTexts[1]);
			$remove = (new Fields\SubmitButton)
				->SetName('remove')
				->SetValue($this->submitsTexts[2]);
			$this->AddField($save, $remove);
		}
	}

	/**
	 * @inheritDocs
	 * @param  array $rawRequestParams Optional, raw `$_POST` or `$_GET` array could be passed.
	 * @return array An array to list: `[$form->result, $form->data, $form->errors];`
	 */
	public function Submit (array & $rawRequestParams = []) {
		$this->submitModelForm($rawRequestParams);
	}
}