<?php

class DetailsController extends Sca_Controller_Action
{
	/**
	 * Allowed sort types
	 *
	 * @var	array
	 */
	protected $aAllowSort = [
		'id' => 'fcd_id',
		'email' => 'fcd_email',
		'phone' => 'fcd_phone',
		'city' => 'fcd_city',
		'address' => 'fcd_address',

	];

	/**
	 * Init
	 */
	public function init()
	{
		$this->prepareController(
			'Details',
			\Model\Friends\DetailsFactory::getInstance(),
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
		$oPaginator = $this->getPaginator($iPage, $sDbSort);

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
	 * Adds item to db
	 */
	public function addAction()
	{
		$this->_helper->viewRenderer('form');
		$this->view->assign('bEdit', false);

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter(false);

			if($oFilter->isValid())
			{
				$aData = $oFilter->getEscaped();

				$this->oFactory->create(
					$aData['email'],
					$aData['phone'],
					$aData['city'],
					$aData['address']
				);

				$this->addMessage('Item successful added', self::MSG_OK);
				$this->_redirect($this->getUrl([], 'list'));
				exit();
			}

			$this->addMessage('Correct wrong fields', self::MSG_ERROR);
			$this->showFormMessages($oFilter);
		}
	}

	/**
	 * Edit item
	 */
	public function editAction()
	{
		$this->_helper->viewRenderer('form');
		$this->view->assign('bEdit', true);

		$oItem = $this->getItem();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter(true);

			if($oFilter->isValid())
			{
				$aData = $oFilter->getEscaped();

				$oItem->setEmail($aData['email']);
				$oItem->setPhone($aData['phone']);
				$oItem->setCity($aData['city']);
				$oItem->setAddress($aData['address']);
				$oItem->save();

				$this->addMessage('Item successful changed', self::MSG_OK);
				$this->_redirect($this->getUrl([], 'list'));
				exit();
			}

			$this->addMessage('Correct wrong fields', self::MSG_ERROR);
			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', [
				'email' => $oItem->getEmail(),
				'phone' => $oItem->getPhone(),
				'city' => $oItem->getCity(),
				'address' => $oItem->getAddress()
			]);
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
			'email' => [
				
			],
			'phone' => [
				
			],
			'city' => [
				
			],
			'address' => [
				
			]
		];

		if(!$bEdit) // if add
		{

		}

		$aFitlers = [
			'*' => 'StringTrim'
		];

		// filter
		return new Zend_Filter_Input($aFitlers, $aValidators, $aValues);
	}
}
