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

require_once XCCK_TRUST_PATH . '/admin/actions/ConfigCatWizardAction.class.php';

/**
 * Xcck_Admin_ConfigGroupWizardAction
**/
class Xcck_Admin_ConfigGroupWizardAction extends Xcck_Admin_ConfigCatWizardAction
{
	protected $_mType = 'Group';

	/**
	 * _getList
	 * 
	 * @param	void
	 * 
	 * @return	mixed[]
	**/
	protected function _getList()
	{
		return Legacy_Utils::getCommonModuleList('group');
	}
}

?>