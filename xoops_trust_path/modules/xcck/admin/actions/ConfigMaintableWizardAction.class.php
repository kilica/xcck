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
 * Xcck_Admin_ConfigMaintableWizardAction
**/
class Xcck_Admin_ConfigMaintableWizardAction extends Xcck_Admin_AbstractWizardAction
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
		$render->setTemplateName('config_maintable_wizard.html');
		$render->setAttribute('adminMenu', $this->mModule->getAdminMenu());
		$render->setAttribute('xcckList', $this->_getXcckList());
		$render->setAttribute('maintable', $this->mRoot->mContext->mModuleConfig['maintable']);
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
		$handler = xoops_gethandler('config');
	
		$maintable = $this->mRoot->mContext->mRequest->getRequest('maintable');
		if(! $maintable){
			$this->_insertConfig('maintable', $maintable);
			$server = $this->mModule->mModuleConfig['access_controller'];
			$mHandler = xoops_gethandler('module');
			if(isset($server)){
				$module = $mHandler->getByDirname($server);
				switch($module->get('role')){
				case 'cat':
					$this->_mNextName = 'Cat';
					break;
				case 'group':
					$this->_mNextName = 'Group';
					break;
				case 'default':
					$this->_mNextName = 'Progress';
					break;
				}
			}
			else{
				$this->_mNextName = 'Progress';
			}
		}
		elseif(in_array($maintable, $this->_getXcckList())){
			$this->_insertConfig('maintable', $maintable);
			$this->_insertConfig('show_order', 'flat');
			$this->_mNextName = 'Progress';
		}
		else{
			return XCCK_FRAME_VIEW_ERROR;
		}
	
		return XCCK_FRAME_VIEW_SUCCESS;
	}

	/**
	 * _getXcckList
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	protected function _getXcckList()
	{
		$list = array('');	//dummy
		$handler = xoops_gethandler('config');
	
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname('xcck');
		foreach($dirnames as $dirname){
			//check self ?
			if($dirname == $this->mAsset->mDirname) continue;
			//check maintable ?
			$conf = $handler->getConfigsByDirname($dirname);
			if($conf['maintable']) continue;
			
			$list[] = $dirname;
		}
		return $list;
	}
}

?>
