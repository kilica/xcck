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

require_once XCCK_TRUST_PATH . '/class/AbstractAction.class.php';

/**
 * Xcck_Admin_AbstractWizardAction
**/
abstract class Xcck_Admin_AbstractWizardAction extends Xcck_AbstractAction
{
	protected $_mNextName = null;
	protected $_mBackName = null;

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		return XCCK_FRAME_VIEW_INPUT;
	}

	/**
	 * execute
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function execute()
	{
		if ($this->mRoot->mContext->mRequest->getRequest('_form_control_cancel') != null)
		{
			return XCCK_FRAME_VIEW_CANCEL;
		}
	
		return $this->_doExecute();
	}

	protected function _insertConfig($key, $value)
	{
		$iHandler = xoops_gethandler('configitem');
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('conf_modid', $this->mRoot->mContext->mModule->mXoopsModule->get('mid')));
		$cri->add(new Criteria('conf_name', $key));
		$objs = $iHandler->getObjects($cri);
		if(count($objs)>0){
			$obj =array_shift($objs);
		}
		elseif(count($objs)==0){
			$obj = $handler->create();
			$obj->set('conf_modid', $this->mRoot->mContext->mModule->mXoopsModule->get('mid'));
			$obj->set('conf_name', $key);
		}
	
		$obj->set('conf_value', $value);
		$iHandler->insert($obj);
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(&$render)
	{
		$this->mRoot->mController->executeForward($this->_getForwardUrl());
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewError(&$render)
	{
		$this->mRoot->mController->executeForward($this->_getBackwardUrl());
	}

	/**
	 * executeViewCancel
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewCancel(&$render)
	{
		$this->mRoot->mController->executeForward($this->_getBackwardUrl());
	}

	/**
	 * _getForwardUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getForwardUrl()
	{
		return XOOPS_MODULE_URL .'/'. $this->mAsset->mDirname .'/admin/index.php?action=Config'.$this->_mNextName.'Wizard';
	}

	/**
	 * _getBackwardUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBackwardUrl()
	{
		return XOOPS_MODULE_URL .'/'. $this->mAsset->mDirname .'/admin/index.php?action=Config'.$this->_mBackName.'Wizard';
	}
}

?>