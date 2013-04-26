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

require_once XCCK_TRUST_PATH . '/class/handler/Page.class.php';
require_once XCCK_TRUST_PATH . '/class/Enum.class.php';
require_once XCCK_TRUST_PATH . '/class/FieldType.class.php';

/**
 * Xcck_RevisionObject
**/
class Xcck_RevisionObject extends Xcck_PageObject
{
    const PRIMARY = 'revision_id';
    const DATANAME = 'revision';

    /**
     * @return  void
    **/
    public function __construct(/*** string ***/ $dirname)
    {
        $this->mDirname = $dirname;
        $defHandler = Legacy_Utils::getModuleHandler('definition', $this->getDirname());
        $this->loadDefinition();
    
        $this->initVar('revision_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('page_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('title', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('category_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('maintable_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('p_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('descendant', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('status', XOBJ_DTYPE_INT, Lenum_Status::PROGRESS, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 50, false);
        $this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('updatetime', XOBJ_DTYPE_INT, time(), false);
    
        foreach(array_keys($this->mDef) as $key){
            $this->mDef[$key]->mFieldType = $this->mDef[$key]->getFieldType();
            $this->mDef[$key]->mFieldType->setInitVar($this, $this->mDef[$key]->getShow('field_name'), $this->mDef[$key]->getDefault());
        }
    }

    public function loadTag()
    {
        $chandler = xoops_gethandler('config');
        $configArr = $chandler->getConfigsByDirname($this->getDirname());
    
        if($this->_mIsTagLoaded==false && $tagDirname = $configArr['tag_dirname']){
            $tagArr = array();
            if(! $this->isNew()){
                XCube_DelegateUtils::call('Legacy_Tag.'.$configArr['tag_dirname'].'.GetTags',
                    new XCube_Ref($tagArr),
                    $tagDirname,
                    $this->getDirname(),
                    'page',
                    $this->get('page_id')
                );
            }
            $this->mTag = $tagArr;
            $this->_mIsTagLoaded = true;
        }
    }
}

/**
 * Xcck_RevisionHandler
**/
class Xcck_RevisionHandler extends Xcck_ObjectGenericHandler
{
    public /*** string ***/ $mTable = '{dirname}_revision';
    public /*** string ***/ $mPrimary = 'revision_id';
    public /*** string ***/ $mClass = 'Xcck_RevisionObject';

    protected $_mClientField = array('title'=>'title', 'category'=>'category_id', 'posttime'=>'posttime');
    protected $_mClientConfig = array('tag'=>'', 'image'=>'', 'workflow'=>'publish', 'activity'=>'', 'map'=>'');

    public function getLatestRevision(/*** int ***/ $pageId, /*** Enum ***/ $status=Lenum_Status::PROGRESS)
    {
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('page_id', $pageId));
        $cri->add(new Criteria('status', $status, '>='));
        $cri->setSort('revision_id', 'DESC');
        $objs = $this->getObjects($cri);
        return (count($objs)>0) ? array_shift($objs) : null;
    }

    public function updateStatus(/*** int ***/ $pageId, /*** Enum ***/$status)
    {
        $obj = $this->getLatestRevision($pageId);
        if(! ($obj instanceof Xcck_RevisionObject)){
            return false;
        }
        $obj->set('status', $status);

        if($status===Lenum_Status::PUBLISHED){
            $handler = Legacy_Utils::getModuleHandler('page', $this->getDirname());
            $page = Xcck_Utils::setupPageByRevision($this);

            return $handler->insert($page, true);
        }
        else{
            return $this->insert($obj, true);
        }
    }

    /**
     * @return  bool
     */
    protected function _isWorkflowClient(/*** mixed[] ***/ $conf)
    {
        return ($conf[$this->_mClientConfig['workflow']]=='linear') ? true : false;
    }

    /**
     * @return  bool
     */
    protected function _isActivityClient(/*** mixed[] ***/ $conf)
    {
        return false;
    }

    /**
     * @return  bool
     */
    protected function _isImageClient(/*** mixed[] ***/ $conf)
    {
        return false;
    }
}

?>
