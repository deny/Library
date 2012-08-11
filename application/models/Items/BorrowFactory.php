<?php

/**
 * @namespace
 */
namespace Model\Items;

class BorrowFactory extends \Sca\DataObject\Factory
{
	use Base\BorrowFactory;

	/**
	 * Return last borrow input
	 *
	 * @param \Model\Items\Item		$oItem	borrowed item
	 * @return	\Model\Items\Item
	 */
	public function getLastForItem(\Model\Items\Item $oItem)
	{
		$aDbRes = $this->getSelect()
						->where('b_item = ?', $oItem->getId())
						->where('b_end IS NULL')
						->order('b_start DESC')
						->limit(1)->query()->fetchAll();

		if(empty($aDbRes))
		{
			throw new \Sca\DataObject\Exception('No item');
		}

		return $this->buildObject($aDbRes[0]);
	}
}
