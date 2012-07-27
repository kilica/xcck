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

require_once XCCK_TRUST_PATH . '/class/AbstractCategoryManager.class.php';

/**
 * Xcck_GroupManager
**/
class Xcck_GroupCategoryManager extends Xcck_AbstractCategoryManager
{
	public $mType = 'group';

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
	**/
	public function __construct(/*** string ***/ $categoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname=null)
	{
		$this->_mDirname = $dirname;
		$this->_mDataname = $dataname;
		$this->_mCategoryDir = $categoryDir;
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$action
	 * @param	string	$dataname
	 * 
	 * @return	bool
	**/
	public function check(/*** int ***/ $categoryId, /*** string ***/ $action, /*** string ***/ $dataname='page')
	{
		$check = null;
		XCube_DelegateUtils::call('Legacy_Group.'.$this->_mCategoryDir.'.HasPermission', new XCube_Ref($check), $this->_mCategoryDir, $categoryId, $this->_mDirname, $this->_mDataname, $action);
		return $check;
	}

	/**
	 * isPoster
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	bool
	**/
	public function isPoster(/*** int ***/ $categoryId=0)
	{
		if($categoryId>0){
			if($this->check($categoryId, Xcck_AuthType::POST, 'page')===true){
				return true;
			}
		}
		else{
			$categoryIds = $this->getPermittedIdList(Xcck_AuthType::POST);
			if(count($categoryIds)>0){
				return true;
			}
		}
		return false;
	}

	/**
	 * getTree
	 * 
	 * @param	string	$action
	 * @param	string	$categoryId	//dummy
	 * 
	 * @return	Legacy_AbstractGroupObject
	**/
	public function getTree($action, $categoryId=0)
	{
		$tree = null;
		XCube_DelegateUtils::call('Legacy_Group.'.$this->_mCategoryDir.'.GetGroupListByAction', new XCube_Ref($tree), $this->_mCategoryDir, $this->_mDirname, $this->_mDataname, $action);
		return $tree;
	}

	/**
	 * getTitle
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	string
	**/
	public function getTitle(/*** int ***/ $categoryId)
	{
		$ret = null;
		XCube_DelegateUtils::call('Legacy_Group.'.$this->_mCategoryDir.'.GetTitle', new XCube_Ref($ret), $this->_mCategoryDir, $categoryId);
		return $ret;
	}

	/**
	 * getTitleList
	 * 
	 * @param	void
	 * 
	 * @return	string[]
	**/
	public function getTitleList()
	{
		$list = null;
		XCube_DelegateUtils::call('Legacy_Group.'.$this->_mCategoryDir.'.GetTitleList', new XCube_Ref($list), $this->_mCategoryDir);
		return $list;
	}

	/**
	 * getPermittedIdList
	 * 
	 * @param	string	$action
	 * @param	int		$categoryId
	 * 
	 * @return	int[]
	**/
	public function getPermittedIdList(/*** string ***/ $action, /*** int ***/ $categoryId=0)
	{
		$idList = array();
		XCube_DelegateUtils::call('Legacy_Group.'.$this->_mCategoryDir.'.GetGroupIdListByAction', new XCube_Ref($idList), $this->_mCategoryDir, $this->_mDirname, $this->_mDataname, $action);
		return $idList;
	}
}

?>
