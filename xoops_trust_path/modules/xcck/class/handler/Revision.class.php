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

	public function isPublished()
	{
		return $this->get('status')===Lenum_Status::PUBLISHED ? true : false;
	}

	public function publish()
	{
		$handler = Legacy_Utils::getModuleHandler('page', $this->getDirname());
		$page = $handler->get($this->get('page_id'));
		$page->loadDefinition();

		$page->set('page_id', $this->get('page_id'));
		$page->set('title', $this->get('title'));
		$page->set('category_id', $this->get('category_id'));
		$page->set('maintable_id', $this->get('maintable_id'));
		$page->set('p_id', $this->get('p_id'));
		$page->set('descendant', $this->get('descendant'));
		$page->set('uid', $this->get('uid'));
		$page->set('status', $this->get('status'));
		$page->set('weight', $this->get('weight'));
		$page->set('updatetime', $this->get('updatetime'));

		foreach($page->mDef as $def){
			$page->set($def->getShow('field_name'), $this->get($def->getShow('field_name')));
		}

		return $handler->insert($page, true);
	}

    public function revise()
    {
        $revisionHandler = Legacy_Utils::getModuleHandler('revision', $this->getDirname());
        $revision = $revisionHandler->create();

        $revision->set('page_id', $this->get('page_id'));
        $revision->set('title', $this->get('title'));
        $revision->set('category_id', $this->get('category_id'));
        $revision->set('maintable_id', $this->get('maintable_id'));
        $revision->set('p_id', $this->get('p_id'));
        $revision->set('descendant', $this->get('descendant'));
        $revision->set('uid', $this->get('uid'));
        $revision->set('status', Xcck_Utils::getDefaultStatus($this->getDirname()));
        $revision->set('weight', $this->get('weight'));
        $revision->set('updatetime', $this->get('updatetime'));

        foreach($this->mDef as $def){
            $revision->set($def->getShow('field_name'), $this->get($def->getShow('field_name')));
        }

        return $revisionHandler->insert($revision, true);
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

    public function getLatestRevision(/*** int ***/ $pageId)
    {
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('page_id', $pageId));
        $cri->add(new Criteria('status', Lenum_Status::PROGRESS, '>='));
        $cri->setSort('revision_id', 'DESC');
        $objs = $this->getObjects($cri);
        if(count($objs)>0){
            return array_shift($objs);
        }
    }

	public function updateStatus(/*** int ***/ $revisionId, /*** Enum ***/$status)
	{
		$obj = $this->get($revisionId);
		if($obj instanceof Xcck_RevisionObject){
			$obj->set('status', $status);
			if(! $this->insert($obj, true)){
				return false;
			}
		}
		else{
			return false;
		}

		if($status===Lenum_Status::PUBLISHED){
			if(! $obj->publish()){
				return false;
			}
		}
		return true;
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
