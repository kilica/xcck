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

require_once XCCK_TRUST_PATH . '/class/AbstractViewAction.class.php';

/**
 * Xcck_PageViewAction
**/
class Xcck_PageViewAction extends Xcck_AbstractViewAction
{
    /**
     * _getCatId
     * 
    **/
    protected function _getCatId()
    {
        $catId = 0;
        if($this->_isSubtable()){
            $this->mObject->loadMaintable();
            $catId = $this->mObject->mMaintable->get('category_id');
        }
        else{
            $catId = $this->mObject->get('category_id');
        }
        return $catId;
    }

    /**
     * &_getHandler
     * 
     * @param   void
     * 
     * @return  Xcck_PageHandler
    **/
    protected function _getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Page');
        return $handler;
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
        return $this->mObject->getShow('title');
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
        if($this->mObject->get('uid')==Legacy_Utils::getUid()){
            return true;
        }
    
        if($this->mObject->get('status')==Lenum_Status::PUBLISHED && $this->mObject->isOpen()===true){;
            $check = $this->mCategoryManager->check($this->_getCatId(), 'view', 'page');
        }
        else{
            $check = $this->mCategoryManager->check($this->_getCatId(), 'review', 'page');
        }
        return $check;
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        $result = parent::prepare();
        $this->mObject->loadMaintable();
        $this->mObject->loadPath();
        $this->mObject->loadTag();
       	$this->mObject->loadPrevNext();	// get previous and next page object
        $this->_setupCategoryManager('page');
    
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.PrepareView', new XCube_Ref($result), $this->mObject);
        return $result;
    }

    /**
     * executeViewSuccess
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $render->setTemplateName($this->mAsset->mDirname . '_page_view.html');
        $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('dataname', 'page');
        $render->setAttribute('catTitle', $this->mCategoryManager->getTitle($this->mObject->get('category_id')));
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('definitions',Legacy_Utils::getModuleHandler('definition', $this->mAsset->mDirname)->getFields());
        $render->setAttribute('accessController', $this->mCategoryManager);
        $render->setAttribute('useMap', $this->mRoot->mContext->mModuleConfig['use_map']);
        $render->setAttribute('commentDirname', $this->mRoot->mContext->mModuleConfig['comment_dirname']);
    
        //setup images
        $this->mObject->setupImages($isPost=false);
        $render->setAttribute('imageObjs', $this->mObject->mImage);
        $render->setAttribute('imageNameList', Xcck_Utils::getImageNameList($this->mAsset->mDirname));
    
        //setup contents tree
        $isHierarchical = $this->mRoot->mContext->mModuleConfig['hierarchical'];
        $render->setAttribute('isHierarchical', $isHierarchical);
        if($isHierarchical==1){
            $render->setAttribute('pageTree', $this->mObjectHandler->getTree($this->mObject->getTopId()));
        }
    
        //setup subtable objects
        $render->setAttribute('isSubtable', $this->_isSubtable());
        $chandler = xoops_gethandler('config');
        $mhandler = xoops_gethandler('module');
        $subtableDirnameArr = Legacy_Utils::getDirnameListByTrustDirname('xcck');

        //page criteria
        $subtableArr = array();
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('status', Lenum_Status::PUBLISHED));
        $cri->add(new Criteria('maintable_id', $this->mObject->get('page_id')));
        XCube_DelegateUtils::call('Module.xcck.Event.GetSubtableCriteria', new XCube_Ref($cri), $this->mAsset->mDirname);   //deprecated
        //definition criteria
        foreach($subtableDirnameArr as $dirname){
            $configArr = $chandler->getConfigsByDirname($dirname);
            if($configArr['maintable']==$this->mAsset->mDirname){
                $subtableCri = clone $cri;
                XCube_DelegateUtils::call('Module.'.$dirname.'.SetupSubtableCriteria', new XCube_Ref($subtableCri), $this->mAsset->mDirname);
                $module = $mhandler->getByDirname($dirname);
                $subtableArr[] = array('dirname'=>$dirname, 'name'=>$module->get('name'));
                $definitionArr[$dirname] = Legacy_Utils::getModuleHandler('definition', $dirname)->getFields(true);
                $pageArr[$dirname] = Legacy_Utils::getModuleHandler('page', $dirname)->getObjects($subtableCri);
            }
        }
        if(count($subtableArr)>0){
            $render->setAttribute('subtableArr', $subtableArr);
            $render->setAttribute('definitionArr', $definitionArr);
            $render->setAttribute('pageArr', $pageArr);
        }
	
    	$render->setAttribute('xoops_breadcrumbs', $this->_getBreadcrumb($this->mObject));
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
        $this->mRoot->mController->executeRedirect($this->_getNextUri('page', 'list'), 1, _MD_XCCK_ERROR_CONTENT_IS_NOT_FOUND);
    }
}

?>
