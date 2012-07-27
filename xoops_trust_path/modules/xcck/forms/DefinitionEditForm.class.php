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
 * Xcck_DefinitionEditForm
**/
class Xcck_DefinitionEditForm extends XCube_ActionForm
{
    protected /*** string ***/ $_mDirname;
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.xcck.DefinitionEditForm.TOKEN";
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
        $this->_mDirname = $dirname;
        //
        // Set form properties
        //
        $this->mFormProperties['definition_id'] = new XCube_IntProperty('definition_id');
        $this->mFormProperties['field_name'] = new XCube_StringProperty('field_name');
        $this->mFormProperties['label'] = new XCube_StringProperty('label');
        $this->mFormProperties['field_type'] = new XCube_StringProperty('field_type');
        $this->mFormProperties['validation'] = new XCube_StringProperty('validation');
        $this->mFormProperties['required'] = new XCube_BoolProperty('required');
        $this->mFormProperties['weight'] = new XCube_IntProperty('weight');
        $this->mFormProperties['show_list'] = new XCube_BoolProperty('show_list');
        $this->mFormProperties['search_flag'] = new XCube_BoolProperty('search_flag');
        $this->mFormProperties['description'] = new XCube_TextProperty('description');
        $this->mFormProperties['options'] = new XCube_TextProperty('options');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['definition_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['definition_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['definition_id']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_DEFINITION_ID);
    
        $this->mFieldProperties['field_name'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['field_name']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['field_name']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_FIELD_NAME, '32');
        $this->mFieldProperties['field_name']->addMessage('maxlength', _MD_XCCK_ERROR_MAXLENGTH, _MD_XCCK_LANG_FIELD_NAME, '32');
        $this->mFieldProperties['field_name']->addVar('maxlength', '32');
    
        $this->mFieldProperties['label'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['label']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['label']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_LABEL, '255');
        $this->mFieldProperties['label']->addMessage('maxlength', _MD_XCCK_ERROR_MAXLENGTH, _MD_XCCK_LANG_LABEL, '255');
        $this->mFieldProperties['label']->addVar('maxlength', '255');
    
        $this->mFieldProperties['field_type'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['field_type']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['field_type']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_FIELD_TYPE, '16');
        $this->mFieldProperties['field_type']->addMessage('maxlength', _MD_XCCK_ERROR_MAXLENGTH, _MD_XCCK_LANG_FIELD_TYPE, '16');
        $this->mFieldProperties['field_type']->addVar('maxlength', '16');
    
        $this->mFieldProperties['weight'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['weight']->setDependsByArray(array('required'));
        $this->mFieldProperties['weight']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_WEIGHT);
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
        $this->set('definition_id', $obj->get('definition_id'));
        $this->set('field_name', $obj->get('field_name'));
        $this->set('label', $obj->get('label'));
        $this->set('field_type', $obj->get('field_type'));
        $this->set('validation', $obj->get('validation'));
        $this->set('required', $obj->get('required'));
        $this->set('weight', $obj->get('weight'));
        $this->set('show_list', $obj->get('show_list'));
        $this->set('search_flag', $obj->get('search_flag'));
        $this->set('description', $obj->get('description'));
        $this->set('options', $obj->get('options'));
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
        //$obj->set('definition_id', $this->get('definition_id'));
        $obj->set('field_name', $this->get('field_name'));
        $obj->set('label', $this->get('label'));
        $obj->set('field_type', $this->get('field_type'));
        $obj->set('validation', $this->get('validation'));
        $obj->set('required', $this->get('required'));
        $obj->set('weight', $this->get('weight'));
        $obj->set('show_list', $this->get('show_list'));
        $obj->set('search_flag', $this->get('search_flag'));
        $obj->set('description', $this->get('description'));
        $obj->set('options', $this->get('options'));
    }

}

?>
