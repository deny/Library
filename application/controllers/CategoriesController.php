<?php

class CategoriesController extends Sca_Controller_Action
{
	/**
	 * Allowed sort types
	 *
	 * @var	array
	 */
	protected $aAllowSort = [
		'id' => 'c_id',
		'name' => 'c_name',

	];

	/**
	 * Init
	 */
	public function init()
	{
		$this->prepareController(
			'Categories',
			\Model\Items\CategoryFactory::getInstance(),
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
					$aData['name']
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

				$oItem->setName($aData['name']);
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
				'name' => $oItem->getName()
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
			'name' => [
				new Zend_Validate_StringLength(['max' =>128])
			]
		];

		$aFitlers = [
			'*' => 'StringTrim'
		];

		// filter
		return new Zend_Filter_Input($aFitlers, $aValidators, $aValues);
	}
}
