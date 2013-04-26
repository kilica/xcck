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
 * XcckObjectHandler
**/
class Xcck_ObjectGenericHandler extends Legacy_AbstractClientObjectHandler
{
    /**
     * @brief   string
    **/
    public $mTable = null;

    /**
     * @brief   string
    **/
    public $mDirname = null;

    /**
     * @brief   string
    **/
    public $mPrimary = null;

    /**
     * @brief   string
    **/
    public $mClass = null;

	protected $_mClientConfig = array('tag'=>'tag_dirname', 'image'=>'images', 'workflow'=>'publish', 'activity'=>'use_activity');

    /**
     * __construct
     * 
     * @param   XoopsDatabase  &$db
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
    {
    	$this->mDirname = $dirname;
        $this->mTable = strtr($this->mTable,array('{dirname}' => $this->getDirname()));
        parent::XoopsObjectGenericHandler($db);
    }

    /**
     * create
     * 
     * @param   bool $isNew
     * 
     * @return  XoopsSimpleObject  $obj
    **/
	public function &create($isNew = true)
	{
		$obj = null;
		if (XC_CLASS_EXISTS($this->mClass)) {
			$obj = new $this->mClass($this->getDirname());
			if($isNew)
				$obj->setNew();
		}
		return $obj;
	}

    /**
     * create
     * 
	 * @param CriteriaElement $criteria
	 * @param int  $limit
	 * @param int  $start
	 * @param bool $id_as_key
     * 
     * @return  XoopsSimpleObject[]  $ret
    **/
	public function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false)
	{
		$ret = array();

		$sql = "SELECT * FROM `" . $this->mTable . '`';
        
		if($criteria !== null && is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);
			
			if (trim($where)) {
				$sql .= " WHERE " . $where;
			}
			
			$sorts = array();
			foreach ($criteria->getSorts() as $sort) {
                $sorts[] = '`' . $sort['sort'] . '` ' . $sort['order']; 
			}
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . implode(',', $sorts);
			}
			
			if ($limit === null) {
				$limit = $criteria->getLimit();
			}
			
			if ($start === null) {
				$start = $criteria->getStart();
			}
		}
		else {
			if ($limit === null) {
				$limit = 0;
			}
			
			if ($start === null) {
				$start = 0;
			}
		}

		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		while($row = $this->db->fetchArray($result)) {
			$obj = new $this->mClass($this->getDirname());	///changed
			$obj->assignVars($row);
			$obj->unsetNew();
			
			if ($id_as_key)	{
				$ret[$obj->get($this->mPrimary)] = $obj;
			}
			else {
				$ret[]=$obj;
			}
		
			unset($obj);
		}
	
		return $ret;
	}
}

?>
