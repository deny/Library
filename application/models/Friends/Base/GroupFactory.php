<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Factory base trait
 */
trait GroupFactory
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
		$aComponents[] = \Model\Friends\Group::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	string	sName
	 * @return	\Model\Friends\Group
	 */
	public function create($sName)
	{
		$aData = $this->prepareToCreate([$sName]);

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
		return ['group' => [
				'g_name' => $aData[0]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Friends\Group
	 */
	public function buildElement()
	{
		return new \Model\Friends\Group();
	}


	/**
	 * Build model list
	 *
	 * @param	array	$aDbResult	database result
	 * @return	array
	 */
	protected function buildList(array &$aDbResult, array $aOptions = [])
	{
		if(empty($aDbResult))
		{
			return array();
		}

		if(in_array('peoples', $aOptions))
		{
			$aIds = [];
			foreach($aDbResult as $aRow)
			{
				$aIds[] = $aRow[\Model\Friends\Group::info()['key']];
			}

			$aTmp = \Model\Friends\FriendFactory::getInstance()->getGroupPeoples($aIds);

			foreach($aDbResult as &$aRow)
			{
				$aRow['_peoples'] = $aTmp[$aRow[\Model\Friends\Group::info()['key']]];
			}
		}



		return parent::buildList($aDbResult, $aOptions);
	}



}
