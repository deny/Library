<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Base trait
 */
trait Friend
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sName = null;

	/**
	 * @var	string
	 */
	private $sSurname = null;

	/**
	 * @var	\Model\Friends\Details
	 */
	private $oDetails = null;


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

		$this->sName = $aRow['f_name'];
		$this->sSurname = $aRow['f_surname'];


		if(isset($aRow['_details']))
		{
			$this->oDetails = $aRow['_details'] ?
									$aRow['_details'] :
									(new \Model\Friends\Details())->initDefault($this);
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
	 * @return	string
	 */
	public function getSurname()
	{
		return $this->sSurname;
	}

	/**
	 * @return	\Model\Friends\Details
	 */
	public function getDetails()
	{
		if(!isset($this->oDetails))
		{
			try
			{
				$this->oDetails = \Model\Friends\DetailsFactory::getInstance()->getOne($this->getId());
			}
			catch(\Sca\DataObject\Exception $oExc) // no data - create default object
			{
				$this->oDetails = (new \Model\Friends\Details())->initDefault($this);
			}
		}

		return $this->oDetails;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setName($mValue)
	{
		$this->sName = $mValue;
		$this->setDataValue(self::info()['table'], 'f_name', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setSurname($mValue)
	{
		$this->sSurname = $mValue;
		$this->setDataValue(self::info()['table'], 'f_surname', $mValue);
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
			'table' => 'friend',
			'alias'	=> 'f',
			'key'	=> 'f_id'
		];
	}
}
