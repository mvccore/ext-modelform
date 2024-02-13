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
trait PropsFieldsTags {

	/**
	 * Custom set of field classes to be able to decorate on model properties.
	 * This array is used only for older way how to decorate properties by PHP Docs tags.
	 * @var \string[]
	 */
	protected $fieldsTags = [
		'Checkbox'				=> 'MvcCore\\Ext\\Forms\\Fields\\Checkbox',
		'CheckboxGroup'			=> 'MvcCore\\Ext\\Forms\\Fields\\CheckboxGroup',
		'Color'					=> 'MvcCore\\Ext\\Forms\\Fields\\Color',
		'CountrySelect'			=> 'MvcCore\\Ext\\Forms\\Fields\\CountrySelect',
		'DataList'				=> 'MvcCore\\Ext\\Forms\\Fields\\DataList',
		'Date'					=> 'MvcCore\\Ext\\Forms\\Fields\\Date',
		'DateTime'				=> 'MvcCore\\Ext\\Forms\\Fields\\DateTime',
		'Email'					=> 'MvcCore\\Ext\\Forms\\Fields\\Email',
		'Hidden'				=> 'MvcCore\\Ext\\Forms\\Fields\\Hidden',
		'LocalizationSelect'	=> 'MvcCore\\Ext\\Forms\\Fields\\LocalizationSelect',
		'Month'					=> 'MvcCore\\Ext\\Forms\\Fields\\Month',
		'Number'				=> 'MvcCore\\Ext\\Forms\\Fields\\Number',
		'Password'				=> 'MvcCore\\Ext\\Forms\\Fields\\Password',
		'RadioGroup'			=> 'MvcCore\\Ext\\Forms\\Fields\\RadioGroup',
		'Range'					=> 'MvcCore\\Ext\\Forms\\Fields\\Range',
		'Search'				=> 'MvcCore\\Ext\\Forms\\Fields\\Search',
		'Select'				=> 'MvcCore\\Ext\\Forms\\Fields\\Select',
		'Tel'					=> 'MvcCore\\Ext\\Forms\\Fields\\Tel',
		'Text'					=> 'MvcCore\\Ext\\Forms\\Fields\\Text',
		'Textarea'				=> 'MvcCore\\Ext\\Forms\\Fields\\Textarea',
		'Time'					=> 'MvcCore\\Ext\\Forms\\Fields\\Time',
		'Url'					=> 'MvcCore\\Ext\\Forms\\Fields\\Url',
		'Week'					=> 'MvcCore\\Ext\\Forms\\Fields\\Week',
	];
}