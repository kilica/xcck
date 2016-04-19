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
     * @param    void
     * 
     * @return    int
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
     * @param    void
     * 
     * @return    string
    **/
    protected function _getPagetitle()
    {
        return $this->mObject->getShow('title');
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
     * hasPermission
     *
     * @param   void
     *
     * @return  bool
     **/
    public function hasPermission()
    {
        $catId = $this->_getCatId();
        $uid = Legacy_Utils::getUid();

        //is Manager ?
        if($this->mCategoryManager->check($catId, Xcck_AuthType::MANAGE, 'page')==true){
            return true;
        }
        //if poster himself ?
        if($this->mObject->get('uid')==$uid){
            return true;
        }
        //if subtable and parent table's poster ?
        if($this->_isSubtable() && $this->mRoot->mContext->mModuleConfig['subtable_parent_auth']=='parent_is_manager'){
            $this->mObject->loadMaintable();
            if($this->mObject->mMaintable->get('uid')==$uid){
                return true;
            }
        }

        return false;
    }

    /**
     * _setupActionForm
     * 
     * @param    void
     * 
     * @return    void
    **/
    protected function _setupActionForm()
    {
        $this->mActionForm =& $this->mAsset->getObject('form', 'Page',false,'delete');
        $this->mActionForm->prepare();
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
        $this->_mPageId = $this->mObject->get('page_id');
        $this->_setupCategoryManager('page');
    
        return true;
    }

    /**
     * executeViewInput
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
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
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $url = $this->_isSubtable()
            ? Legacy_Utils::renderUri($this->mRoot->mContext->mModuleConfig['maintable'], 'page', $this->mObject->getShow('maintable_id'))
            : Legacy_Utils::renderUri($this->mAsset->mDirname);

        XCube_DelegateUtils::call(
            'Module.'.$this->mAsset->mDirname.'.Event.GetForwardUri.Delete.Success',
            new XCube_Ref($url),
            $this->mObject
        );

        $this->mRoot->mController->executeForward($url);
    }

    /**
     * executeViewError
     *
     * @param   XCube_RenderTarget  &$render
     *
     * @return  void
     **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
        $url = $this->_isSubtable()
            ? Legacy_Utils::renderUri($this->mRoot->mContext->mModuleConfig['maintable'], 'page', $this->mObject->getShow('maintable_id'))
            : $this->_getNextUri('page', 'list');
        $message = _MD_XCCK_ERROR_DBUPDATE_FAILED;

        XCube_DelegateUtils::call(
            'Module.'.$this->mAsset->mDirname.'.Event.GetForwardUri.Delete.Error',
            new XCube_Ref($url),
            new XCube_Ref($message),
            $this->mObject
        );

        $this->mRoot->mController->executeRedirect($url, 1, $message);
    }

    /**
     * executeViewCancel
     *
     * @param   XCube_RenderTarget  &$render
     *
     * @return  void
     **/
    public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
    {
        $url = $this->_isSubtable() ? Legacy_Utils::renderUri($this->mRoot->mContext->mModuleConfig['maintable'], 'page', $this->mObject->getShow('maintable_id')) : $this->_getNextUri('page', 'list');

        $this->mRoot->mController->executeForward($url);
    }
}

?>
