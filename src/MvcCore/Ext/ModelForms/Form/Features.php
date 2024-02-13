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
 * @mixin \MvcCore\Ext\ModelForms\Form
 */
trait Features {
	use \MvcCore\Ext\ModelForms\Form\Props,
		\MvcCore\Ext\ModelForms\Form\PropsFormNamespaces,
		\MvcCore\Ext\ModelForms\Form\PropsSubmit,
		\MvcCore\Ext\ModelForms\Form\PropsFieldsTags,
		\MvcCore\Ext\ModelForms\Form\PropsValidatorsTags,

		\MvcCore\Ext\ModelForms\Form\GettersSetters,
		\MvcCore\Ext\ModelForms\Form\FormMethods,
		\MvcCore\Ext\ModelForms\Form\ModelFormMethods,
		\MvcCore\Ext\ModelForms\Form\ModelFormInitMethods,
		\MvcCore\Ext\ModelForms\Form\ModelFormSubmitMethods;
}