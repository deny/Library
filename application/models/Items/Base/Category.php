<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Base trait
 */
trait Category
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sName = null;


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

		$this->sName = $aRow['c_name'];





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


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'c_name', $mValue);
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
			'table' => 'category',
			'alias'	=> 'c',
			'key'	=> 'c_id'
		];
	}
}
