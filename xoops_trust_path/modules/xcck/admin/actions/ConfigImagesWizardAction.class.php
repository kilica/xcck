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
 * Xcck_Admin_ConfigImagesWizardAction
**/
class Xcck_Admin_ConfigImagesWizardAction extends Xcck_Admin_AbstractWizardAction
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
		$render->setTemplateName('config_image_wizard.html');
		$render->setAttribute('adminMenu', $this->mModule->getAdminMenu());
		$render->setAttribute('images', $this->mRoot->mContext->mModuleConfig['images']);
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
		$this->_mNextName = 'Image';
		$handler = xoops_gethandler('config');
	
		$publish = $this->mRoot->mContext->mRequest->getRequest('images');
		$this->_insertConfig('images', $images);
	
		return XCCK_FRAME_VIEW_SUCCESS;
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
		return XOOPS_MODULE_URL .'/'. $this->mAsset->mDirname .'/admin/;
	}
}

?>