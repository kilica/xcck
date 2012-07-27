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

require_once XCCK_TRUST_PATH . '/actions/PageListAction.class.php';

/**
 * Xcck_RevisionShowAction
**/
class Xcck_RevisionShowAction extends Xcck_PageListAction
{
	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_PageHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Revision');
		return $handler;
	}

	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_PageFilterForm
	**/
	protected function &_getFilterForm()
	{
		$filter =& $this->mAsset->getObject('filter', 'revision', false);
		$filter->prepare($this->_getPageNavi(), $this->_getHandler());
		return $filter;
	}

	/**
	 * _getBaseUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBaseUrl()
	{
		return Legacy_Utils::renderUri($this->mAsset->mDirname, 'revision');
	}

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch($this->mAsset->mDirname);
		$handler = $this->_getHandler();
	
		if($this->mRoot->mContext->mModuleConfig['list_order']=='categorized' && $this->_getCatId()>0){
			$limit = 0;
			$start = 0;
		}
		else{
			$limit = null;
			$start = null;
		}
		
		$criteria=$this->mFilter->getCriteria($limit, $start);
	
		if($this->mRoot->mContext->mModuleConfig['hierarchical']){
			$criteria->add(new Criteria('p_id', 0));
		}
	
		//Site Owner can add optional condition for list by Delegate
		/*
		XCube_DelegateUtils::call(
			'Module.'.$this->mAsset->mDirname.'.SetupListCriteria',
			$criteria
		);
		*/
	
		$this->mObjects = $handler->getObjects($criteria);
		if($this->mRoot->mContext->mModuleConfig['list_order']=='categorized'){
			$this->_setupTree();
		}
		return XCCK_FRAME_VIEW_INDEX;
	}

	protected function _setupTree()
	{
		$this->mTree['category'] = $this->mCategoryManager->getTree(Xcck_AuthType::VIEW, $this->_getCatId());
		$this->mTree['page'] = array();
		foreach(array_keys($this->mTree['category']) as $key){
			$this->mTree['page'][$key] = array();
			foreach($this->mObjects as $obj){
				if($this->mTree['category'][$key]->get('cat_id')==$obj->get('category_id')){
					$this->mTree['page'][$key][] = $obj;
				}
			}
		}
	}

	/**
	 * set template
	 *
	 * @param	XCube_RenderTarget	&$render
	 *
	 * @return	void
	**/
	protected function _setTemplate(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_revision_show.html');
		$render->setAttribute('catTitleList', $this->mCategoryManager->getTitleList());
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->_setTemplate($render);
	
		//set template data
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('definitions', Legacy_Utils::getModuleHandler('definition', $this->mAsset->mDirname)->getFields(true));
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', 'page');
		$render->setAttribute('accessController', $this->mCategoryManager);
		$render->setAttribute('isSubtable', $this->_isSubtable());
		$render->setAttribute('moduleDescription', $this->mRoot->mContext->mModuleConfig['description']);
	
		$render->setAttribute('xoops_breadcrumbs', $this->_getBreadcrumb());
	}
}

?>
