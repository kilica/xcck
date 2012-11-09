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

require_once XCCK_TRUST_PATH . '/actions/PageViewAction.class.php';

/**
 * Xcck_RevisionViewAction
**/
class Xcck_RevisionViewAction extends Xcck_PageViewAction
{
    protected function _getId()
    {
        if($revisionId = $this->mRoot->mContext->mRequest->getRequest('revision_id')){
            $handler = Legacy_Utils::getModuleHandler('revision', $this->mAsset->mDirname);
            $revision = $handler->get($revisionId);
            if($revision instanceof Xcck_RevisionObject){
                return $revision->get('page_id');
            }
        }
        return parent::_getId();
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
     * _setupObject
     *
     * @param	void
     *
     * @return	void
     **/
    protected function _setupObject()
    {
        $id = $this->_getId();

        $this->mObjectHandler =& $this->_getHandler();

        $revisionHandler = Legacy_Utils::getModuleHandler('revision', $this->mAsset->mDirname);
        $revision = $revisionHandler->getLatestRevision($id, Lenum_Status::REJECTED);

        $this->mObject = Xcck_Utils::setupPageByRevision($revision);
    }
}

?>
