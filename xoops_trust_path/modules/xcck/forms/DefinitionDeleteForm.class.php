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
 * Xcck_DefinitionDeleteForm
**/
class Xcck_DefinitionDeleteForm extends XCube_ActionForm
{
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.xcck.DefinitionDeleteForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['definition_id'] = new XCube_IntProperty('definition_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['definition_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['definition_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['definition_id']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_DEFINITION_ID);
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
        $obj->set('definition_id', $this->get('definition_id'));
    }
}

?>
