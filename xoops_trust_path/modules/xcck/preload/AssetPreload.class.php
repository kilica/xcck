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

if(!defined('XCCK_TRUST_PATH'))
{
	define('XCCK_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/xcck');
}

require_once XCCK_TRUST_PATH . '/class/XcckUtils.class.php';


/**
 * Xcck_AssetPreloadBase
**/
class Xcck_AssetPreloadBase extends XCube_ActionFilter
{
	public $mDirname = null;

    /**
     * prepare
     * 
     * @param   string	$dirname
     * 
     * @return  void
    **/
    public static function prepare(/*** string ***/ $dirname)
    {
        $root =& XCube_Root::getSingleton();
        $instance = new Xcck_AssetPreloadBase($root->mController);
        $instance->mDirname = $dirname;
        $root->mController->addActionFilter($instance);
    }

	/**
	 * preBlockFilter
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function preBlockFilter()
	{
		$dirname = $this->mDirname;
	
		$this->mRoot->mDelegateManager->add('Module.xcck.Global.Event.GetAssetManager','Xcck_AssetPreloadBase::getManager');
		$this->mRoot->mDelegateManager->add('Legacy_Utils.CreateModule','Xcck_AssetPreloadBase::getModule');
		$this->mRoot->mDelegateManager->add('Legacy_Utils.CreateBlockProcedure','Xcck_AssetPreloadBase::getBlock');

		//client delegates
		$file = require_once XCCK_TRUST_PATH . '/class/callback/ClientDelegateFunctions.class.php';
		//Workflow Client
		$this->mRoot->mDelegateManager->add('Legacy_WorkflowClient.GetClientList','Xcck_WorkflowClientDelegate::getClientList', $file);
		$this->mRoot->mDelegateManager->add('Legacy_WorkflowClient.UpdateStatus','Xcck_WorkflowClientDelegate::updateStatus', $file);
		//Image Client
		$this->mRoot->mDelegateManager->add('Legacy_ImageClient.GetClientList','Xcck_ImageClientDelegate::getClientList', $file);
		//Category Client
		$this->mRoot->mDelegateManager->add('Legacy_CategoryClient.GetClientList','Xcck_CatClientDelegate::getClientList', $file);
		$this->mRoot->mDelegateManager->add('Legacy_CategoryClient.'.$dirname.'.GetClientData','Xcck_CatClientDelegate::getClientData', $file);
		//Group Client
		$this->mRoot->mDelegateManager->add('Legacy_GroupClient.GetClientList','Xcck_GroupClientDelegate::getClientList', $file);
		$this->mRoot->mDelegateManager->add('Legacy_GroupClient.'.$dirname.'.GetClientData','Xcck_GroupClientDelegate::getClientData', $file);
		$this->mRoot->mDelegateManager->add('Legacy_GroupClient.GetActionList','Xcck_GroupClientDelegate::getActionList', $file);
		//Tag Client
		$this->mRoot->mDelegateManager->add('Legacy_TagClient.GetClientList','Xcck_TagClientDelegate::getClientList', $file);
		$this->mRoot->mDelegateManager->add('Legacy_TagClient.'.$dirname.'.GetClientData','Xcck_TagClientDelegate::getClientData', $file);
		//Comment Clinet
		$this->mRoot->mDelegateManager->add('Legacy_CommentClient.GetClientList','Xcck_CommentClientDelegate::getClientList', $file);
		//Activity Client
		$this->mRoot->mDelegateManager->add('Legacy_ActivityClient.GetClientList','Xcck_ActivityClientDelegate::getClientList', $file);
		$this->mRoot->mDelegateManager->add('Legacy_ActivityClient.'.$dirname.'.GetClientData','Xcck_ActivityClientDelegate::getClientData', $file);
		$this->mRoot->mDelegateManager->add('Legacy_ActivityClient.'.$dirname.'.GetClientFeed','Xcck_ActivityClientDelegate::getClientFeed', $file);
		//$this->mRoot->mDelegateManager->add('Module.xcck.GetSubtableTableCriteria', 'Xcck_DelegateFunctions::getSubtableCriteria', $file);
	
		//common delegates
		$file = require_once XCCK_TRUST_PATH . '/class/callback/DelegateFunctions.class.php';
	
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.Global.Event.GetNormalUri','Xcck_DelegateFunctions::getNormalUri', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.Global.Event.GetBreadcrumbs','Xcck_DelegateFunctions::getBreadcrumbs', $file);
		$this->mRoot->mDelegateManager->add('Legacy.Event.UserDelete','Xcck_DelegateFunctions::deleteUserData', $file);
	
		//xcck delegates
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.SavePage', 'Xcck_DelegateFunctions::savePage', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.DeletePage', 'Xcck_DelegateFunctions::deletePage', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.GetDefinitionList', 'Xcck_DelegateFunctions::getDefinitionList', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.GetDefinition', 'Xcck_DelegateFunctions::getDefinition', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.GetPage', 'Xcck_DelegateFunctions::getPage', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.SetupActionForm', 'Xcck_DelegateFunctions::setupActionForm', $file);
		$this->mRoot->mDelegateManager->add('Module.'.$dirname.'.LoadActionForm', 'Xcck_DelegateFunctions::loadActionForm', $file);
	}

	/**
	 * getManager
	 * 
	 * @param	Xcck_AssetManager  &$obj
	 * @param	string	$dirname
	 * 
	 * @return	void
	**/
	public static function getManager(/*** Xcck_AssetManager ***/ &$obj,/*** string ***/ $dirname)
	{
		require_once XCCK_TRUST_PATH . '/class/AssetManager.class.php';
		$obj = Xcck_AssetManager::getInstance($dirname);
	}

	/**
	 * getModule
	 * 
	 * @param	Legacy_AbstractModule  &$obj
	 * @param	XoopsModule  $module
	 * 
	 * @return	void
	**/
	public static function getModule(/*** Legacy_AbstractModule ***/ &$obj,/*** XoopsModule ***/ $module)
	{
		if($module->getInfo('trust_dirname') == 'xcck')
		{
			require_once XCCK_TRUST_PATH . '/class/Module.class.php';
			$obj = new Xcck_Module($module);
		}
	}

	/**
	 * getBlock
	 * 
	 * @param	Legacy_AbstractBlockProcedure  &$obj
	 * @param	XoopsBlock	$block
	 * 
	 * @return	void
	**/
	public static function getBlock(/*** Legacy_AbstractBlockProcedure ***/ &$obj,/*** XoopsBlock ***/ $block)
	{
		$moduleHandler =& Xcck_Utils::getXoopsHandler('module');
		$module =& $moduleHandler->get($block->get('mid'));
		if(is_object($module) && $module->getInfo('trust_dirname') == 'xcck')
		{
			require_once XCCK_TRUST_PATH . '/blocks/' . $block->get('func_file');
			$className = 'Xcck_' . substr($block->get('show_func'), 4);
			$obj = new $className($block);
		}
	}
}

?>
