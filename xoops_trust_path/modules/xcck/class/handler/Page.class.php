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

require_once XCCK_TRUST_PATH . '/class/ObjectHandler.class.php';
require_once XCCK_TRUST_PATH . '/class/Enum.class.php';
require_once XCCK_TRUST_PATH . '/class/FieldType.class.php';

/**
 * Xcck_PageObject
**/
class Xcck_PageObject extends Legacy_AbstractObject
{
    const PRIMARY = 'page_id';
    const DATANAME = 'page';
    protected $_mIsDefinitionLoaded = false;
    protected $_mIsMaintableLoaded = false;
    protected $_mIsPrevNextLoaded = false;
    public $mDef = array();
    public $mMaintable = null;
    public $mPath = array('page_id'=>array(), 'title'=>array());
    protected $_mIsPathLoaded = false;
    public $mParent = null;
    protected $_mIsParentLoaded = false;
    public $mLatlng = null;

    /**
     * __construct
     * 
     * @param   string	$dirname
     * 
     * @return  void
    **/
    public function __construct($dirname)
    {
        $this->mDirname = $dirname;
        $defHandler = Legacy_Utils::getModuleHandler('definition', $this->getDirname());
        $this->loadDefinition();
    
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

    public function showCategory()
    {
        if(! $this->get('category_id')){
            return '';
        }

        $chandler = xoops_gethandler('config');
        $conf = $chandler->getConfigsByDirname($this->getDirname());
        if(trim($conf['maintable'])){
            $conf = $chandler->getConfigsByDirname(trim($conf['maintable']));
        }
        $catDirname = $conf['access_controller'];

        $category = '';
        XCube_DelegateUtils::call('Legacy_Category.'.$catDirname.'.GetTitle', new XCube_Ref($category), $catDirname, $this->get('category_id'));
        return $category;
    }

    /**
     * showField
     * 
     * @param   string  $key
     * @param   Enum  $option	DEPRECATED
     * 
     * @return  mixed
    **/
    public function showField(/*** int ***/ $key, /*** Enum ***/ $option=Xcck_ActionType::VIEW)
    {
        return $this->mDef[$key]->mFieldType->showField($this, $key, $option);
    }

    /**
     * editField
     * 
     * @param   string  $key
     * 
     * @return  mixed
    **/
    public function editField(/*** int ***/ $key)
    {
        return $this->mDef[$key]->mFieldType->showField($this, $key, Xcck_ActionType::EDIT);
    }

    /**
     * getField
     * 
     * @param   string  $key
     * 
     * @return  mixed
    **/
    public function getField(/*** int ***/ $key)
    {
        return $this->mDef[$key]->mFieldType->showField($this, $key, Xcck_ActionType::NONE);
    }

    /**
     * setField
     * 
     * @param   string  $key
     * @param   mixed   $value
     * 
     * @return  void
    **/
    public function setField(/*** string ***/ $key, /*** mixed ***/ $value)
    {
        switch ($this->mDef[$key]->get('field_type')) {
            case Xcck_FieldType::DATE:
            case Xcck_FieldType::STARTDATE:
            case Xcck_FieldType::ENDDATE:
                $dateArr = explode('-', $value);
                $date = mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]);
                $this->set($key, $date);
                break;
            case Xcck_FieldType::TEXT:
                $filter = $this->mDef[$key]->mFieldType->getOption($this->mDef[$key], 'filter');
                if($filter=='purifier'){
                    $value = XCube_Root::getSingleton()->mTextFilter->purifyHtml($value);
                }
                $this->set($key, $value);
                break;
            default:
                $this->set($key, $value);
                break;
        }
        
        return $value;
    }

    /**
     * loadDefinition
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function loadDefinition()
    {
        if ($this->_mIsDefinitionLoaded == false) {
            $handler = Legacy_Utils::getModuleHandler('definition', $this->mDirname);
            $this->mDef = $handler->getFields();

            $this->_mIsDefinitionLoaded = true;
        }
    }

    /**
     * checkPublish
     * 
     * @param   string  $dirname
     * 
     * @return  bool
    **/
    public function checkPublish()
    {
        $pubCondition = Xcck_Utils::getModuleConfig($this->getDirname(), 'publish');
        //$threshold = $this->_getConfig('threshold');
        switch($pubCondition){
            case 'none':
                return true;
            case 'approval':
                
            case 'rating':
        }
    }

    /**
     * isOpen
     * 
     * @param   int  $time
     * 
     * @return  bool
    **/
	public function isOpen($time=null)
	{
		$time = isset($time) ? $time : time();
		$handler = Legacy_Utils::getModuleHandler('definition', $this->getDirname());
		$startField = $handler->getStartField();
		$endField = $handler->getEndField();
		$starttime = $startField ? $this->get($startField->get('field_name')) : 0;
		if($starttime > $time){
			return false;
		}
		$endtime = $endField ? $this->get($endField->get('field_name')) : 0;
		if($endtime < $time && $endtime>0){
			return false;
		}
		return true;
	}

    /**
     * getShowStatus
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getShowStatus()
    {
        switch($this->get('status')){
            case Lenum_Status::DELETED:
                return _MD_XCCK_LANG_STATUS_DELETED;
            case Lenum_Status::REJECTED:
                return _MD_XCCK_LANG_STATUS_REJECTED;
            case Lenum_Status::PROGRESS:
                return _MD_XCCK_LANG_STATUS_POSTED;
            case Lenum_Status::PUBLISHED:
                return _MD_XCCK_LANG_STATUS_PUBLISHED;
        }
    }

    /**
     * loadMaintable
     * 
     * @param   void
     * 
     * @return  void
     */
    public function loadMaintable()
    {
        if ($this->_mIsMaintableLoaded == false) {
            $maintable = Xcck_Utils::getModuleConfig($this->getDirname(), 'maintable');
            if(! $maintable){
                return;
            }
        
            $handler = Legacy_Utils::getModuleHandler('page', $maintable);
            $this->mMaintable = $handler->get($this->get('maintable_id'));
        
            $this->_mIsMaintableLoaded = true;
        }
    }

    /**
     * loadPrevNext
     * 
     * @param   int		$order
     * 
     * @return  void
     */
    public function loadPrevNext(/*** Enum ***/ $order=null)
    {
        if ($this->_mIsPrevNextLoaded == false) {
            $handler = Legacy_Utils::getModuleHandler('page', $this->getDirname());
            $order = isset($order) ? $order :Xcck_Utils::getModuleConfig($this->getDirname(), 'default_order');
            $orderList = Xcck_Utils::getOrderList($this->getDirname());
        
        	//previous object
            if($order>0){
            	$condA = '<=';
            	$condB = '<';
            	$sort = 'ASC';
            }
            else{
            	$condA = '>=';
            	$condB = '>';
            	$sort = 'DESC';
            }
            $cri = new CriteriaCompo();
            $cri->add(new Criteria($orderList[abs($order)], $this->get($orderList[abs($order)]), $condA));
            $cri->add(new Criteria('page_id', $this->get('page_id'), $condB));
            $cri->setSort($orderList[abs($order)], $sort);
            $prevObj = $handler->getObjects($cri, 1, 0);
            $this->mPrev = count($prevObj)>0 ? array_shift($prevObj) : null;
        
        	//next object
            if($order>0){
            	$condA = '>=';
            	$condB = '>';
            	$sort = 'ASC';
            }
            else{
            	$condA = '<=';
            	$condB = '<';
            	$sort = 'DESC';
            }
            $cri = new CriteriaCompo();
            $cri->add(new Criteria($orderList[abs($order)], $this->get($orderList[abs($order)]), $condA));
            $cri->add(new Criteria('page_id', $this->get('page_id'), $condB));
            $cri->setSort($orderList[abs($order)], $sort);
            $nextObj = $handler->getObjects($cri, 1, 0);
            $this->mNext = count($nextObj)>0 ? array_shift($nextObj) : null;
		
            $this->_mIsPrevNextLoaded = true;
        }
    }

    /**
     * @public
     * call load ancestral page function if not loaded yet.
     */
    public function loadPath()
    {
        //set this page's ancestral id
        $p_id = $this->get('p_id');
        if($this->_mIsPathLoaded==false && $p_id>0){
            $handler = Legacy_Utils::getModuleHandler('page', $this->getDirname());
            $this->_loadPath($p_id, $handler);
            $this->_mIsPathLoaded=true;
        }
    }

    /**
     * @protected
     * load page path array retroactively.
     */
    protected function _loadPath($p_id, $handler)
    {
        $page =& $handler->get($p_id);
        if(is_object($page)){
            $this->mPath['page_id'][] = $page->getShow('page_id');
            $this->mPath['title'][] = $page->getShow('title');
            $this->_loadPath($page->get('p_id'), $handler);
        }
    }

    /**
     * @public
     * get top page_id in hierarchical page tree
     */
    public function getTopId()
    {
        $this->loadPath();
        if(count($this->mPath['page_id'])>0){
            return $this->mPath['page_id'][0];
        }
        return $this->get('page_id');
    }

    /**
     * get depth of the page path
     * 
     * @param   void
     * 
     * @return  int
    **/
    public function getDepth()
    {
        $this->loadPath();
        return count($this->mPath['page_id']) + 1;
    }

    /**
     * get number of image used in this table
     * 
     * @param   void
     * 
     * @return  int
    **/
	public function getImageNumber()
	{
		$list = Xcck_Utils::getImageNameList($this->getDirname());
		return count($list);
	}
}


/**
 * Xcck_PageHandler
**/
class Xcck_PageHandler extends Xcck_ObjectGenericHandler
{
    public /*** string ***/ $mTable = '{dirname}_page';

    public /*** string ***/ $mPrimary = 'page_id';

    public /*** string ***/ $mClass = 'Xcck_PageObject';

    protected $_mClientField = array('title'=>'title', 'category'=>'category_id', 'posttime'=>'posttime');
    protected $_mClientConfig = array('tag'=>'tag_dirname', 'image'=>'images', 'workflow'=>'publish', 'activity'=>'use_activity', 'map'=>'use_map');

    /**
     * _getTagList
     *
     * @param Legacy_AbstractObject $obj
     *
     * @return  string[]
     */
    protected function _getTagList(Legacy_AbstractObject $obj)
    {
        return $obj->mTag;
    }

    public function insert($obj, $force=false)
    {
		if($obj->isNew()){
			if(parent::insert($obj, $force)===false){
				return false;
			}
			else{
				$this->_countupDescendant($obj);
			}
		}
		elseif($obj->get('status')===Lenum_Status::PUBLISHED){
			return parent::insert($obj, $force);
		}
		return true;
    }

    /**
     * @return  void
     */
	protected function _countupDescendant(Xcck_PageObject $obj)
	{
        //count up number of descendant
        if($obj->get('p_id')>0){
            $topId = $obj->getTopId();
            $topObj = $this->get($topId);
            if(is_object($topObj)===true){
                $topObj->set('descendant', $topObj->get('descendant')+1);
                $topObj->set('updatetime', $obj->get('posttime'));
                $this->insert($topObj, true);
            }
        }
	}

    protected function _deleteMap(Xcck_PageObject $obj)
    {
        $result = array();
        XCube_DelegateUtils::call('Legacy_Map.DeletePlaces', new XCube_Ref($result), $obj->getDirname(), $obj->getDataname(), $obj->get('page_id'));
    
        return $result;
    }

    public function delete(Xcck_PageObject $obj, $force=false)
    {
        //delete page tree
        $tree = $this->getTree($obj->get('page_id'));
        if($count = count($tree) && $obj->get('p_id')>0){
            $topId = $obj->getTopId();
            $topObj = $this->get($topId);
            if(is_object($topObj)===true){
                $topObj->set('descendant', $topObj->get('descendant')-$count);
                $this->insert($topObj, $force);
            }
        }
        foreach($tree as $page){
            if(! parent::delete($obj, $force)){
                return XCCK_FRAME_VIEW_ERROR;
            }
        }
    
    	$this->_deleteSubtable($obj);
    	$this->_deleteRevision($obj);
    
        return XCCK_FRAME_VIEW_SUCCESS;
    }

    /**
     * @return  bool
     */
	protected function _deleteSubtable(Xcck_PageObject $obj)
	{
        //delete subtable
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname('xcck');
        foreach($dirnames as $dirname){
            if(Xcck_Utils::getModuleConfig($dirname, 'maintable')==$obj->getDirname()){
                return Legacy_Utils::getModuleHandler('page', $dirname)->deleteAll(new Criteria('maintable_id', $obj->get('page_id')));
            }
        }
	}

    /**
     * @return  bool
     */
	protected function _deleteRevision(Xcck_PageObject $obj)
	{
        //count up number of descendant
       	$handler = Legacy_Utils::getModuleHandler('revision', $this->getDirname());
       	return $handler->deleteAll(new Criteria('page_id', $obj->get('page_id')));
	}

    /**
     * count page in given category
     * @param   int     $catId
     * 
     * @return  int
    **/
    public function countPageByCategory(/*** int ***/ $catId)
    {
    	$cri = Xcck_Utils::getListCriteria($this->getDirname(), $catId);
    	return $this->getCount($cri);
    }

    /**
     * getTree
     * get Xcck_PageObject array in maintable-subtable tree form
     * @param   int     $p_id
     * 
     * @return  Xcck_PageObject[]
    **/
    public function getTree(/*** int ***/ $p_id)
    {
        $tree = array();
        $obj = $this->get($p_id);
        if(is_object($obj)){
            $tree[] = $obj;
        }
        else{
            return $tree;
        }
        return $this->_getTree($tree, $p_id);
    }

    /**
     * _getTree
     * 
     * @param   Xcck_PageObject[]   $tree
     * @param   int     $pid
     * 
     * @return  Xcck_PageObject[]
    **/
    protected function _getTree(/*** Xcck_PageObject[] ***/ $tree, /*** int ***/ $p_id)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('p_id', $p_id));
        $criteria->setSort('weight');
        $pageArr =$this->getObjects($criteria);
        foreach(array_keys($pageArr) as $key){
            $tree[] = $pageArr[$key];
            $tree = $this->_getTree($tree, $pageArr[$key]->get('page_id'));
        }
        return $tree;
    }

    /**
     * check if use Legacy_Workflow
     *
     * @param mixed[]   $conf
     *
     * @return  bool
     */
    protected function _isWorkflowClient(/*** mixed[] ***/ $conf)
    {
        return false;
    }

    /**
     * check if use Legacy_Activity
     *
     * @param mixed[]   $conf
     *
     * @return  bool
     */
    protected function _isActivityClient(/*** mixed[] ***/ $conf)
    {
        return defined('LEGACY_ACTIVITY_DIRNAME') ? true : false;
    }

    /**
     * check if use Legacy_Image
     *
     * @param mixed[]   $conf
     *
     * @return  bool
     */
    protected function _isImageClient(/*** mixed[] ***/ $conf)
    {
        return $conf[$this->_mClientConfig['image']] ? true : false;
    }

}

?>
