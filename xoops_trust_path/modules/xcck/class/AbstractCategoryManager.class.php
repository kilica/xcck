<?php
/**
 * @file
 * @package Xcck
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

/**
 * Xcck_AbstractCagtegoryManager
**/
Abstract class Xcck_AbstractCategoryManager
{
	const VIEW = "view";
	const POST = "post";
	const REVIEW = 'review';
	const MANAGE = "manage";
	protected $_mCategoryDirKey = null;
	protected $_mDirname = null;

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
	**/
	abstract public function __construct(/*** string ***/ $CategoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname=null);

	/**
	 * getCategoryType
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getCategoryType()
	{
		return $this->mType;
	}

	/**
	 * getModuleName
	 * 
	 * @param	string $dirname	//xcck dirname
	 * 
	 * @return	string
	**/
	public function getModuleName()
	{
		if($this->mType=='cat' || $this->mType=='group'){
			$accessController = Xcck_Utils::getAccessController($this->_mDirname);
			if($accessController instanceof XoopsModule){
				return $accessController->name();
			}
		}
	}

	/**
	 * _getAuthType
	 * 
	 * @param	string $action	//view, edit, manage, etc
	 * 
	 * @return	string
	**/
	protected function _getAuthType(/*** string ***/ $action)
	{
		@$authSetting = XCube_Root::getSingleton()->mContext->mModuleConfig['auth_type'];
		$authType = isset($authSetting) ? explode('|', $authSetting) : array('viewer', 'poster', 'reviewer', 'manager');
		switch($action){
			case self::VIEW:
				return trim($authType[0]);
				break;
			case self::POST:
				return trim($authType[1]);
				break;
			case self::REVIEW:
				return trim($authType[2]);
				break;
			case self::MANAGE:
				return trim($authType[3]);
				break;
		}
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$action
	 * @param	string	$dataname
	 * 
	 * @return	string
	**/
	abstract public function check(/*** int ***/ $categoryId, /*** string ***/ $action, /*** string ***/ $dataname='page');

	/**
	 * isPoster
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	bool
	**/
	abstract public function isPoster(/*** int ***/ $categoryId=0);

	/**
	 * isEditor
	 * 
	 * @param	int		$categoryId
	 * @param	int		$posterUid
	 * 
	 * @return	bool
	**/
	public function isEditor(/*** int ***/ $categoryId, /*** int ***/ $posterUid)
	{
		$uid = Legacy_Utils::getUid();
		//is Manager ?
		if($this->check($categoryId, Xcck_AuthType::MANAGE, 'page')===true){
			return true;
		}
		//is old post and your post ?
		if($posterUid==$uid && $uid>0){
			return true;
		}
		return false;
	}

	/**
	 * getTree
	 * 
	 * @param	string	$action	//action
	 * @param	int		$categoryId
	 * 
	 * @return	XoopsSimpleObject[]
	**/
	abstract public function getTree(/*** string ***/ $action, /*** int ***/ $categoryId=0);

	/**
	 * getTitle
	 * 
	 * @param	int		$categoryId
	 * 
	 * @return	string
	**/
	abstract public function getTitle(/*** int ***/ $categoryId);

	/**
	 * getTitleList
	 * 
	 * @param	void
	 * 
	 * @return	string[]
	**/
	abstract public function getTitleList();

	/**
	 * getPermittedIdList
	 * 
	 * @param	string	$action		//edit, view, manage, etc
	 * @param	int		$categoryId
	 * 
	 * @return	int[]
	**/
	abstract public function getPermittedIdList(/*** string ***/ $action, /*** int ***/ $categoryId=0);
}

?>
