<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

require_once XCCK_TRUST_PATH . '/admin/class/AbstractWizardAction.class.php';

/**
 * Xcck_Admin_ConfigCatWizardAction
**/
class Xcck_Admin_ConfigCatWizardAction extends Xcck_Admin_AbstractWizardAction
{
	protected $_mType = 'Cat';

	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewInput(&$render)
	{
		$render->setTemplateName('config_category2_wizard.html');
		$render->setAttribute('adminMenu', $this->mModule->getAdminMenu());
		$render->setAttribute('type', $this->_mType);
		$render->setAttribute('list', $this->_getList());
		$render->setAttribute('orderList', $this->_getOrderList());
		$render->setAttribute('showOrder', $this->mRoot->mContext->mModuleConfig['list_order']);
		$render->setAttribute('authType', $this->mRoot->mContext->mModuleConfig['auth_type']);
	}

	/**
	 * _doExecute
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	protected function _doExecute()
	{
		$this->_mNextName = 'Progress';
		$handler = xoops_gethandler('config');
	
		$authType = $this->mRoot->mContext->mRequest->getRequest('auth_type');
		if(! isset($authType)) $authType = "";
		$showOrder = $this->mRoot->mContext->mRequest->getRequest('list_order');
		if(! in_array($showOrder, $this->_getOrderList()) || ($this->_mType=='cat' &&count(explode('|', $authType))!=4)){
			return XCCK_FRAME_VIEW_ERROR;
		}
	
		$this->_insertConfig('auth_type', $authType);
		$this->_insertConfig('list_order', $showOrder);
	
		return XCCK_FRAME_VIEW_SUCCESS;
	}

	/**
	 * _getList
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	protected function _getList()
	{
		return Legacy_Utils::getCommonModuleList('cat');
	}

	/**
	 * _getOrderList
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	protected function _getOrderList()
	{
		return array('flat'=>'flat', 'category'=>'category');
	}
}

?>