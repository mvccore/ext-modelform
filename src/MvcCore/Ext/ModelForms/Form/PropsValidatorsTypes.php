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
trait PropsValidatorsTypes {

	/**
	 * Custom set of validator classes to be able to decorate on model properties.
	 * @var \string[]
	 */
	protected $validatorsTypes = [
		'Clear'			=> 'MvcCore\\Ext\\Forms\\Validators\\Clear',
		'Local'			=> 'MvcCore\\Ext\\Forms\\Validators\\Local',
		'Color'			=> 'MvcCore\\Ext\\Forms\\Validators\\Color',
		'Date'			=> 'MvcCore\\Ext\\Forms\\Validators\\Date',
		'DateTime'		=> 'MvcCore\\Ext\\Forms\\Validators\\DateTime',
		'Email'			=> 'MvcCore\\Ext\\Forms\\Validators\\Email',
		'FloatNumber'	=> 'MvcCore\\Ext\\Forms\\Validators\\FloatNumber',
		'IntNumber'		=> 'MvcCore\\Ext\\Forms\\Validators\\IntNumber',
		'MinMaxLength'	=> 'MvcCore\\Ext\\Forms\\Validators\\MinMaxLength',
		'MinMaxOptions'	=> 'MvcCore\\Ext\\Forms\\Validators\\MinMaxOptions',
		'Month'			=> 'MvcCore\\Ext\\Forms\\Validators\\Month',
		'Number'		=> 'MvcCore\\Ext\\Forms\\Validators\\Number',
		'Password'		=> 'MvcCore\\Ext\\Forms\\Validators\\Password',
		'Pattern'		=> 'MvcCore\\Ext\\Forms\\Validators\\Pattern',
		'Range'			=> 'MvcCore\\Ext\\Forms\\Validators\\Range',
		'SafeString'	=> 'MvcCore\\Ext\\Forms\\Validators\\SafeString',
		'Tel'			=> 'MvcCore\\Ext\\Forms\\Validators\\Tel',
		'Time'			=> 'MvcCore\\Ext\\Forms\\Validators\\Time',
		'Url'			=> 'MvcCore\\Ext\\Forms\\Validators\\Url',
		'ValueInOptions'=> 'MvcCore\\Ext\\Forms\\Validators\\ValueInOptions',
		'Week'			=> 'MvcCore\\Ext\\Forms\\Validators\\Week',
		'CompanyIdEu'	=> 'MvcCore\\Ext\\Forms\\Validators\\CompanyIdEu',
		'CompanyVatIdEu'=> 'MvcCore\\Ext\\Forms\\Validators\\CompanyVatIdEu',
		'CreditCard'	=> 'MvcCore\\Ext\\Forms\\Validators\\CreditCard',
		'Hex'			=> 'MvcCore\\Ext\\Forms\\Validators\\Hex',
		'Iban'			=> 'MvcCore\\Ext\\Forms\\Validators\\Iban',
		'Ip'			=> 'MvcCore\\Ext\\Forms\\Validators\\Ip',
		'ZipCode'		=> 'MvcCore\\Ext\\Forms\\Validators\\ZipCode',
	];
}