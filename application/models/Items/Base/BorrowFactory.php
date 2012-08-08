<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Factory base trait
 */
trait BorrowFactory
{
	use \Sca\DataObject\Singleton;

	/**
	 * Factory initialization
	 *
	* @param	array	$aComponents	components descritpion
	 * @return	void
	 */
	protected function init(array &$aComponents = [])
	{
		$aComponents[] = \Model\Items\Borrow::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	int	iItemId
	 * @param	int	iFriendId
	 * @param	int	iStart
	 * @param	int	iEnd
	 * @return	\Model\Items\Borrow
	 */
	public function create($iItemId, $iFriendId, $iStart, $iEnd)
	{
		$aData = $this->prepareToCreate([$iItemId, $iFriendId, $iStart, $iEnd]);

		return $this->createNewElement($aData);
	}


	/**
	 * Prepare data to create
	 *
	 * @param	array	$aData	model data
	 * @return	array
	 */
	protected function prepareToCreate(array $aData)
	{
		return ['borrow' => [
				'b_item' => $aData[0],
				'b_friend' => $aData[1],
				'b_start' => $aData[2],
				'b_end' => $aData[3]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Items\Borrow
	 */
	public function buildElement()
	{
		return new \Model\Items\Borrow();
	}

	/**
	 * Return select object for model
	 *
	 * @param	mixed	$mFields	fields definition
	 * @param	array	$aOptions	other options
	 * @return	\Zend_Db_Select
	 */
	protected function getSelect($mFields = '*', array $aOptions = [])
	{
		$oSelect = parent::getSelect($mFields, $aOptions);

		if(in_array('item', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Items\Borrow::info();
			$aInfo = \Model\Items\Item::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.b_item'
			);

		}

		if(in_array('friend', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Items\Borrow::info();
			$aInfo = \Model\Friends\Friend::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.b_friend'
			);

		}



		return $oSelect;
	}



	/**
	 * Prepare data to build
	 *
	 * @param	array	$aRow		db row
	 * @param	array	$aOptions	build options
	 * @return	void
	 */
	protected function prepareToBuild(array &$aRow, array $aOptions = [])
	{
		if(in_array('item', $aOptions)) // preload standard field
		{
			$aRow['_item'] = (new \Model\Items\Item())->init($aRow);
		}

		if(in_array('friend', $aOptions)) // preload standard field
		{
			$aRow['_friend'] = (new \Model\Friends\Friend())->init($aRow);
		}


	}


}
