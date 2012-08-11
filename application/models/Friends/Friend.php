<?php

/**
 * @namespace
 */
namespace Model\Friends;

class Friend extends \Sca\DataObject\Element
{
	use Base\Friend;

	/**
	 * Return full name (name + surname)
	 *
	 * @return	string
	 */
	public function getFullName()
	{
		return $this->getName() . ' '. $this->getSurname();
	}
}
