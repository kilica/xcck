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
 * Xcck_Admin_ConfigProgressWizardAction
**/
class Xcck_Admin_ConfigProgressWizardAction extends Xcck_Admin_AbstractWizardAction
{
	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewInput(&$render)
	{
		$render->setTemplateName('config_progress_wizard.html');
		$render->setAttribute('adminMenu', $this->mModule->getAdminMenu());
		$render->setAttribute('list', $this->_getList());
		$render->setAttribute('publish', $this->mRoot->mContext->mModuleConfig['publish']);
		$render->setAttribute('threshold', $this->mRoot->mContext->mModuleConfig['threshold']);
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
		$this->_mNextName = 'Images';
		$handler = xoops_gethandler('config');
	
		$publish = $this->mRoot->mContext->mRequest->getRequest('publish');
		$threshold = $this->mRoot->mContext->mRequest->getRequest('threshold');
		switch($publish){
		case 'linear':
			$this->_insertConfig('threshold', 0);
			break;
		case 'rating':
			$this->_insertConfig('threshold', $threshold);
			break;
		case 'none':
			$this->_insertConfig('threshold', 0);
			break;
		default:
			return XCCK_FRAME_VIEW_ERROR;
		}
	
		$this->_insertConfig('publish', $publish);
	
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
		return array('none'=>'none', 'linear'=>'linear', 'rating'=>'rating');
	}
}

?>