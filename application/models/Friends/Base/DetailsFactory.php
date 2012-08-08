<?php

/**
 * @namespace
 */
namespace Model\Friends\Base;

/**
 * Factory base trait
 */
trait DetailsFactory
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
		$aComponents[] = \Model\Friends\Details::info();
		parent::init($aComponents);
	}

// CREATE METHODS


	/**
	 * Prepare data to create (empty in component)
	 *
	 * @param	array	$aData	model data
	 * @return	array
	 */
	protected function prepareToCreate(array $aData)
	{
	}



// FACTORY METHODS

	public function getForUser($oUser)
	{
		$aDbRes = $this->getSelect()
						->where('fcd_id = ?', $oUser->getId())
						->limit(1)->query()->fetchAll();

		$oResult = $this->buildElement();

		if(empty($aDbRes))
		{
			$oResult->initDefault($oUser);
		}
		else
		{
			$oResult->init($aDbRes[0]);
		}

		return $oResult;
	}


// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Friends\Details
	 */
	public function buildElement()
	{
		return new \Model\Friends\Details();
	}




}
