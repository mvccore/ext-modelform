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

class		Form
extends		\MvcCore\Ext\Form
implements	\MvcCore\Ext\ModelForms\IForm {

	use		\MvcCore\Ext\ModelForms\Forms\Features;

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

	public function Submit (array & $rawRequestParams = []) {
		//parent::Submit($rawRequestParams);
		// TODO: udelat submitnutí podle model formu - kdy se automaticky zavolá insert nebo update!
		$this->submitModelForm($rawRequestParams);
	}
}