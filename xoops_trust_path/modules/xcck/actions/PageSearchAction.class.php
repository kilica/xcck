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
 * Xcck_PageSearchAction
**/
class Xcck_PageSearchAction extends Xcck_PageListAction
{
	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_PageFilterForm
	**/
	protected function &_getFilterForm()
	{
		$filter =& $this->mAsset->getObject('filter', 'Search', false);
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
		return Legacy_Utils::renderUri($this->mAsset->mDirname, 'page', 'search');
	}

    /**
     * _getPageTitle
     *
     * @param   void
     *
     * @return  string
     **/
    protected function _getPagetitle()
    {
        return _MD_XCCK_LANG_SEARCH_RESULT;
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
	
		$criteria=$this->mFilter->getCriteria(null, $this->mRoot->mContext->mRequest->getRequest('limit'));
	
	
		$this->mObjects = $handler->getObjects($criteria);
		return XCCK_FRAME_VIEW_INDEX;
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
		$render->setTemplateName($this->mAsset->mDirname . '_page_search.html');
		$render->setAttribute('catTitleList', $this->mCategoryManager->getTitleList());
	}

    /**
     * _setHeaderScript
     * 
     * @param   void
     * 
     * @return  void
    **/
    protected function _setHeaderScript()
    {
        $headerScript = $this->mRoot->mContext->getAttribute('headerScript');
        //DatePicker Script
        $headerScript->addScript($this->_getDatePickerScript());
    }
}

?>
