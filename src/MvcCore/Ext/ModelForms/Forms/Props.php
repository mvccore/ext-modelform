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

trait Props {

	/**
	 * Model instance with database data to edit or delete, 
	 * empty new instance or `NULL` to create.
	 * @var \MvcCore\Ext\ModelForms\IModel|\MvcCore\Ext\Models\Model|NULL
	 */
	protected $modelInstance = NULL;
	
	/**
	 * Model instance full class name.
	 * @var string|\MvcCore\Ext\ModelForms\Model|NULL
	 */
	protected $modelClassFullName = NULL;

	/**
	 * Model properties flags, optional.
	 * @var int
	 */
	protected $modelPropsFlags = 0;

	/**
	 * Boolean about if form creates new model instance or not.
	 * @var bool|NULL
	 */
	protected $modelIsNew = NULL;

	/**
	 * Model interface full name.
	 * @var string
	 */
	protected $modelClassInterface = 'MvcCore\\Ext\\ModelForms\\IModel';
}