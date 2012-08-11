<?php

class DetailsController extends Sca_Controller_Action
{
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
	 * Edit item
	 */
	public function editAction()
	{
		$this->_helper->viewRenderer('form');

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

				$this->addMessage('Details successful changed', self::MSG_OK);
				$this->_redirect('/friends/list');
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
	 * @see \Sca\Controller\Action::getItem
	 */
	protected function getItem()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oItem = \Model\Friends\FriendFactory::getInstance()->getOne($iId);
		}
		catch(Exception $oExc)
		{
			$this->moveTo404();
		}

		return $oItem->getDetails();
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
				new Zend_Validate_StringLength(['max' => 50]),
				new Zend_Validate_EmailAddress(),
				'allowEmpty' => true
			],
			'phone' => [
				new Zend_Validate_StringLength(['max' => 12]),
				'allowEmpty' => true
			],
			'city' => [
				new Zend_Validate_StringLength(['max' => 80]),
				'allowEmpty' => true
			],
			'address' => [
				new Zend_Validate_StringLength(['max' => 120]),
				'allowEmpty' => true
			]
		];

		$aFitlers = [
			'*' => 'StringTrim'
		];

		// filter
		return new Zend_Filter_Input($aFitlers, $aValidators, $aValues);
	}
}
