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
 * Xcck_Admin_IndexAction
**/
class Xcck_Admin_IndexAction extends Xcck_AbstractAction
{
	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		return XCCK_FRAME_VIEW_SUCCESS;
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
		$render->setTemplateName('admin.html');
		$render->setAttribute('adminMenu', $this->mModule->getAdminMenu());
		$render->setAttribute('xcckList', $this->_getXcckList());
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
		$list = array('');
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
