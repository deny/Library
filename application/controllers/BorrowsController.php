<?php

class BorrowsController extends Sca_Controller_Action
{
	/**
	 * Allowed sort types
	 *
	 * @var	array
	 */
	protected $aAllowSort = [
		'id' => 'b_id',
		'item' => 'i_name',
		'friend' => 'CONCAT(f_name, " ", f_surname)',
		'start' => 'b_start',
		'end' => 'b_end',

	];

	/**
	 * Init
	 */
	public function init()
	{
		$this->prepareController(
			'Borrows',
			\Model\Items\BorrowFactory::getInstance(),
			10
		);

		parent::init();
	}

	/**
	 * Display items list
	 */
	public function listAction()
	{
	// page number
		$iPage = $this->_request->getParam('page', 1);
		if($iPage < 1)
		{
			$this->moveTo404();
		}

	// sort
		$sSort = $this->_request->getParam('sort');
		$sDbSort = current($this->aAllowSort) . ' ASC';
		$aUsedSort = array(0,0);
		if(!empty($sSort))
		{
			$aSort = explode(':', $sSort);
			if(count($aSort) == 2 && isset($this->aAllowSort[$aSort[0]]))
			{
				$sDbSort = $this->aAllowSort[$aSort[0]] . ($aSort[1] == 'desc' ? ' DESC' : ' ASC');
				$aUsedSort = $aSort;
			}
		}

	// get paginator
		$oPaginator = $this->getPaginator($iPage, $sDbSort, ['friend','item']);

	// set view
		$this->view->assign('oPaginator', $oPaginator);
		$this->view->assign('aParams', array(
			'page'	=> $iPage == 1 ? null : $iPage,
			'sort'	=> empty($sSort) ? null : $sSort
		));
		$this->view->assign('sAction', 'list');
		$this->view->assign('aUsedSort', $aUsedSort);
	}

	/**
	 * Borrow item
	 */
	public function borrowAction()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oItem = \Model\Items\ItemFactory::getInstance()->getOne($iId);

			if($oItem->getStatus() == \Model\Items\Item::STATUS_BORROWED)
			{
				$this->addMessage('Item is borrowed', self::MSG_ERROR);
				$this->_redirect('/items/list');
				exit();
			}
		}
		catch(Exception $oExc)
		{
			$this->moveTo404();
		}

		$this->_helper->viewRenderer('form');
		$this->view->assign('bEdit', false);

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter(false);

			if($oFilter->isValid())
			{
				$aData = $oFilter->getEscaped();

				$this->oFactory->create(
					$oItem->getId(),
					$aData['friend'],
					strtotime($aData['date']),
					null
				);

				$oItem->setStatus(\Model\Items\Item::STATUS_BORROWED);
				$oItem->save();

				$this->addMessage('Item successful borrowed', self::MSG_OK);
				$this->_redirect($this->getUrl([], 'list'));
				exit();
			}

			$this->addMessage('Correct wrong fields', self::MSG_ERROR);
			$this->showFormMessages($oFilter);
		}
	}

	/**
	 * Return item
	 */
	public function returnAction()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oBorrowItem = \Model\Items\ItemFactory::getInstance()->getOne($iId);

			if($oBorrowItem->getStatus() == \Model\Items\Item::STATUS_FREE)
			{
				$this->addMessage('Item is not borrowed', self::MSG_ERROR);
				$this->_redirect('/items/list');
				exit();
			}

			$oItem = $this->oFactory->getLastForItem($oBorrowItem);
		}
		catch(Exception $oExc)
		{
			$this->moveTo404();
		}

		$this->_helper->viewRenderer('form');
		$this->view->assign('bEdit', true);

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter(true);

			if($oFilter->isValid())
			{
				$aData = $oFilter->getEscaped();

				$oItem->setEnd(strtotime($aData['date']));
				$oItem->save();

				$oBorrowItem->setStatus(\Model\Items\Item::STATUS_FREE);
				$oBorrowItem->save();

				$this->addMessage('Item successful returned', self::MSG_OK);
				$this->_redirect($this->getUrl([], 'list'));
				exit();
			}

			$this->addMessage('Correct wrong fields', self::MSG_ERROR);
			$this->showFormMessages($oFilter);
		}
	}

	/**
	 * Delete item
	 */
	public function deleteAction()
	{
		$oItem = $this->getItem();
		$oItem->delete();

		$this->addMessage('Delete successful', self::MSG_OK);

		$this->_redirect($this->getUrl([], 'list'));
	}

	/**
	 * Return filter
	 *
	 * @param	bool	$bEdit
	 * @return	Zend_Filter_Input
	 */
	protected function getFilter($bEdit)
	{
		$aValues = $this->_request->getPost();

    	// validators
		$aValidators = [
			'date' => [
				new Zend_Validate_Date(['format' => 'Y-m-d'])
			]
		];

		if(!$bEdit) // if add
		{
			$aValidators['friend'] = [
				new Zend_Validate_Callback(function($iId) {

					try
					{
						\Model\Friends\FriendFactory::getInstance()->getOne($iId);
						return true;
					}
					catch(Exception $oExc) {
						return false;
					}

				})
			];
		}

		$aFitlers = [
			'*' => 'StringTrim'
		];

		// filter
		return new Zend_Filter_Input($aFitlers, $aValidators, $aValues);
	}
}
