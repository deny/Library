<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Base trait
 */
trait Group
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sName = null;

	/**
	 * @var	array
	 */
	private $aPeoples = null;


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

		$this->sName = $aRow['g_name'];



		if(isset($aRow['_peoples']))
		{
			$this->oPeoples = $aRow['_peoples'];
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
	 * @return	array
	 */
	public function getPeoples()
	{
		if(!isset($this->oPeoples))
		{
			$this->oPeoples = \Model\Friends\FriendFactory::getInstance()->getGroupPeoples($this->getId());
		}

		return $this->oPeoples;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'g_name', $mValue);
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
			'table' => 'group',
			'alias'	=> 'g',
			'key'	=> 'g_id'
		];
	}
}
