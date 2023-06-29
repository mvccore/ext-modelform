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

use \MvcCore\Ext\ModelForms\IForm as IModelForm;

/**
 * @mixin \MvcCore\Ext\ModelForms\Form
 */
trait PropsSubmit {

	/**
	 * Model form submit result values for create, edit and delete.
	 * @var \int[]
	 */
	protected static $submitResultManipulationFlags = [
		IModelForm::RESULT_SUCCESS_CREATE,
		IModelForm::RESULT_SUCCESS_EDIT,
		IModelForm::RESULT_SUCCESS_DELETE,
		IModelForm::RESULT_SUCCESS_COPY
	];
	
	/**
	 * Default model form submit button(s) field names.
	 * @var array
	 */
	protected $submitNames = [
		'create'	=> 'create',
		'edit'		=> 'save',
		'delete'	=> 'delete',
		'copy'		=> 'copy',
	];
	
	/**
	 * Default model form submit button(s) field texts.
	 * @var array
	 */
	protected $submitTexts = [
		'create'	=> 'Create',
		'edit'		=> 'Save',
		'delete'	=> 'Delete',
		'copy'		=> 'Copy',
	];
	
	/**
	 * Default client error message texts. Replacement `{0}` 
	 * is always replaced by managed model full class name.
	 * Array have keys `create`, `edit` and `delete` 
	 * with not translated error message texts.
	 * @var array
	 */
	protected $defaultClientErrorMessages = [
		'create'	=> "Error when creating new database record `{0}`.",
		'edit'		=> "Error when saving database record `{0}`.",
		'delete'	=> "Error when removing database record `{0}`.",
		'copy'		=> "Error when copying database record `{0}`.",
	];
}