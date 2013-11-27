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

require_once XCCK_TRUST_PATH . '/class/AbstractDeleteAction.class.php';

/**
 * Xcck_PageDeleteAction
**/
class Xcck_PageDeleteAction extends Xcck_AbstractDeleteAction
{
	protected /*** int ***/ $_mPageId = 0;

	/**
	 * _getCatId
	 * 
	 * @param	void
	 * 
	 * @return	int
	**/
	protected function _getCatId()
	{
		if($this->_isSubtable()){
            $this->mObject->loadMaintable();
			return $this->mObject->mMaintable->get('category_id');
		}
		else{
			return $this->mObject->get('category_id');
		}
	}

	/**
	 * _getPageTitle
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getPagetitle()
	{
		return $this->mObject->getShow('title');
	}

	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_PageHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Page');
		return $handler;
	}

	/**
	 * hasPermission
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function hasPermission()
	{
		$catId = $this->_getCatId();
	
		if($catId>0){
			//is Manager ?
			if($this->mCategoryManager->check($catId, Xcck_AuthType::MANAGE, 'page')==true){
				return true;
			}
			//is new post and has post permission ?
			if($this->mCategoryManager->check($catId, Xcck_AuthType::POST, 'page')==true){
				return true;
			}
			//is old post and your post ?
			if($check==true && ! $this->mObject->isNew() && $this->mObject->get('uid')==Legacy_Utils::getUid()){
					return true;
			}
		}
	}

	/**
	 * _setupActionForm
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	protected function _setupActionForm()
	{
		$this->mActionForm =& $this->mAsset->getObject('form', 'Page',false,'delete');
		$this->mActionForm->prepare();
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function prepare()
	{
		parent::prepare();
		$this->_mPageId = $this->mObject->get('page_id');
		$this->_setupCategoryManager('page');
	
		return true;
	}

	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_page_delete.html');
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('accessController', $this->mCategoryManager);
		$render->setAttribute('isSubtable', $this->_isSubtable());

		//setup contents tree
		$isHierarchical = $this->mRoot->mContext->mModuleConfig['hierarchical'];
		$render->setAttribute('isHierarchical', $isHierarchical);
		if($isHierarchical==1){
			$pagePath = $this->mObject->loadPath();
			$tree = $this->mObjectHandler->getTree($this->mObject->getTopId());
			array_shift($tree);
			$render->setAttribute('pageTree', $tree);
		}
    
    	$render->setAttribute('xoops_breadcrumbs', $this->_getBreadcrumb($this->mObject));
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
        $url = null;
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Event.GetForwardUri.Delete.Success', new XCube_Ref($url), $this->mObject);
        if(isset($url)){
            $this->mRoot->mController->executeForward($url);
        }

        $this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname));
	}

	/**
	 * executeViewError
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeRedirect($this->_getNextUri('page', 'list'), 1, _MD_XCCK_ERROR_DBUPDATE_FAILED);
	}

	/**
	 * executeViewCancel
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeForward($this->_getNextUri('page', 'list'));
	}
}

?>
