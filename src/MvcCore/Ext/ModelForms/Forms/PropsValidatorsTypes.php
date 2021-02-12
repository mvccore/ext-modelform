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

trait PropsValidatorsTypes {

	/**
	 * Custom set of validator classes to be able to decorate on model properties.
	 * @var \string[]
	 */
	protected $validatorsTypes = [
		'MvcCore\\Ext\\Forms\\Validators\\Clear',
		'MvcCore\\Ext\\Forms\\Validators\\Local',
		'MvcCore\\Ext\\Forms\\Validators\\Color',
		'MvcCore\\Ext\\Forms\\Validators\\Date',
		'MvcCore\\Ext\\Forms\\Validators\\DateTime',
		'MvcCore\\Ext\\Forms\\Validators\\Email',
		'MvcCore\\Ext\\Forms\\Validators\\FloatNumber',
		'MvcCore\\Ext\\Forms\\Validators\\IntNumber',
		'MvcCore\\Ext\\Forms\\Validators\\MinMaxLength',
		'MvcCore\\Ext\\Forms\\Validators\\MinMaxOptions',
		'MvcCore\\Ext\\Forms\\Validators\\Month',
		'MvcCore\\Ext\\Forms\\Validators\\Number',
		'MvcCore\\Ext\\Forms\\Validators\\Password',
		'MvcCore\\Ext\\Forms\\Validators\\Pattern',
		'MvcCore\\Ext\\Forms\\Validators\\Range',
		'MvcCore\\Ext\\Forms\\Validators\\SafeString',
		'MvcCore\\Ext\\Forms\\Validators\\Tel',
		'MvcCore\\Ext\\Forms\\Validators\\Time',
		'MvcCore\\Ext\\Forms\\Validators\\Url',
		'MvcCore\\Ext\\Forms\\Validators\\ValueInOptions',
		'MvcCore\\Ext\\Forms\\Validators\\Week',
	];
}