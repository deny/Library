<?php

/**
 * @namespace
 */
namespace Model\Items;

class Item extends \Sca\DataObject\Element
{
	use Base\Item;

	const STATUS_FREE = 'free';
	const STATUS_LENT = 'lent';


}
