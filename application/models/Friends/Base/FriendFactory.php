<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Factory base trait
 */
trait FriendFactory
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
		$aComponents[] = \Model\Friends\Friend::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	string	sName
	 * @param	string	sSurname
	 * @return	\Model\Friends\Friend
	 */
	public function create($sName, $sSurname)
	{
		$aData = $this->prepareToCreate([$sName, $sSurname]);

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
		return ['friend' => [
				'f_name' => $aData[0],
				'f_surname' => $aData[1]
		]];
	}



// FACTORY METHODS

	/**
	 * Return data for one-to-many field
	 *
	 *@param	mixed	$mId	owner id/ids
	 * @return	array
	 */
	public function getGroupPeoples($mId)
	{
		if(empty($mId))
		{
			return array();
		}

		$aInfo = \Model\Friends\Group::info();
		$aThis = \Model\Friends\Friend::info();
		$oSelect = $this->getSelect()
							->join(
								'group_j_peoples AS gjp',
								'gjp.gjp_id = '. $aThis['alias'] .'.'. $aThis['key'],
								''
							);

		if(is_array($mId))
		{
			$oSelect->where('gjp.'. $aInfo['key'] .' IN(?)', $mId);
			$oSelect->group($aThis['alias'] .'.'. $aThis['key']);
			$oSelect->columns(new \Zend_Db_Expr('GROUP_CONCAT(gjp.'. $aInfo['key'] .') AS _j'));
		}
		else
		{
			$oSelect->where('gjp.'. $aInfo['key'] .' = ?', $mId);
		}

		$aDbRes = $oSelect->query()->fetchAll();

		$aResult = null;
		if(is_array($mId))
		{
			$aResult = array_fill_keys($mId, []);
			foreach($aDbRes as $aRow)
			{
				$oTmp = $this->buildObject($aRow);
				foreach(explode(',', $aRow['_j']) as $iId)
				{
					$aResult[$iId][] = $oTmp;
				}
			}
		}
		else
		{
			$aResult = $this->buildList($aDbRes);
		}

		return $aResult;
	}



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Friends\Friend
	 */
	public function buildElement()
	{
		return new \Model\Friends\Friend();
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

		if(in_array('details', $aOptions)) // component preload
		{
			$aThis = \Model\Friends\Friend::info();
			$aInfo = \Model\Friends\Details::info();
			$oSelect->joinLeft(
				$aInfo['table'] .' AS '. $aInfo['alias'],
				$aInfo['alias'] .'.'. $aInfo['key'] .' = '. $aThis['alias'] .'.'. $aThis['key']
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
		if(in_array('details', $aOptions)) // component preload
		{
			if(isset($aRow[\Model\Friends\Details::info()['key']]))
			{
				$aRow['_details'] = (new \Model\Friends\Details())->init($aRow);
			}
			else
			{
				$aRow['_details'] = false;
			}
		}


	}


}
