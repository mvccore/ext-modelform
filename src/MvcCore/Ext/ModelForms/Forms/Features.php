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

trait Features {
	use \MvcCore\Ext\ModelForms\Forms\Props,
		\MvcCore\Ext\ModelForms\Forms\PropsFormNamespaces,
		\MvcCore\Ext\ModelForms\Forms\PropsSubmit,
		\MvcCore\Ext\ModelForms\Forms\PropsFieldsTypes,
		\MvcCore\Ext\ModelForms\Forms\PropsValidatorsTypes,

		\MvcCore\Ext\ModelForms\Forms\GettersSetters,
		\MvcCore\Ext\ModelForms\Forms\FormMethods,
		\MvcCore\Ext\ModelForms\Forms\ModelFormMethods,
		\MvcCore\Ext\ModelForms\Forms\ModelFormInitMethods,
		\MvcCore\Ext\ModelForms\Forms\ModelFormSubmitMethods;
}