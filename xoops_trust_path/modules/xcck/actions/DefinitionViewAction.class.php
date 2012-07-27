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

require_once XCCK_TRUST_PATH . '/class/AbstractViewAction.class.php';

/**
 * Xcck_DefinitionViewAction
**/
class Xcck_DefinitionViewAction extends Xcck_AbstractViewAction
{
	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Xcck_DefinitionHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Definition');
		return $handler;
	}

	/**
	 * _getPageTitle
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getPagetitle()
	{
		return $this->mObject->getShow('field_name');
	}

	/**
	 * hasPermission
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function hasPermission()
	{
		return $this->_isDefinitionEditor();
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function prepare()
	{
		parent::prepare();
		$this->_setupCategoryManager('page');
	
		return true;
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_definition_view.html');
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('isSubtable', $this->_isSubtable());
	}

	/**
	 * executeViewError
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeRedirect($this->_getNextUri('definition', 'list'), 1, _MD_XCCK_ERROR_CONTENT_IS_NOT_FOUND);
	}
}

?>
