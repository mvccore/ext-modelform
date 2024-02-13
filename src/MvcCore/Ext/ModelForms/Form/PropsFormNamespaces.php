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
trait PropsFormNamespaces {

	/**
	 * Default form namespaces to have shorter form id session key.
	 * @var \string[]
	 */
	protected static $formsNamespaces = [
		'\\App\\Forms\\',
		'\\MvcCore\\Ext\\',
	];
	
	/**
	 * Form fields base namespaces to create fields instances by decorated class names.
	 * Field will be created by class existence in this namespaces order.
	 * This array is used only for newer way how to decorate properties by PHP attributes.
	 * @var \string[]
	 */
	protected static $fieldsNamespaces = [
		'\\MvcCore\\Ext\\Forms\\Fields\\'
	];

}