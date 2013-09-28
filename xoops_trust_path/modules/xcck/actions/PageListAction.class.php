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
 * Xcck_PageListAction
**/
class Xcck_PageListAction extends Xcck_AbstractListAction
{
    /**
     * _getCatId
     * 
     * @param    void
     * 
     * @return    int
    **/
    protected function _getCatId()
    {
        return intval($this->mRoot->mContext->mRequest->getRequest('category_id'));
    }

    /**
     * &_getHandler
     * 
     * @param    void
     * 
     * @return    Xcck_PageHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Page');
        return $handler;
    }

    /**
     * &_getFilterForm
     * 
     * @param    void
     * 
     * @return    Xcck_PageFilterForm
    **/
    protected function &_getFilterForm()
    {
        $filter =& $this->mAsset->getObject('filter', 'Page',false);
        $filter->prepare($this->_getPageNavi(), $this->_getHandler());
        return $filter;
    }

    /**
     * _getBaseUrl
     * 
     * @param    void
     * 
     * @return    string
    **/
    protected function _getBaseUrl()
    {
        return Legacy_Utils::renderUri($this->mAsset->mDirname, 'page');
    }

    /**
     * hasPermission
     * 
     * @param    void
     * 
     * @return    bool
    **/
    public function hasPermission()
    {
        if($this->_getCatId() > 0){
            return ($this->mCategoryManager->check($this->_getCatId(), Xcck_AuthType::VIEW, 'page')==true) ? true : false;
        }
        return true;
    }

    /**
     * prepare
     * 
     * @param    void
     * 
     * @return    bool
    **/
    public function prepare()
    {
        parent::prepare();
        $this->_setupCategoryManager('page');
    
        return true;
    }

    /**
     * getDefaultView
     * 
     * @param    void
     * 
     * @return    Enum
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
    
        if($this->mRoot->mContext->mModuleConfig['hierarchical'] && $this->mRoot->mContext->mModuleConfig['list_order']!='categorized'){
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
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    protected function _setTemplate(/*** XCube_RenderTarget ***/ &$render)
    {
        switch($this->mRoot->mContext->mModuleConfig['list_order']){
        case 'categorized':
            $render->setTemplateName($this->mAsset->mDirname . '_category_list.html');
            $render->setAttribute('tree', $this->mTree);
            break;
        case 'album':
            $render->setTemplateName($this->mAsset->mDirname . '_page_album.html');
            $render->setAttribute('catTitleList', $this->mCategoryManager->getTitleList());
            break;
        case 'flat':
            $render->setTemplateName($this->mAsset->mDirname . '_page_list.html');
            $render->setAttribute('catTitleList', $this->mCategoryManager->getTitleList());
            break;
        }
    }

    /**
     * executeViewIndex
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
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
        $render->setAttribute('editPageName', $this->_isSubtable() ? 'subtable' : 'page');
        $render->setAttribute('moduleDescription', $this->mRoot->mContext->mModuleConfig['description']);
    
        $render->setAttribute('xoops_breadcrumbs', $this->_getBreadcrumb());
    }
}

?>
