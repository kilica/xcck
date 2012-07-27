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

require_once XCCK_TRUST_PATH . '/class/AbstractListAction.class.php';

/**
 * Xcck_DefinitionListAction
**/
class Xcck_DefinitionListAction extends Xcck_AbstractListAction
{
	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_DefinitionHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Definition');
		return $handler;
	}

	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_DefinitionFilterForm
	**/
	protected function &_getFilterForm()
	{
		$filter =& $this->mAsset->getObject('filter', 'Definition',false);
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
		return Legacy_Utils::renderUri($this->mAsset->mDirname, 'definition');
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
		if($this->mCategoryManager->getCategoryType()!='none'){
			//is Manager ?
			return (count($this->mCategoryManager->getPermittedIdList(Xcck_AuthType::MANAGE, 'definition'))>0) ? true : false;
		}
		else{
			return $this->_isDefinitionEditor();
		}
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
		$this->_setupCategoryManager('page');
	
		return true;
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
		$render->setTemplateName($this->mAsset->mDirname . '_definition_list.html');
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		$render->setAttribute('isSubtable', $this->_isSubtable());
	}
}

?>
