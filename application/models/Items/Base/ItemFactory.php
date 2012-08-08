<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Factory base trait
 */
trait ItemFactory
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
		$aComponents[] = \Model\Items\Item::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	string	sName
	 * @param	int	iCategoryId
	 * @param	string	sDescription
	 * @param	string	sStatus
	 * @return	\Model\Items\Item
	 */
	public function create($sName, $iCategoryId, $sDescription, $sStatus)
	{
		$aData = $this->prepareToCreate([$sName, $iCategoryId, $sDescription, $sStatus]);

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
		return ['item' => [
				'i_name' => $aData[0],
				'i_category' => $aData[1],
				'i_description' => $aData[2],
				'i_status' => $aData[3]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Items\Item
	 */
	public function buildElement()
	{
		return new \Model\Items\Item();
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

		if(in_array('category', $aOptions)) // zawiera pole
		{
			$aThis = \Model\Items\Item::info();
			$aInfo = \Model\Items\Category::info();
			$oSelect->join(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.i_category'
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
		if(in_array('category', $aOptions)) // preload standard field
		{
			$aRow['_category'] = (new \Model\Items\Category())->init($aRow);
		}


	}


}
