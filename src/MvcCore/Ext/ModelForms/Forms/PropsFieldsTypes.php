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

trait PropsFieldsTypes {

	/**
	 * Custom set of field classes to be able to decorate on model properties.
	 * @var \string[]
	 */
	protected $fieldsTypes = [
		'MvcCore\\Ext\\Forms\\Fields\\Checkbox',
		'MvcCore\\Ext\\Forms\\Fields\\CheckboxGroup',
		'MvcCore\\Ext\\Forms\\Fields\\Color',
		'MvcCore\\Ext\\Forms\\Fields\\CountrySelect',
		'MvcCore\\Ext\\Forms\\Fields\\DataList',
		'MvcCore\\Ext\\Forms\\Fields\\Date',
		'MvcCore\\Ext\\Forms\\Fields\\DateTime',
		'MvcCore\\Ext\\Forms\\Fields\\Email',
		'MvcCore\\Ext\\Forms\\Fields\\Hidden',
		'MvcCore\\Ext\\Forms\\Fields\\LocalizationSelect',
		'MvcCore\\Ext\\Forms\\Fields\\Month',
		'MvcCore\\Ext\\Forms\\Fields\\Number',
		'MvcCore\\Ext\\Forms\\Fields\\Password',
		'MvcCore\\Ext\\Forms\\Fields\\RadioGroup',
		'MvcCore\\Ext\\Forms\\Fields\\Range',
		'MvcCore\\Ext\\Forms\\Fields\\Search',
		'MvcCore\\Ext\\Forms\\Fields\\Select',
		'MvcCore\\Ext\\Forms\\Fields\\Tel',
		'MvcCore\\Ext\\Forms\\Fields\\Text',
		'MvcCore\\Ext\\Forms\\Fields\\Textarea',
		'MvcCore\\Ext\\Forms\\Fields\\Time',
		'MvcCore\\Ext\\Forms\\Fields\\Url',
		'MvcCore\\Ext\\Forms\\Fields\\Week',
	];
}