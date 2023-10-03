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

namespace MvcCore\Ext\ModelForms\Model;

/**
 * @mixin \MvcCore\Ext\ModelForms\Model|\MvcCore\Ext\Models\Db\Model
 */
trait Features {
	
	/**
	 * @inheritDoc
	 * @param  int $propsFlags
	 * @return \string[][]|NULL[]|array
	 */
	public static function GetUniqueFieldsNames ($propsFlags = 0) {
		if ($propsFlags === 0)
			$propsFlags = static::$defaultPropsFlags;
		list(
			$metaData, 
			$primaryFieldsIndexes, 
			$uniqueFieldsIndexes
		) = static::GetMetaData(
			$propsFlags, [
				\MvcCore\Ext\Models\Db\Model\IConstants::METADATA_PRIMARY_KEY, 
				\MvcCore\Ext\Models\Db\Model\IConstants::METADATA_UNIQUE_KEY
			]
		);
		$result = [NULL, NULL];
		if (count($primaryFieldsIndexes) > 0) {
			$fieldsNames = [];
			foreach ($primaryFieldsIndexes as $primaryFieldIndex)
				$fieldsNames[] = $metaData[$primaryFieldIndex][3];
			$result[0] = $fieldsNames;
		}
		if (count($uniqueFieldsIndexes) > 0) {
			$fieldsNames = [];
			foreach ($uniqueFieldsIndexes as $uniqueFieldIndex)
				$fieldsNames[] = $metaData[$uniqueFieldIndex][3];
			$result[1] = $fieldsNames;
		}
		return $result;
		
	}

	/**
	 * @inheritDoc
	 * @param  int $propsFlags
	 * @return \MvcCore\Ext\ModelForms\Models\PropertyMeta[]|array
	 */
	public static function GetFormsMetaData ($propsFlags = 0) {
		if ($propsFlags === 0)
			$propsFlags = static::$defaultPropsFlags;
		list(
			$metaData, 
			$sourceCodeIndexes
		) = static::GetMetaData(
			$propsFlags, [
				\MvcCore\Ext\Models\Db\Model\IConstants::METADATA_BY_CODE
			]
		);
		$result = [];
		foreach ($sourceCodeIndexes as $propName => $metaIndex) 
			$result[$propName] = new \MvcCore\Ext\ModelForms\Models\PropertyMeta(
				$metaData[$metaIndex]
			);
		return $result;
	}
}