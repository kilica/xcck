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
 * Xcck_PageDeleteForm
**/
class Xcck_PageDeleteForm extends XCube_ActionForm
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
        return "module.xcck.PageDeleteForm.TOKEN";
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
        $this->mFormProperties['page_id'] = new XCube_IntProperty('page_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['page_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['page_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['page_id']->addMessage('required', _MD_XCCK_ERROR_REQUIRED, _MD_XCCK_LANG_PAGE_ID);
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
        $obj->set('page_id', $this->get('page_id'));
    }
}

?>
