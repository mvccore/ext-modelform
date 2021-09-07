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

namespace MvcCore\Ext\ModelForms;

interface IModel extends \MvcCore\Ext\Models\Db\IModel {
	
	const PHP_DOCS_TAG_NAME_FIELD = '@field';

	const PHP_DOCS_TAG_NAME_VALIDATOR = '@validator';

	/**
	 * Get default form model properties flags.
	 * @return int
	 */
	public static function GetDefaultPropsFlags ();

	/**
	 * Get array with primary keys fields names and with unique keys fields names.
	 * @param  int $propsFlags
	 * @return \string[][]|NULL[]|array
	 */
	public static function GetUniqueFieldsNames ($propsFlags = 0);

	/**
	 * Get form model metadata.
	 * @param  int $propsFlags
	 * @return \MvcCore\Ext\ModelForms\Models\PropertyMeta[]|array
	 */
	public static function GetFormsMetaData ($propsFlags = 0);
}