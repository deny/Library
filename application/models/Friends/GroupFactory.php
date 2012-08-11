<?php

/**
 * @namespace
 */
namespace Model\Friends;

class GroupFactory extends \Sca\DataObject\Factory
{
	use Base\GroupFactory;


	/**
	 * Add friend to group
	 *
	 * @param 	\Model\Friends\Group 	$oGroup		group object
	 * @param 	\Model\Friends\Friend 	$oFriend	friend object
	 * @return	void
	 */
	public function addToGroup(\Model\Friends\Group $oGroup, \Model\Friends\Friend  $oFriend)
	{
		try
		{
			$this->oDb->insert(
				'group_j_peoples',
				array(
					'g_id' 		=> $oGroup->getId(),
					'gjp_id'	=> $oFriend->getId()
				)
			);
		} catch (\Exception $e) {}
	}

	/**
	 * Delete friend from group
	 *
	 * @param 	\Model\Friends\Group 	$oGroup		group object
	 * @param 	\Model\Friends\Friend 	$oFriend	friend object
	 * @return	void
	 */
	public function delFromGroup(\Model\Friends\Group $oGroup, \Model\Friends\Friend  $oFriend)
	{
		$oWhere= new \Sca\DataObject\Where('g_id = ?', $oGroup->getId());
		$oWhere->addAnd('gjp_id = ?', $oFriend->getId());

		$this->oDb->delete('group_j_peoples', $oWhere);
	}
}
