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

require_once XCCK_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Xcck_DefinitionEditAction
**/
class Xcck_DefinitionEditAction extends Xcck_AbstractEditAction
{
    /**
     * &_getHandler
     * 
     * @param   void
     * 
     * @return  Xcck_DefinitionHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Definition');
        return $handler;
    }

    /**
     * _getPageTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getPagetitle()
    {
        return $this->mObject->getShow('field_name');
    }

    /**
     * hasPermission
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function hasPermission()
    {
		return $this->_isDefinitionEditor();
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        parent::prepare();
        $this->_setupCategoryManager('page');
    
        return true;
    }

    /**
     * _setupActionForm
     * 
     * @param   void
     * 
     * @return  void
    **/
    protected function _setupActionForm()
    {
        $this->mActionForm =& $this->mAsset->getObject('form', 'Definition',false,'edit');
        $this->mActionForm->prepare($this->mAsset->mDirname);
    }

    /**
     * _setHeaderScript
     * 
     * @param   void
     * 
     * @return  void
    **/
    protected function _setHeaderScript()
    {
        $headerScript = $this->mRoot->mContext->getAttribute('headerScript');
        $type = $this->mActionForm->get('field_type');
        $headerScript->addScript('
$(".optionField input, .optionField select, .optionField textarea").attr("disabled", "disabled");
$(".optionField").addClass("hideOption");
$("#fieldtype_'. $type .'").removeClass("hideOption");
$("#fieldtype_'. $type .' input, #fieldtype_'. $type .' select, #fieldtype_'. $type .' textarea").removeAttr("disabled");
$("#legacy_xoopsform_field_type").change(function(){
  $(".optionField").addClass("hideOption");
  $("#fieldtype_"+$(this).val()).removeClass("hideOption");
  $("#fieldtype_"+$(this).val()+" input, #fieldtype_"+$(this).val()+" select, #fieldtype_"+$(this).val()+" textarea").removeAttr("disabled");
});'
        );
    }

    protected function _checkDuplicate()
    {
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('definition_id', $this->mObject->get('definition_id'), '!='));
        $cri->add(new Criteria('field_name', $this->mObject->get('field_name')));
        if(count($this->_getHandler()->getObjects($cri))>0){
            $this->mRoot->mController->executeRedirect($this->_getNextUri('definition', 'list'), 1, _MD_XCCK_ERROR_DUPLICATE_DATA);
        }
    }

    /**
     * executeViewInput
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
    {
        $typeList = new Xcck_FieldType();
        $handler = $this->_getHandler();
        $render->setTemplateName($this->mAsset->mDirname . '_definition_edit.html');
        $render->setAttribute('actionForm', $this->mActionForm);
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('typeArr', $typeList->getTypeList());
        $render->setAttribute('validationArr', $handler->getValidationList());
        $render->setAttribute('isSubtable', $this->_isSubtable());
        $render->setAttribute('catDirnames', Legacy_Utils::getCommonModuleList('cat'));
    }

    /**
     * executeViewSuccess
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname, 'definition'));
    }

    /**
     * executeViewError
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeRedirect($this->_getNextUri('definition'), 1, _MD_XCCK_ERROR_DBUPDATE_FAILED);
    }

    /**
     * executeViewCancel
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeForward($this->_getNextUri('definition'));
    }
}

?>
