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
 * Xcck_AbstractCagtegoryManager
**/
class Xcck_NoneCategoryManager extends Xcck_AbstractCategoryManager
{
	public $mType = 'none';

	/**
	 * __construct
	 * 
	 * @param	string	$categoryDir	category module's dirname
	 * @param	string	$dirname	this module's dirname
	 * @param	string	$dataname	this module's dataname
	 * 
	 * @return	void
		**/
	public function __construct(/*** string ***/ $categoryDir, /*** string ***/ $dirname, /*** string ***/ $dataname=null)
	{
		$this->_mDirname = $dirname;
	}

	/**
	 * check
	 * 
	 * @param	int		$categoryId
	 * @param	string	$action
	 * 
	 * @return	bool
	**/
	public function check(/*** int ***/ $categoryId, /*** string ***/ $action, /*** string ***/ $dataname='page')
	{
		$check = false;
		$context = XCube_Root::getSingleton()->mContext;
		switch($action){
		case self::POST:
			$check = ($context->mUser->isInRole('Site.Owner')) ? true : false;
			break;
		case self::MANAGE:
			$check = ($context->mUser->isInRole('Site.Owner')) ? true : false;
			break;
		case self::VIEW:
			$check = true;
			break;
		}
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
		$this->check($categoryId, Xcck_AuthType::POST);
	}

	/**
	 * getTree
	 * 
	 * @param	string	$action
	 * @param	int		$categoryId
	 * 
	 * @return	XoopsSimpleObject[]
	**/
	public function getTree(/*** string ***/ $action, /*** int ***/ $categoryId=0)
	{
		return array();
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
		return null;
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
		return array();
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
		return array();
	}
}

?>
