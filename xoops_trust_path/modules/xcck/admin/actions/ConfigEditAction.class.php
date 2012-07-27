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
	 * execute
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function execute()
	{
		$handler = xoops_gethandler('config');
	
		$list = $this->_getCategorySetList();
		$catSetList = get_array_key($list);
		$xcckList = $this->_getXcckList();
	
		if($this->mRoot->mContext->mRequest->getRequest('set_id')){
			$setId = $this->mRoot->mContext->mRequest->getRequest('set_id');
		}
	
		if($this->mRoot->mContext->mRequest->getRequest('maintable')){
			$maintable = $this->mRoot->mContext->mRequest->getRequest('maintable');
		}
	
		if(isset($setId) && in_array($setId, $catSetList)){
			$this->_insertConfig('set_id', $setId);
		}
	
		if(isset($maintable) && in_array($maintable, $xcckList)){
			$this->_insertConfig('maintable', $maintable);
		}
	}

	protected function _insertConfig($key, $value)
	{
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('conf_modid', $this->mRoot->mContext->mModule->mXoopsModule->get('id')));
		$cri->add(new Criteria('conf_name', $key));
		$objs = $handler->getObjects($cri);
		if(count($objs)>0){
			$obj =array_shift($objs);
		}
		elseif(count($objs)==0){
			$obj = $handler->create();
			$obj->set('conf_modid', $this->mRoot->mContext->mModule->mXoopsModule->get('id'));
			$obj->set('conf_name', $key);
		}
	
		$obj->set('conf_value', $value);
		$handler->insertConfig($obj);
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
	 * _getForwardUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getForwardUrl()
	{
		return './index.php';
	}

	/**
	 * _getCategorySetList
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	protected function _getCategorySetList()
	{
		return Legacy_Utils::getDirnameListByTrustDirname('lecat');
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
		return Legacy_Utils::getDirnameListByTrustDirname('xcck');
		
	}

}

?>
