<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Base trait
 */
trait Borrow
{


// FIELDS

	/**
	 * @var	int
	 */
	private $iItemId = null;

	/**
	 * @var	\Model\Items\Item
	 */
	private $oItem = null;

	/**
	 * @var	int
	 */
	private $iFriendId = null;

	/**
	 * @var	\Model\Friends\Friend
	 */
	private $oFriend = null;

	/**
	 * @var	int
	 */
	private $iStart = null;

	/**
	 * @var	int
	 */
	private $iEnd = null;


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

		$this->iItemId = $aRow['b_item'];
		$this->iFriendId = $aRow['b_friend'];
		$this->iStart = $aRow['b_start'];
		$this->iEnd = $aRow['b_end'];



		if(isset($aRow['_item']))
		{
			$this->oItem = $aRow['_item'];
		}

		if(isset($aRow['_friend']))
		{
			$this->oFriend = $aRow['_friend'];
		}



		return $this;
	}


// GETTERS

	/**
	 * @return	\Model\Items\Item
	 */
	public function getItemId()
	{
		return $this->iItemId;
	}

	/**
	 * @return	\Model\Items\Item
	 */
	public function getItem()
	{
		if(!isset($this->oItem))
		{
			$this->oItem = \Model\Items\ItemFactory::getInstance()->getOne($this->iItemId);
		}

		return $this->oItem;
	}

	/**
	 * @return	\Model\Friends\Friend
	 */
	public function getFriendId()
	{
		return $this->iFriendId;
	}

	/**
	 * @return	\Model\Friends\Friend
	 */
	public function getFriend()
	{
		if(!isset($this->oFriend))
		{
			$this->oFriend = \Model\Friends\FriendFactory::getInstance()->getOne($this->iFriendId);
		}

		return $this->oFriend;
	}

	/**
	 * @return	int
	 */
	public function getStart()
	{
		return $this->iStart;
	}

	/**
	 * @return	int
	 */
	public function getEnd()
	{
		return $this->iEnd;
	}


// SETTERS

	/**
	 * @param	{*field-type*}	$mValue		new value
	 * @return	void
	 */
	public function setItemId($mValue)
	{
		$this->iItemId = $mValue;
		$this->oItem = null;
		$this->setDataValue(self::info()['table'], 'b_item', $mValue);
		return $this;
	}

	/**
	 * @param	{*field-type*}	$mValue		new value
	 * @return	void
	 */
	public function setFriendId($mValue)
	{
		$this->iFriendId = $mValue;
		$this->oFriend = null;
		$this->setDataValue(self::info()['table'], 'b_friend', $mValue);
		return $this;
	}

	/**
	 * @param	int	$mValue		new value
	 * @return	void
	 */
	public function setStart($mValue)
	{
		$this->iStart = $mValue;
		$this->setDataValue(self::info()['table'], 'b_start', $mValue);
		return $this;
	}

	/**
	 * @param	int	$mValue		new value
	 * @return	void
	 */
	public function setEnd($mValue)
	{
		$this->iEnd = $mValue;
		$this->setDataValue(self::info()['table'], 'b_end', $mValue);
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
			'table' => 'borrow',
			'alias'	=> 'b',
			'key'	=> 'b_id'
		];
	}
}
