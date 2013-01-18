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

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Xcck_PageEditForm
**/
class Xcck_PageEditForm extends XCube_ActionForm
{
	public $mDirname = null;
    protected $_mDef = array();

    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.xcck.PageEditForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare($dirname)
    {
    	$this->mDirname = $dirname;
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $this->_mDef = $handler->getObjects();
        $this->mFieldType = new Xcck_FieldType();
    
        $chandler = xoops_gethandler('config');
        $conf = $chandler->getConfigsByDirname($dirname);
        $this->mUseMap = $conf['use_map']==1 ? true : false;
        $this->mUseTag = $conf['tag_dirname'] ? true : false;
    
        //
        // Set form properties for default fields
        //
        $this->mFormProperties['page_id'] = new XCube_IntProperty('page_id');
        $this->mFormProperties['title'] = new XCube_StringProperty('title');
        $this->mFormProperties['category_id'] = new XCube_IntProperty('category_id');
        $this->mFormProperties['maintable_id'] = new XCube_IntProperty('maintable_id');
        $this->mFormProperties['p_id'] = new XCube_IntProperty('p_id');
        $this->mFormProperties['descendant'] = new XCube_IntProperty('descendant');
        $this->mFormProperties['uid'] = new XCube_IntProperty('uid');
        $this->mFormProperties['weight'] = new XCube_IntProperty('weight');
        $this->mFormProperties['status'] = new XCube_IntProperty('status');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');
        $this->mFormProperties['updatetime'] = new XCube_IntProperty('updatetime');
    
        if($this->mUseTag){
            $this->mFormProperties['tags'] = new XCube_TextProperty('tags');
        }
    
        if($this->mUseMap){
            $this->mFormProperties['latitude'] = new XCube_FloatProperty('latitude');
            $this->mFormProperties['longitude'] = new XCube_FloatProperty('longitude');
        }

        //
        //validation check for default fields
        //
        $this->mFieldProperties['title'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['title']->setDependsByArray(array('required'));
        $this->mFieldProperties['title']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_TITLE, '255');
    
        foreach(array_keys($this->_mDef) as $key){
            //
            // Set form properties for custom fields
            //
            $formPropertyClass = $this->_mDef[$key]->mFieldType->getFormPropertyClass();
            $this->mFormProperties[$this->_mDef[$key]->get('field_name')] = new $formPropertyClass($this->_mDef[$key]->get('field_name'));
            //add checkbox for startdate/enddate field
            if(in_array($this->_mDef[$key]->get('field_type'), array('startdate', 'enddate'))){
            	$this->mFormProperties['enable_'.$this->_mDef[$key]->get('field_name')] = new XCube_BoolProperty('enable_'.$this->_mDef[$key]->get('field_name'));
            }
        
            //
            //validation checks for custom fields
            //
            $validationArr = array();
            $this->mFieldProperties[$this->_mDef[$key]->get('field_name')] = new XCube_FieldProperty($this);
            //required check
            if($this->_mDef[$key]->get('required')==1){
                $validationArr[] = 'required';
                $this->mFieldProperties[$this->_mDef[$key]->get('field_name')]->addMessage('required', _MD_XCCK_ERROR_REQUIRED, $this->_mDef[$key]->get('label'));
            }
            //validation check
            switch($this->_mDef[$key]->get('validation')){
            case 'email' :
                $validationArr[] = 'email';
                $this->mFieldProperties[$this->_mDef[$key]->get('field_name')]->addMessage($this->_mDef[$key]->get('field_name'), _MD_XCCK_ERROR_EMAIL);
            break;
            }
            $this->mFieldProperties[$this->_mDef[$key]->get('field_name')]->setDependsByArray($validationArr);
        }
    }

    /**
     * Validate input data.
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function validate()
    {
        parent::validate();
        /**
         * Xcck.Event.ValidateRegisterForm
         * 
         * @param   &Xcck_PageEditForm
        **/
        XCube_DelegateUtils::call('Module.'.$this->mDirname.'.ActionForm.Update', $this);
    }

    /**
     * load
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function load(/*** XoopsSimpleObject ***/ &$obj)
    {
        $this->set('page_id', $obj->get('page_id'));
        $this->set('title', $obj->get('title'));
        $this->set('category_id', $obj->get('category_id'));
        $this->set('maintable_id', $obj->get('maintable_id'));
        $this->set('p_id', $obj->get('p_id'));
        $this->set('descendant', $obj->get('descendant'));
        $this->set('uid', $obj->get('uid'));
        $this->set('weight', $obj->get('weight'));
        $this->set('status', $obj->get('status'));
        $this->set('posttime', $obj->get('posttime'));
        $this->set('updatetime', $obj->get('updatetime'));
        foreach(array_keys($this->_mDef) as $key){
            $this->set($this->_mDef[$key]->get('field_name'), $obj->editField($this->_mDef[$key]->get('field_name')));
            //enable start/end date field if set
            if(in_array($this->_mDef[$key]->get('field_type'), array('startdate', 'enddate')) && $obj->get($this->_mDef[$key]->get('field_name'))>0 && ! $obj->isNew()){
            	$this->set('enable_'.$this->_mDef[$key]->get('field_name'), 1);
            }
        }
        
        if($this->mUseTag){
            $tags = is_array($obj->mTag) ? implode(' ', $obj->mTag) : null;
            if(count($obj->mTag)>0) $tags = $tags.' ';
            $this->set('tags', $tags);
        }
    }

    /**
     * update
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function update(/*** XoopsSimpleObject ***/ &$obj)
    {
        //$obj->set('page_id', $this->get('page_id'));
        $obj->set('title', $this->get('title'));
        $obj->set('category_id', $this->get('category_id'));
        $obj->set('maintable_id', $this->get('maintable_id'));
        $obj->set('p_id', $this->get('p_id'));
        $obj->set('descendant', $this->get('descendant'));
        $obj->set('weight', $this->get('weight'));
        //$obj->set('status', $this->get('status'));
        $obj->set('updatetime', time());
        foreach($this->_mDef as $def){
            switch($def->get('field_type')){
            case Xcck_FieldType::DATE:
            	if($this->get($def->get('field_name'))){
	                $val = $this->_makeUnixtime($def->get('field_name'));
	            }
	            else{
	            	$val = 0;
	            }
                break;
            case Xcck_FieldType::STARTDATE:
            case Xcck_FieldType::ENDDATE:
            	if($this->get($def->get('field_name')) && $this->get('enable_'.$def->get('field_name'))==1){
	                $val = $this->_makeUnixtime($def->get('field_name'));
	            }
	            else{
	            	$val = 0;
	            }
                break;
            case Xcck_FieldType::TEXT:
                $val = $def->get('options')=='html' ? $this->_getPurifiedHtml($def->get('field_name')) : $this->get($def->get('field_name'));
                break;
            case Xcck_FieldType::CHECKBOX:
				$val = decbin(array_sum($this->get($def->get('field_name'))));
				//var_dump($this->get($def->get('field_name')));var_dump($val);die();
				break;
            default:
                $val = $this->get($def->get('field_name'));
                break;
            }
            $obj->set($def->get('field_name'), $val);
        }
    
        if($this->mUseTag){
            $obj->mTag = explode(' ', trim($this->get('tags')));
        }
    
        if($this->mUseMap){
            $obj->mLatlng = array($this->get('latitude'), $this->get('longitude'));
        }
        XCube_DelegateUtils::call('Module.'.$this->mDirname.'.ActionForm.Update', $this, $obj);
    }

    protected function _makeUnixtime($key)
    {
        $req = $this->get($key);
        $timeArray = explode('-', $req[0]);
        $hour = isset($req[1]) ? $req[1] : 0;
        $minute = isset($req[2]) ? $req[2] : 0;
        return mktime($hour, $minute, 0, $timeArray[1], $timeArray[2], $timeArray[0]);
    }

    /**
     * _getPurifiedHtml
     * 
     * @param   string  $key
     * @param   string  $encoding
     * @param   string  $doctype
     * 
     * @return  string
    **/
    protected function _getPurifiedHtml(/*** string ***/ $key, /*** string ***/ $encoding=null, /*** string ***/ $doctype=null)
    {
        $root = XCube_Root::getSingleton();
        $textFilter = $root->getTextFilter();
        return $textFilter->purifyHtml($this->get($key), $encoding, $doctype);
    }
}

?>
