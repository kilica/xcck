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
 * Xcck_DefinitionObject
**/
class Xcck_DefinitionObject extends Legacy_AbstractObject
{
	const PRIMARY = 'definition_id';
	const DATANAME = 'definition';

    /**
     * __construct
     * 
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public function __construct(/*** string ***/ $dirname)
    {
        $this->mDirname = $dirname;
    
        $this->initVar('definition_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('field_name', XOBJ_DTYPE_STRING, '', false, 32);
        $this->initVar('label', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('field_type', XOBJ_DTYPE_STRING, '', false, 16);
        $this->initVar('validation', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('required', XOBJ_DTYPE_BOOL, '', false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('show_list', XOBJ_DTYPE_BOOL, 1, false);
        $this->initVar('search_flag', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('description', XOBJ_DTYPE_TEXT, '', false);
        $this->initVar('options', XOBJ_DTYPE_TEXT, '', false);
    }

    /**
     * getFieldType
     * 
     * @param   void
     * 
     * @return  Xcck_iFieldType
    **/
    public function getFieldType()
    {
        $className = 'Xcck_FieldType'.ucfirst($this->get('field_type'));
        if(class_exists($className)===true){
            return new $className();
        }
    }

    /**
     * getOptions
     * 
     * @param   void
     * 
     * @return  mixed
    **/
    public function getOptions($key=null)
    {
        $fieldType = $this->getFieldType();
        return $fieldType->getOption($this, $key);
    }

    /**
     * getMinuteOption
     * 
     * @param   void
     * 
     * @return  int[]
    **/
    public function getMinuteOption()
    {
        switch($this->get('options')){
        case 'half':
            $minute = array(0,30);
            break;
        case 'quarter':
            $minute = array(0,15,30,45);
            break;
        case '10min':
            $minute = array(0,10,20,30,40,50);
            break;
        case 'min':
            $minute = range(0,59);
            break;
        }
        return $minute;
    }

    /**
     * getDefault
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getDefault()
    {
        return $this->mFieldType->getDefault($this->get('options'));
    }

    /**
     * getShowYN
     * 
     * @param   $key
     * 
     * @return  string
    **/
    public function getShowYN($key)
    {
        return $this->get($key)==true ? _MD_XCCK_LANG_YES : _MD_XCCK_LANG_NO;
    }
}

/**
 * Xcck_DefinitionHandler
**/
class Xcck_DefinitionHandler extends Xcck_ObjectGenericHandler
{
    public /*** string ***/ $mTable = '{dirname}_definition';

    public /*** string ***/ $mPrimary = 'definition_id';

    public /*** string ***/ $mClass = 'Xcck_DefinitionObject';

    /**
     * @public
     */
    function getFields($list=false)
    {
        $listStatus = $list===true ? 1 : 0;
        static $fields = array();
        $dirname = $this->getDirname();
        if(isset($fields[$dirname][$listStatus])){
            return $fields[$dirname][$listStatus];
        }

        $cri = new CriteriaCompo();
        if($list===true){
            $cri->add(new Criteria('show_list', 1));
        }
        $cri->setSort('weight');
        $objs = $this->getObjects($cri);
        $ret = array();
        foreach($objs as $obj){
            $ret[$obj->get('field_name')] = $obj;
        }
        $fields[$dirname][$listStatus] = $ret;
        return $ret;
    }

    public function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false)
    {
        $objs = parent::getObjects($criteria, $limit, $start, $id_as_key);
        foreach($objs as $obj){
            $obj->mFieldType = $obj->getFieldType();
        }
        return $objs;
    }

    /**
     * @public
     */
    public function insert(&$obj, $force = false)
    {
        $fieldType = $obj->getFieldType();
        if ($obj->isNew()) {
            $sql = 'ALTER TABLE %s ADD `'. $obj->get('field_name') .'` '. $fieldType->getTableQuery($obj->get('field_type'));
	        $this->_alterPage($sql);
	        $this->_alterRevision($sql);
        }
        else {
            $oldObj =& $this->get($obj->get('definition_id'));
            if($oldObj->get('field_name')!=$obj->get('field_name')){
                $sql = 'ALTER TABLE $s CHANGE `'. $oldObj->get('field_name') .'` `'. $obj->get('field_name') .'` '. $fieldType->getTableQuery($this->get('field_type'));
		        $this->_alterPage($sql);
		        $this->_alterRevision($sql);
            }
        }
    
        return parent::insert($obj, $force);
    }

    /**
     * @return bool
     */
    public function delete(&$obj, $force=false)
    {
        $sql = 'ALTER TABLE %s DROP `'. $obj->get('field_name') .'`';
        $this->_alterPage($sql);
        $this->_alterRevision($sql);
    
        return parent::delete($obj, $force);
    }

    /**
     * @return void
     */
	protected function _alterPage(/*** string ***/ $sql)
	{
		$this->db->query(sprintf($sql, $this->db->prefix($this->getDirname().'_page')));
	}

    /**
     * @return void
     */
	protected function _alterRevision(/*** string ***/ $sql)
	{
		$this->db->query(sprintf($sql, $this->db->prefix($this->getDirname().'_revision')));
	}

    public function getDefinitionsArr()
    {
        static $defArr = array();
        if(isset($defArr[$this->getDirname()])){
            return $defArr[$this->getDirname()];
        }
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight', 'ASC');
        $definitions = $this->getObjects($criteria);
        $arr = array();
        foreach($definitions as $def){
            $arr[$def->get('field_name')] = $def->gets();
        }
        $defArr[$this->getDirname()] = $arr;

        return $arr;
    }

    /**
     * @public
     */
    public function getValidationList()
    {
        return array("email");
    }

	public function getStartField()
	{
		$objs = $this->getObjects(new Criteria('field_type', 'startdate'));
		if(count($objs)>0){
			return array_shift($objs);
		}
	}

	public function getEndField()
	{
		$objs = $this->getObjects(new Criteria('field_type', 'enddate'));
		if(count($objs)>0){
			return array_shift($objs);
		}
	}

    protected function _isActivityClient(/*** mixed[] ***/ $conf)
    {
        return false;
    }
}

?>
