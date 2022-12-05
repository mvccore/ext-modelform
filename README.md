# MvcCore - Extension - ModelForm

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.1.32-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-modelform/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

MvcCore form extension to build forms by decorated model classes.

## Installation
```shell
composer require mvccore/ext-modelform
```

## Features
- Simply creating form instance with fields by decorated model properties  
  without extending `\MvcCore\Ext\Form` or \MvcCore\Ext\ModelForms\Form` class.
- Possibility to get model properties metadata by `\MvcCore\Ext\ModelForms\IModel::GetFormsMetaData()`.
- Model properties decoration by PHP 8+ attributes or by PHP Docs tags in older PHP versions.
- Possibility to decorate model property by any MvcCore form field with field options.
- Possibility to decorate options loader for fields with options like Select or other...
- Possibility to decorate model property by any MvcCore form validator(s) or by multiple validators with it's options.
- Possibility to decorate local validation method for any field, validation method  
  could be placed in form, controller or model as instance or static method, method context is configurable.
- Possibility to extend model form or it's protected methods to customize fields initiatization.

## Basic Example

### PHP >= 8 With Attributes Decorations

#### Bootstrap.php
```php
<?php

namespace App;

class Bootstrap {
	public static function Init (): \MvcCore\Application {
		// ...		
		
		// PHP 8+ and Attributes anotations are not enabled by default:
		\MvcCore\Tool::SetAttributesAnotations(TRUE);

		// ...
	}
}
```

#### Model Class
```php
namespace App\Models;

use \MvcCore\Ext\Models\Db\Attrs;

#[Attrs\Table('cds')]
class Album extends \MvcCore\Ext\Models\Db\Models\MySql {

	use \MvcCore\Ext\ModelForms\Models\Features;

	#[Attrs\Column('id_album'), Attrs\KeyPrimary]
	protected int $idAlbum;

	#[Attrs\Column('title'), Attrs\KeyUnique]
	#[Fields\Text([
		'label'		=> 'Title',
		'maxLength'	=> 200,
	])]
	protected string $title;

	#[Attrs\Column('interpret')]
	#[Fields\Text([
		'label'		=> 'Interpret',
		'maxLength'	=> 200,
	])]
	protected string $interpret;

	#[Attrs\Column('year')]
	#[Fields\Number([
		'label'		=> 'Year',
		'min'		=> 1900,
		'max'		=> 2030,
	])]
	#[Validators\Clear]
	#[Validators\IntNumber]
	protected ?int $year;

	// ...getters and setters could be anything...

	public function GetIdAlbum (): int {
		return $this->idAlbum;
	}

	public static function GetById (int $id): ?static {
		return self::GetConnection()
			->Prepare([
				"SELECT c.*		",
				"FROM cds c		",
				"WHERE c.id_album = :id;",
			])
			->FetchOne([':id' => $id])
			->ToInstance(static::class);
	}
}
```
#### Controller Class

```php

namespace App\Controllers;

use App\Models;

class CdCollection extends \MvcCore\Controller {

	protected ?\MvcCore\Ext\ModelForms\Form $form = NULL;

	protected ?\App\Models\Album $album = NULL;
	
	public function CreateAction (): void {
		$this->createForm(TRUE);
		$this->view->form = $this->form;
	}

	public function EditAction (): void {
		$this->initAlbumByParam();
		$this->createForm(FALSE);
		$this->form->SetModelInstance($this->album);
		$this->view->form = $this->form;
	}

	public function SubmitCreateAction () {
		$this->createForm(TRUE);
		$this->submit();
	}
	
	public function SubmitEditAction () {
		$this->initAlbumByParam();
		$this->createForm(FALSE);
		$this->form->SetModelInstance($this->album);
		$this->submit();
	}

	protected function initAlbumByParam () {
		$id = $this->GetParam('id', '[0-9]', NULL, 'int');
		if ($id === NULL) throw new \Exception("Album not found.");
		$this->album = \App\Models\Album::GetById($id);
	}

	protected function submit () {
		$this->form->Submit();
		$this->form->SubmittedRedirect();
	}

	protected function createForm (bool $createNew): void {
		$this->form = new \MvcCore\Ext\ModelForms\Form;
		if ($createNew) {
			$this->form->SetAction($this->Url(':SubmitCreate'));
			$this->form->SetErrorUrl($this->Url(':Create'));
		} else {
			$urlParams = ['id' => $this->album->GetIdAlbum()];
			$this->form->SetAction($this->Url(':SubmitEdit', $urlParams));
			$this->form->SetErrorUrl($this->Url(':Edit', $urlParams));
		}
		$this->form->SetSuccessUrl($this->Url(':Index'));
	}
}
```


### PHP < 8 With PHP Docs Decorations

#### Model Class
```php
namespace App\Models;

/**
 * @table cds
 */
class Album extends \MvcCore\Ext\Models\Db\Models\MySql {

	/**
	 * @var int 
	 * @column idAlbum 
	 * @keyPrimary
	 */
	protected $idAlbum;

	/**
	 * @var string 
	 * @column title
	 * @keyUnique
	 * @validator Text({
	 *	"label"	    : "Title",
	 *	"maxLength": 200,
	 * })
	 */
	#[Fields\Text([
		'label'		=> 'Title',
		'maxLength'	=> 200,
	])]
	protected $title;

	/**
	 * @var string 
	 * @column interpret
	 * @validator Text({
	 *	"label"	    : "Interpret",
	 *	"maxLength": 200,
	 * })
	 */
	protected $interpret;

	/**
	 * @var ?int 
	 * @column year
	 * @field Number({
	 * 	"label":"Year",
	 * 	"min"  :1900,
	 * 	"max"  :2030
	 * })
	 * @validator Clear, IntNumber
	 */
	protected $year = NULL;

	// ...getters and setters could be anything...

	/** @return int */
	public function GetIdAlbum () {
		return $this->idAlbum;
	}

	/**
	 * @param int $id
	 * @return \App\Models\Album|NULL
	 */
	public static function GetById ($id) {
		return self::GetConnection()
			->Prepare([
				"SELECT c.*		",
				"FROM cds c		",
				"WHERE c.id_album = :id;",
			])
			->FetchOne([':id' => $id])
			->ToInstance(static::class);
	}
}
```