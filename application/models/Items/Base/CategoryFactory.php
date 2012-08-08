<?php

/**
 * @namespace
 */
namespace Model\Items\Base;

/**
 * Factory base trait
 */
trait CategoryFactory
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
		$aComponents[] = \Model\Items\Category::info();
		parent::init($aComponents);
	}

// CREATE METHODS

	/**
	 * Create object
	 *
	 * @param	string	sName
	 * @return	\Model\Items\Category
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
		return ['category' => [
				'c_name' => $aData[0]
		]];
	}



// FACTORY METHODS



// OTHER

	/**
	 * Build new model object
	 *
	 * @return	\Model\Items\Category
	 */
	public function buildElement()
	{
		return new \Model\Items\Category();
	}




}
