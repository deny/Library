<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Base trait
 */
trait Item
{
	// CONST STATUS_FREE = 'free';
	// CONST STATUS_LENT = 'lent';



// FIELDS

	/**
	 * @var	string
	 */
	private $sName = null;

	/**
	 * @var	int
	 */
	private $iCategoryId = null;

	/**
	 * @var	\Model\Items\Category
	 */
	private $oCategory = null;

	/**
	 * @var	string
	 */
	private $sDescription = null;

	/**
	 * @var	string
	 */
	private $sStatus = null;


// INITIALIZATION

	/**
	 * Model initialziation
	 *
	 * @param	array	$aRow			row from DB
	 * @param	array	$aComponents	components desc
	 */
	public function init(array &$aRow, array &$aComponents = [])
	{
		$aComponents[] = self::info();
		parent::init($aRow, $aComponents);

		$this->sName = $aRow['i_name'];
		$this->iCategoryId = $aRow['i_category'];
		$this->sDescription = $aRow['i_description'];
		$this->sStatus = $aRow['i_status'];



		if(isset($aRow['_category']))
		{
			$this->oCategory = $aRow['_category'];
		}



		return $this;
	}


// GETTERS

	/**
	 * @return	string
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * @return	\Model\Items\Category
	 */
	public function getCategoryId()
	{
		return $this->iCategoryId;
	}

	/**
	 * @return	\Model\Items\Category
	 */
	public function getCategory()
	{
		if(!isset($this->oCategory))
		{
			$this->oCategory = \Model\Items\CategoryFactory::getInstance()->getOne($this->iCategoryId);
		}

		return $this->oCategory;
	}

	/**
	 * @return	string
	 */
	public function getDescription()
	{
		return $this->sDescription;
	}

	/**
	 * @return	string
	 */
	public function getStatus()
	{
		return $this->sStatus;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'i_name', $mValue);
		return $this;
	}

	/**
	 * @param	{*field-type*}	$mValue		new value
	 * @return	void
	 */
	public function setCategoryId($mValue)
	{
		$this->iCategoryId = $mValue;
		$this->oCategory = null;
		$this->setDataValue(self::info()['table'], 'i_category', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setDescription($mValue)
	{
		$this->sDescription = $mValue;
		$this->setDataValue(self::info()['table'], 'i_description', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setStatus($mValue)
	{
		$this->sStatus = $mValue;
		$this->setDataValue(self::info()['table'], 'i_status', $mValue);
		return $this;
	}


// STATIC

	/**
	 * Return model DB information
	 *
	 * @return	array
	 */
	public static function info()
	{
		return [
			'table' => 'item',
			'alias'	=> 'i',
			'key'	=> 'i_id'
		];
	}
}
