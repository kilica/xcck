<?php
/**
 * @file
 * @package xcck
 * @version $Id$
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

class Xcck_CreateBlogModule extends XCube_ActionFilter
{
	/**
	 * @public
	 */
	function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy.Admin.Event.ModuleInstall.' . basename(dirname(__FILE__)) . '.Success',array(&$this, 'createBlogModule'));
	}

//			XCube_DelegateUtils::call('Legacy.Admin.Event.ModuleInstall.' . ucfirst($this->mXoopsModule->get('dirname') . '.Success'), new XCube_Ref($this->mXoopsModule), new XCube_Ref($this->mInstaller->mLog));

	/**
	 *	@public
	*/
	public function createBlogModule(&$module, &$log)
	{
		$handler = Legacy_Utils::getModuleHandler('definition', $dirname);
		$arr = self::_getFieldSetting();
		foreach($arr as $field){
			$obj = $handler->create();
			self::_setupObject($obj, $field);
			$handler->insert($obj, true);
		}
	}

	protected static function _setupObject(/*** Xcck_DefinitionObject ***/ $obj, /*** mixed[] ***/ $field)
	{
        $obj->set('field_name', $field['field_name']);
        $obj->set('label', $field['label']);
        $obj->set('field_type', $field['field_type']);
        $obj->set('validation', $field['validation']);
        $obj->set('required', $field['required']);
        $obj->set('weight', $field['weight']);
        $obj->set('show_list', $field['show_list']);
        $obj->set('search_flag', $field['search_flag']);
        $obj->set('description', $field['description']);
        $obj->set('options', $field['options']);
	}

	protected static function _getFieldSetting()
	{
		$settingArr = array();
	
		$arr = array();
        $arr['field_name'] = 'content';
        $arr['label'] = '内容';
        $arr['field_type'] = 'text';
        $arr['validation'] = '';
        $arr['required'] = false;
        $arr['weight'] = 20;
        $arr['show_list'] = false;
        $arr['search_flag'] = 0;
        $arr['description'] = '';
        $arr['options'] = 'html';
        $settingArr[] = $arr;
	
        return $settingArr;
	}
}

?>
