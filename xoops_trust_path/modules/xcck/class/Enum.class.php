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

interface Xcck_AuthType
{
    const VIEW = "view";
    const POST = "post";
    const REVIEW = "review";
    const MANAGE = "manage";
}

/**
 * Xcck_Order
**/
class Xcck_Order
{
    const NONE = 0;	//set no order
    const WEIGHT_ASC = 1;
    const WEIGHT_DESC = 2;
    const UPDATETIME_ASC = 3;
    const UPDATETIME_DESC = 4;
}

/**
 * Xcck_ActionType
**/
class Xcck_ActionType
{
    const NONE = 0;
    const EDIT = 1;
    const VIEW = 2;
}


/**
 * Xcck_Cond
**/
class Xcck_Cond
{
    const EQ = 0;
    const NE = 1;
    const LIKE = 2;
    const NOTLIKE = 3;
    const LT = 4;
    const LE = 5;
    const GT = 6;
    const GE = 7;
    const IN = 8;

    /**
     * get Xcck_Cond string from value
     * 
     * @param   Enum   $num		Xcck_Cond const value
     * 
     * @return  string
    **/
	public static function getString(/*** int ***/ $num)
	{
		$ret = null;
		switch($num){
			case 0:
				$ret = '=';
				break;
			case 1:
				$ret = '<>';
				break;
			case 2:
				$ret = 'LIKE';
				break;
			case 3:
				$ret = 'NOT LIKE';
				break;
			case 4:
				$ret = '<';
				break;
			case 5:
				$ret = '<=';
				break;
			case 6:
				$ret = '>';
				break;
			case 7:
				$ret = '>=';
				break;
			case 8:
				$ret = 'IN';
				break;
		}
		return $ret;
	}

    /**
     * get Xcck_Cond value from string
     * 
     * @param   string   $str		Xcck_Cond const value
     * 
     * @return  Enum
    **/
	public static function getValue(/*** string ***/ $num)
	{
		$ret = null;
		switch($num){
			case '=':
			case 'EQ':
				$ret = 0;
				break;
			case '<>':
			case 'NE':
				$ret = 1;
				break;
			case 'LIKE':
				$ret = 2;
				break;
			case 'NOTLIKE':
				$ret = 3;
				break;
			case '<':
			case 'LT':
				$ret = 4;
				break;
			case '<=':
			case 'LE':
				$ret = 5;
				break;
			case '>':
			case 'GT':
				$ret = 6;
				break;
			case '>=':
			case 'GE':
				$ret = 7;
				break;
			case 'IN':
				$ret = 8;
				break;
		}
		return $ret;
	}
}


?>
