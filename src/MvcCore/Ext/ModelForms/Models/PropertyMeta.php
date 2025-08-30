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

namespace MvcCore\Ext\ModelForms\Models;

class PropertyMeta {

	/**
	 * Property is private.
	 * @var bool
	 */
	public $IsPrivate;

	/**
	 * Property allow `NULL` value.
	 * @var bool
	 */
	public $AllowNulls;

	/**
	 * Property type(s), including `NULL` if allowed.
	 * @var \string[]
	 */
	public $Types;

	/**
	 * Property source code name.
	 * @var string
	 */
	public $Name;

	/**
	 * Property database column name if defined.
	 * @var ?string
	 */
	public $DbColumnName;

	/**
	 * Property additional parsing data.
	 * @var ?array<string>
	 */
	public $ParserData;

	/**
	 * Property additional formating data.
	 * @var ?array<string>
	 */
	public $FormatData;

	/**
	 * `TRUE` if property has defined primary key.
	 * @var bool
	 */
	public $IsPrimary;

	/**
	 * `TRUE` if property has defined autoincrement feature.
	 * @var bool
	 */
	public $AutoIncrement;

	/**
	 * `TRUE` if property has defined unique key 
	 * or string with database unique key name
	 * or `NULL` if propert has no unique key.
	 * @var bool|string|null
	 */
	public $IsUnique;

	/**
	 * `TRUE` if property has defined default value.
	 * @var bool
	 */
	public $HasDefault;

	/**
	 * Conversion of property cached metadata array item into class object.
	 * @param array $metaDataItem
	 */
	public function __construct ($metaDataItem) {
		list (
			$this->IsPrivate,
			$this->AllowNulls,
			$this->Types, 
			$this->Name, 
			$this->DbColumnName, 
			$this->ParserData, 
			$this->FormatData, 
			$this->IsPrimary, 
			$this->AutoIncrement, 
			$this->IsUnique, 
			$this->HasDefault
		) = $metaDataItem;
	}
}