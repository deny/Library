<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Base trait
 */
trait Details
{


// FIELDS

	/**
	 * @var	string
	 */
	private $sEmail = null;

	/**
	 * @var	string
	 */
	private $sPhone = null;

	/**
	 * @var	string
	 */
	private $sCity = null;

	/**
	 * @var	string
	 */
	private $sAddress = null;


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

		$this->sEmail = $aRow['fcd_email'];
		$this->sPhone = $aRow['fcd_phone'];
		$this->sCity = $aRow['fcd_city'];
		$this->sAddress = $aRow['fcd_address'];





		return $this;
	}

	/**
	 * Model default initialziation
	 *
	 * @param	\Model\Friends\Friend	$oOwner	owner
	 */
	public function initDefault(\Model\Friends\Friend $oOwner)
	{
		$aComponents = [self::info()];
		$aTmp = [
			'fcd_id'	=> $oOwner->getId(),
			'fcd_email' => $this->sEmail,
			'fcd_phone' => $this->sPhone,
			'fcd_city' => $this->sCity,
			'fcd_address' => $this->sAddress
		];
		parent::initDefault($aTmp, $aComponents);
		return $this;
	}


// GETTERS

	/**
	 * @return	string
	 */
	public function getEmail()
	{
		return $this->sEmail;
	}

	/**
	 * @return	string
	 */
	public function getPhone()
	{
		return $this->sPhone;
	}

	/**
	 * @return	string
	 */
	public function getCity()
	{
		return $this->sCity;
	}

	/**
	 * @return	string
	 */
	public function getAddress()
	{
		return $this->sAddress;
	}


// SETTERS

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setEmail($mValue)
	{
		$this->sEmail = $mValue;
		$this->setDataValue(self::info()['table'], 'fcd_email', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setPhone($mValue)
	{
		$this->sPhone = $mValue;
		$this->setDataValue(self::info()['table'], 'fcd_phone', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setCity($mValue)
	{
		$this->sCity = $mValue;
		$this->setDataValue(self::info()['table'], 'fcd_city', $mValue);
		return $this;
	}

	/**
	 * @param	string	$mValue		new value
	 * @return	void
	 */
	public function setAddress($mValue)
	{
		$this->sAddress = $mValue;
		$this->setDataValue(self::info()['table'], 'fcd_address', $mValue);
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
			'table' => 'friend_c_details',
			'alias'	=> 'fcd',
			'key'	=> 'fcd_id'
		];
	}
}
