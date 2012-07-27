<?php
/**
 * @package xcck
 * @version $Id: DelegateFunctions.class.php,v 1.1 2007/05/15 02:35:07 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * category client delegate
**/
class Xcck_CatClientDelegate implements Legacy_iCategoryClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 *  @list[]['fieldname']
	 * @param string	$cDirname   Legacy_Category module's dirname
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $cDirname)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if($conf['access_controller']==$cDirname){
				$list[] = array('dirname'=>$dir, 'dataname'=>'page', 'fieldname'=>'category_id');
			}
		}
	
		$isCalled = true;
	}

	/**
	 * getClientData
	 *
	 * @param mixed	 &$list
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param string	$fieldname
	 * @param int	   $catId
	 *
	 * @return  void
	 */ 
	public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $fieldname, /*** int ***/ $catId)
	{
		//default
		$limit = 5;
		$start =0;
	
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
		//setup client module info
		$cri = Xcck_Utils::getListCriteria($dirname, $catId);
		$objs = $handler->getObjects($cri, $limit, $start);
		if(count($objs)>0){
			$list['dirname'][] = $dirname;
			$list['dataname'][] = $dataname;
			$list['data'][] = $objs;
			$handler = xoops_gethandler('module');
			$module = $handler->getByDirname($dirname);
			$list['title'][] = $module->name();
			$list['template_name'][] = 'db:'.$dirname . '_page_inc.html';
		}
	}
}

/**
 * tag client delegate
**/
class Xcck_TagClientDelegate implements Legacy_iTagClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 * @param string	$tDirname   Legacy_Tag module's dirname
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $tDirname)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if($conf['tag_dirname']==$tDirname){
				$list[] = array('dirname'=>$dir, 'dataname'=>'page');
			}
		}
	
		$isCalled = true;
	}

	/**
	 * getClientData
	 *
	 * @param mixed	 &$list
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int[]	 $idList
	 *
	 * @return  void
	 */ 
	public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int[] ***/ $idList)
	{
		//default
		$limit = 5;
		$start =0;
	
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
	
		//setup client module info
		$cri = Xcck_Utils::getListCriteria($dirname);
		$cri->add(new Criteria('page_id', $idList, 'IN'));
		$objs = $handler->getObjects($cri, $limit, $start);
		if(count($objs)>0){
			$list['dirname'][] = $dirname;
			$list['dataname'][] = $dataname;
			$list['data'][] = $objs;
			$handler = xoops_gethandler('module');
			$module = $handler->getByDirname($dirname);
			$list['title'][] = $module->name();
			$list['template_name'][] = 'db:'.$dirname . '_page_inc.html';
		}
	}
}


/**
 * comment client delegate
**/
class Xcck_CommentClientDelegate implements Legacy_iCommentClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  string  $list[]['dirname']
	 *  string  $list[]['dataname']
	 *  string  $list[]['access_controller']
	 * @param string	$cDirname   comment module's dirname
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $cDirname)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if($conf['comment_dirname']==$cDirname){
				$list[] = array('dirname'=>$dir, 'dataname'=>'page', 'access_controller'=>$conf['access_controller']);
			}
		}
	
		$isCalled = true;
	}
}

/**
 * activity client delegate
**/
class Xcck_ActivityClientDelegate implements Legacy_iActivityClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  string  $list[]['dirname']
	 *  string  $list[]['dataname']
	 *  string  $list[]['access_controller']
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			$list[] = array('dirname'=>$dir, 'dataname'=>'page', 'access_controller'=>$conf['access_controller']);
		}
	
		$isCalled = true;
	}

	/**
	 * getClientData
	 *
	 * @param mixed	 &$list
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int	   $dataId
	 *
	 * @return  void
	 */ 
	public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
	{
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
	
		//setup client module info
		$obj = $handler->get($dataId);
		if($obj instanceof Xcck_PageObject){
			$list['dirname'] = $dirname;
			$list['dataname'] = $dataname;
			$list['data_id'] = $dataId;
			$list['data'] = $obj;
			$handler = xoops_gethandler('module');
			$module = $handler->getByDirname($dirname);
			$list['title'] = $module->name();
			$list['template_name'] = 'db:'.$dirname . '_page_inc_activity.html';
		}
	}

	/**
	 * getClientFeed	Legacy_ActivityClient.{dirname}.GetClientFeed
	 *
	 * @param mixed	 &$list
	 *  string[]	$list['title']  entry's title
	 *  string[]	$list['link']   link to entry
	 *  string[]	$list['id']	 entry's id(=permalink to entry)
	 *  int[]	   $list['updated']	unixtime
	 *  int[]	   $list['published']  unixtime
	 *  string[]	$list['author']
	 *  string[]	$list['content']
	 * @param string	$dirname	client module's dirname
	 * @param string	$dataname   client module's dataname(tablename)
	 * @param int	   $dataId	 client module's primary key
	 *
	 * @return  void
	 */ 
	public static function getClientFeed(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
	{
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
	
		//setup client module info
		$obj = $handler->get($dataId);
		$list['title'] = $obj->get('title');
		$list['link'] = Legacy_Utils::renderUri($dirname, $dataname, $dataId);
		$list['id'] = Legacy_Utils::renderUri($dirname, $dataname, $dataId);
		$list['published'] = $obj->get('posttime');
		$list['updated'] = $obj->get('updatetime');
		$list['author'] = Legacy_Utils::getUserName($obj->get('uid'));
		$list['content'] = null;
	}
}

/**
 * workflow client delegate
**/
class Xcck_WorkflowClientDelegate implements Legacy_iWorkflowClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if($conf['publish']=='linear'){
				$list[] = array('dirname'=>$dir, 'dataname'=>'revision');
			}
		}
	
		$isCalled = true;
	}

	/**
	 * updateStatus
	 *
	 * @param string &$result
	 * @param string $dirname
	 * @param string $dataname
	 * @param int $id
	 * @param string $id
	 * @param Lenum_Status $status
	 *
	 * @return  void
	 */ 
	public static function updateStatus(/*** string ***/ &$result, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $id, /*** Lenum_Status ***/ $status)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
		foreach($dirnames as $xcckDirname){
			if($dirname == $xcckDirname && $dataname=='revision'){
				$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
				$handler->updateStatus($id, $status);
			}
		}
	
		$isCalled = true;
	}
}

/**
 * image client delegate
**/
class Xcck_ImageClientDelegate implements Legacy_iImageClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list)
	{
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		//don't call this method multiple times when site owner duplicate this module.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if(trim($conf['images'])){
				$list[] = array('dirname'=>$dir, 'dataname'=>'page');
			}
		}
	
		$isCalled = true;
	}
}

/**
 * group client delegate
**/
class Xcck_GroupClientDelegate implements Legacy_iGroupClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]   &$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 *  @list[]['fieldname']
	 * @param string	$gDirname   Legacy_Group module's dirname
	 *
	 * @return  void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $gDirname)
	{
		//don't call this method multiple times when site owner duplicate this module.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			if($conf['access_controller']==$gDirname){
				$list[] = array('dirname'=>$dir, 'dataname'=>'page', 'fieldname'=>'category_id');
			}
		}
	
		$isCalled = true;
	}

	/**
	 * getClientData
	 *
	 * @param mixed	 &$list
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param string	$fieldname
	 * @param int	   $groupId
	 *
	 * @return  void
	 */ 
	public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $fieldname, /*** int ***/ $groupId)
	{
		//default
		$limit = 5;
		$start =0;
	
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
		//setup client module info
		$cri = Xcck_Utils::getListCriteria($dirname, $groupId);
		$objs = $handler->getObjects($cri, $limit, $start);
		if(count($objs)>0){
			$list['dirname'][] = $dirname;
			$list['dataname'][] = $dataname;
			$list['data'][] = $objs;
			$handler = xoops_gethandler('module');
			$module = $handler->getByDirname($dirname);
			$list['title'][] = $module->name();
			$list['template_name'][] = 'db:'.$dirname . '_page_inc.html';
		}
	}

	/**
	 * getActionList
	 * Get client module's actions(view, edit, etc) to set their permission
	 * by member's group rank.
	 *
	 * @param mixed[]   &$list
	 *  $list['action'][]   string
	 *  $list['rank'][]	 Lenum_GroupRank
	 *  $list['title'][]	string
	 *  $list['desctiption'][]  string
	 * @param string	$dirname
	 * @param string	$dataname
	 *
	 * @return  void
	 */ 
	public static function getActionList(&$list, $dirname, $dataname)
	{
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
		if(! in_array($dirname, $dirnames)){
			return;
		}
	
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
		//	return;
		}
		XCube_Root::getSingleton()->mLanguageManager->loadModuleMessageCatalog($dirname);
	
		//view
		$list['action'][] = 'view';
		$list['rank'][] = Lenum_GroupRank::GUEST;
		$list['title'][] = _MD_XCCK_TITLE_ACTION_VIEW;
		$list['description'][] = _MD_XCCK_DESC_ACTION_VIEW;
	
		//post
		$list['action'][] = 'post';
		$list['rank'][] = Lenum_GroupRank::REGULAR;
		$list['title'][] = _MD_XCCK_TITLE_ACTION_POST;
		$list['description'][] = _MD_XCCK_DESC_ACTION_POST;
	
		//review
		$list['action'][] = 'review';
		$list['rank'][] = Lenum_GroupRank::STAFF;
		$list['title'][] = _MD_XCCK_TITLE_ACTION_REVIEW;
		$list['description'][] = _MD_XCCK_DESC_ACTION_REVIEW;
	
		//manage
		$list['action'][] = 'manage';
		$list['rank'][] = Lenum_GroupRank::STAFF;
		$list['title'][] = _MD_XCCK_TITLE_ACTION_MANAGE;
		$list['description'][] = _MD_XCCK_DESC_ACTION_MANAGE;
	
		$isCalled = true;
	}
}

class Xcck_MapClientDelegate //implements Legacy_iMapClientDelegate
{
	/**
	 * getClientList	Legacy_MapClient.GetClientList
	 *
	 * @param mixed[]	&$list
	 *  @list[]['dirname']	client module's dirname
	 *  @list[]['dataname']	client module's dataname(tablename)
	 *  @list[]['access_controller']	access controller's module dirname
	 *
	 * @return	void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list)
	{
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		//get dirname list of xCCK
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
	
		$handler = xoops_gethandler('config');
		foreach($dirnames as $dir){
			//setup client module info
			$conf = $handler->getConfigsByDirname($dir);
			$list[] = array('dirname'=>$dir, 'dataname'=>'page', 'access_controller'=>$conf['access_controller']);
		}
	
		$isCalled = true;
	}

	/**
	 * getClientData	Legacy_MapClient.{dirname}.GetClientData
	 *
	 * @param mixed		&$list
	 *  string	$list['dirname']	client module's dirname
	 *  string	$list['dataname']	client module's dataname(tablename)
	 *  int		$list['data_id']	client module's primary key
	 *  mixed	$list['data']
	 *  string  $list['title']		client module's title
	 *  string	$list['template_name']
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int		$dataId
	 *
	 * @return	void
	 */ 
	public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
	{
		$handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
		if(! $handler){
			return;
		}
	
		//setup client module info
		$obj = $handler->get($dataId);
		if($obj instanceof Xcck_PageObject){
			$list['dirname'] = $dirname;
			$list['dataname'] = $dataname;
			$list['data_id'] = $dataId;
			$list['data'] = $obj;
			$handler = xoops_gethandler('module');
			$module = $handler->getByDirname($dirname);
			$list['title'] = $module->name();
			$list['template_name'] = 'db:'.$dirname . '_page_inc_activity.html';
		}
	}
}
?>
