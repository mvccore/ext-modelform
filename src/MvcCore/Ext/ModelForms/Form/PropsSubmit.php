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
trait PropsSubmit {
	
	/**
	 * Default model form submit button(s) field names.
	 * @var array
	 */
	protected $submitNames = [
		'create'	=> 'create',
		'edit'		=> 'save',
		'delete'	=> 'delete',
	];
	
	/**
	 * Default model form submit button(s) field texts.
	 * @var array
	 */
	protected $submitTexts = [
		'create'	=> 'Create',
		'edit'		=> 'Save',
		'delete'	=> 'Delete',
	];
}