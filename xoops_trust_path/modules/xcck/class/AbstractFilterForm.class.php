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

/**
 * Xcck_AbstractFilterForm
**/
abstract class Xcck_AbstractFilterForm
{
    public /*** Enum[] ***/ $mSort = array();

    public /*** string[] ***/ $mSortKeys = array();

    public /*** XCube_PageNavigator ***/ $mNavi = null;

    protected /*** XoopsObjectGenericHandler ***/ $_mHandler = null;

    // todo
    public /*** Criteria ***/ $_mCriteria = null;

    /**
     * _getId
     * 
     * @param   void
     * 
     * @return  int
    **/
    protected function _getId()
    {
    }

    /**
     * &_getHandler
     * 
     * @param   void
     * 
     * @return  XoopsObjectGenericHandler
    **/
    protected function &_getHandler()
    {
    }

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        $this->_mCriteria = new CriteriaCompo();
    }

    /**
     * prepare
     * 
     * @param   XCube_PageNavigator  &$navi
     * @param   XoopsObjectGenericHandler  &$handler
     * 
     * @return  void
    **/
    public function prepare(/*** XCube_PageNavigator ***/ &$navi,/*** XoopsObjectGenericHandler ***/ &$handler)
    {
        $this->mNavi =& $navi;
        $this->_mHandler =& $handler;
    
        $this->mNavi->mGetTotalItems->add(array(&$this, 'getTotalItems'));
    }

    /**
     * getTotalItems
     * 
     * @param   int  &$total
     * 
     * @return  void
    **/
    public function getTotalItems(/*** int ***/ &$total)
    {
        $total = $this->_mHandler->getCount($this->getCriteria());
    }

    /**
     * fetchSort
     * 
     * @param   void
     * 
     * @return  void
    **/
    protected function fetchSort()
    {
    	$sortKeyName = $this->mNavi->mPrefix . 'sort';
        $root =& XCube_Root::getSingleton();
        $sort = $root->mContext->mRequest->getRequest($sortKeyName);
        if(! is_array($sort)){
        	$sort = array($sort);
        }
        $this->mSort = $sort;
    
        if(!isset($this->mSortKeys[abs($this->mSort[0])]))
        {
            $this->mSort[0] = $this->getDefaultSortKey();
        }
    
    	foreach(array_keys($this->mSort) as $key){
	        $this->mNavi->mSort[$sortKeyName[$key]] = $this->mSort[$key];
	    }
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        $this->mNavi->fetch();
        $this->fetchSort();
    }

    /**
     * getSort
     * 
     * @param   int		$num
     * 
     * @return  Enum
    **/
    public function getSort(/*** int ***/ $num=0)
    {
        $sortkey = abs($this->mSort[$num]);
        return $this->mSortKeys[$sortkey];
    }

    /**
     * getOrder
     * 
     * @param   int		$num
     * 
     * @return  Enum
    **/
    public function getOrder(/*** int ***/ $num=0)
    {
        return ($this->mSort[$num] < 0) ? 'desc' : 'asc';
    }

    /**
     * &getCriteria
     * 
     * @param   int  $start
     * @param   int  $limit
     * 
     * @return  Criteria
    **/
    public function &getCriteria(/*** int ***/ $start = null,/*** int ***/ $limit = null)
    {
        $t_start = ($start === null) ? $this->mNavi->getStart() : intval($start);
        $t_limit = ($limit === null) ? $this->mNavi->getPerpage() : intval($limit);
    
        $criteria = $this->_mCriteria;
    
        $criteria->setStart($t_start);
        $criteria->setLimit($t_limit);
        return $criteria;
    }
}

?>
