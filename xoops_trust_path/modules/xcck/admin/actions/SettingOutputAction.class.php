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
 * Xcck_Admin_ConfigProgressWizardAction
**/
class Xcck_Admin_SettingOutputAction extends Xcck_AbstractAction
{
    /**
     * getDefaultView
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    public function getDefaultView()
    {
        return XCCK_FRAME_VIEW_SUCCESS;
    }

	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(&$render)
	{
		$render->setTemplateName('setting_output.html');
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('definitions', Legacy_Utils::getModuleHandler('definition', $this->mAsset->mDirname)->getFields());
		$render->setAttribute('xcckConfigs', $this->mRoot->mContext->mModuleConfig);
	}
}

?>