<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

require_once XCCK_TRUST_PATH.'/class/File.class.php';
if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

class Xcck_FieldType
{
    const STRING = 'string';
    const TEXT = 'text';
    const INT = 'int';
    const FLOAT = 'float';
    const DATE = 'date';
    const STARTDATE = 'startdate';
    const ENDDATE = 'enddate';
    const CHECKBOX = 'checkbox';
    const SELECTBOX = 'selectbox';
    const CATEGORY = 'category';
//	const GROUP = 'group';
    const URI = 'uri';
    const FILE = 'file';
//    const RATER = 'rater';
//	const MAP = 'map';

    /**
     * getTypeList
     * 
     * @param   void
     * 
     * @return  string[]
    **/
    public static function getTypeList()
    {
        $ref = new ReflectionClass('Xcck_FieldType');
        return $ref->getConstants();
    }
}

interface Xcck_iFieldType
{
    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0);
    public function getTableQuery();
    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default);
    public function getDefault(/*** string ***/ $option);
    public function getXObjType();
    public function getFormPropertyClass();
    public function getSearchFormString(/*** string ***/ $key);
    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null);
}


/** --------------------------------------------------------
 *  String Type
**/
class Xcck_FieldTypeString implements Xcck_iFieldType
{
    const TYPE = 'string';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::VIEW){
            $value = $obj->getShow($key);
        }
        elseif($option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }
    
        return $value;
    }

    public function getTableQuery()
    {
        return 'VARCHAR(255) NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false, 255);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return isset($option) ? $option : '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_STRING;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_StringProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}


/** --------------------------------------------------------
 *  Text Type
**/
class Xcck_FieldTypeText implements Xcck_iFieldType
{
    const TYPE = 'text';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::VIEW){
            switch($this->getOption($obj->mDef[$key], 'filter')){
            case 'purifier':
            case 'none':
                $value = $obj->get($key);
                break;
            case 'bbcode':
            default:
                $value = $obj->getShow($key);
                break;
            }
        }
        elseif($option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }
        return $value;
    }

    public function getTableQuery()
    {
        return 'text NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_TEXT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_TextProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /> <input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        $options = explode('|', $def->get('options'));
        switch($key){
        case 'editor':
            return $options[0];
            break;
        case 'filter':
            return $options[1];
            break;
        }
    }
}


/** --------------------------------------------------------
 *  Int Type
**/
class Xcck_FieldTypeInt implements Xcck_iFieldType
{
    const TYPE = 'int';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        return $obj->get($key);
    }

    public function getTableQuery()
    {
        return 'INT(11) SIGNED NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return isset($option) ? $option : '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_INT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_IntProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}



/** --------------------------------------------------------
 *  Float Type
**/
class Xcck_FieldTypeFloat implements Xcck_iFieldType
{
    const TYPE = 'float';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        return $obj->get($key);
    }

    public function getTableQuery()
    {
        return 'decimal(10,4) UNSIGNED NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return isset($option) ? $option : 0;
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_FLOAT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_FloatProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}


/** --------------------------------------------------------
 *  Date Type
**/
class Xcck_FieldTypeDate implements Xcck_iFieldType
{
    const TYPE = 'date';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE){
            $value = $obj->get($key);
        }
        elseif($option==Xcck_ActionType::EDIT){
            $value = array(date(_PHPDATEPICKSTRING, $obj->get($key)), date('H', $obj->get($key)), date('i', $obj->get($key)));
        }
        elseif($option==Xcck_ActionType::VIEW){
            $format = (in_array($obj->mDef[$key]->get('options'), array('hour','half','quarter','10min','min'))) ? "m" : "s";
            $value = ($obj->get($key)) ? formatTimestamp($obj->get($key), $format) : "";
        }
    
        return $value;
    }

    public function getTableQuery()
    {
        return 'INT(11) UNSIGNED NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return time();
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_INT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_StringArrayProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" class="datePicker" /> <input type="text" value="" name="<{$key}>" class="datePicker" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}

/** --------------------------------------------------------
 *  Start Date Type
**/
class Xcck_FieldTypeStartdate extends Xcck_FieldTypeDate
{
    const TYPE = 'startdate';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE){
            $value = $obj->get($key);
        }
        elseif($option==Xcck_ActionType::EDIT){
        	$date = $obj->get($key)==0 ? time() : $obj->get($key);
            $value = array(date(_PHPDATEPICKSTRING, $date), date('H', $date), date('i', $date));
        }
        elseif($option==Xcck_ActionType::VIEW){
            $format = (in_array($obj->mDef[$key]->get('options'), array('hour','half','quarter','10min','min'))) ? "m" : "s";
            $value = ($obj->get($key)) ? formatTimestamp($obj->get($key), $format) : "";
        }
    
        return $value;
    }
}

/** --------------------------------------------------------
 *  End Date Type
**/
class Xcck_FieldTypeEnddate extends Xcck_FieldTypeStartdate
{
    const TYPE = 'enddate';
}

/** --------------------------------------------------------
 *  Selectbox Type
**/
class Xcck_FieldTypeSelectbox implements Xcck_iFieldType
{
    const TYPE = 'selectbox';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::VIEW){
            $value = $obj->getShow($key);
        }
        elseif($option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }
        return $value;
    }

    public function getTableQuery()
    {
        return 'VARCHAR(60) NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_STRING;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_StringProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return preg_split('/\x0d\x0a|\x0d|\x0a/', $def->get('options'), null);
    }
}


/** --------------------------------------------------------
 *  Category Type
**/
class Xcck_FieldTypeCategory implements Xcck_iFieldType
{
    const TYPE = 'category';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }
        elseif($option==Xcck_ActionType::VIEW){
            $catDir = $obj->mDef[$key]->get('options');
            XCube_DelegateUtils::call('Legacy_Category.'.$catDir.'.GetTitle', new XCube_Ref($value), $catDir, $obj->get($key));
        }
        return $value;
    }

    public function getTableQuery()
    {
        return 'INT(11) SIGNED NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return 0;
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_INT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_IntProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}


/** --------------------------------------------------------
 *  Group Type
 **/
class Xcck_FieldTypeGroup extends Xcck_FieldTypeCategory
{
	const TYPE = 'group';

	public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
	{
		if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::EDIT){
			$value = $obj->get($key);
		}
		elseif($option==Xcck_ActionType::VIEW){
			$groupDir = $obj->mDef[$key]->get('options');
			XCube_DelegateUtils::call('Legacy_Group.'.$groupDir.'.GetTitle', new XCube_Ref($value), $groupDir, $obj->get($key));
		}
		return $value;
	}

	public function getSearchFormString(/*** string ***/ $key)
	{
		return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
	}

}


/** --------------------------------------------------------
 *  Uri Type
**/
class Xcck_FieldTypeUri implements Xcck_iFieldType
{
    const TYPE = 'uri';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::VIEW){
            $value = $obj->getShow($key);
        }
        elseif($option==Xcck_ActionType::NONE || $option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }
        return $value;
    }

    public function getTableQuery()
    {
        return 'text NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_TEXT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_TextProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        return $def->get('options');
    }
}

/** --------------------------------------------------------
 *  Checkbox Type
 **/
class Xcck_FieldTypeCheckbox implements Xcck_iFieldType
{
	const TYPE = 'checkbox';

	public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
	{
		$optionArr = $this->parseOptionString($obj->mDef[$key]->get('options'));
		$valueArr = array_reverse(str_split(str_pad($obj->get($key), count($optionArr['title']), "0", STR_PAD_LEFT)));
		switch($option){
		case Xcck_ActionType::NONE:
			$value = $valueArr;
			break;
		case Xcck_ActionType::EDIT:
			$value = array();
			foreach(array_keys($valueArr) as $key){
				$value[$key] = $valueArr[$key]==1 ? pow(2, $key) : 0;
			}
			break;
		case Xcck_ActionType::VIEW:
			$value = array('checked'=>array(), 'unchecked'=>array());
			foreach(array_keys($valueArr) as $key){
				if($valueArr[$key]==1){
					$value['checked'][$key] = $optionArr['title'][$key];
				}
				else{
					$value['unchecked'][$key] = $optionArr['title'][$key];
				}
			}
			break;
		}
		return $value;
	}

	public function getTableQuery()
	{
		return 'BIGINT UNSIGNED NOT NULL';
	}

	public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
	{
		$obj->initVar($key, $this->getXObjType(), $default, false);
	}

	public function getDefault(/*** string ***/ $option)
	{
		$optionsArr = $this->parseOptionString($option);
		return implode('', array_reverse($optionsArr['default']));
	}

	public function getXObjType()
	{
		return XOBJ_DTYPE_INT;
	}

	public function getFormPropertyClass()
	{
		//return 'XCube_IntArrayProperty';
		return 'Xcck_CheckboxArrayProperty';
	}

	public function getSearchFormString(/*** string ***/ $key)
	{
	}

	public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
	{
		$option = $this->parseOptionString($def->get('options'));
		return isset($key) ? $option[$key] : $option;
	}

	public function parseOptionString(/*** string ***/ $optionString)
	{
		$ret = array('title'=>array(), 'default'=>array());
		$optionsArr = preg_split('/\x0d\x0a|\x0d|\x0a/', $optionString, null);
		foreach(array_keys($optionsArr) as $key){
			$option = explode('|', $optionsArr[$key]);
			if($option[0]){
				$ret['title'][$key] = $option[0];
				$ret['default'][$key] = intval($option[1]);
			}
		}
		return $ret;
	}
}

/**
 * @public
 * @brief Represents int[] property. XCube_GenericArrayProperty<XCube_IntProperty>.
 * @see XCube_IntProperty
 */
class Xcck_CheckboxArrayProperty extends XCube_IntArrayProperty
{
	public function __construct($name)
	{
		parent::XCube_IntArrayProperty($name);
	}
/*
	public function hasFetchControl()
	{
		return true;
	}

	public function fetch(&$form)
	{
		unset($this->mProperties);
		$this->mProperties = array();
		foreach ($_FILES[$this->mName]['name'] as $_key => $_val) {
			$this->mProperties[$_key] = new $this->mPropertyClassName($this->mName);
			$this->mProperties[$_key]->mIndex = $_key;
			$this->mProperties[$_key]->fetch($form);
		}
	}
*/
	public function isNull()
	{
		$values = $this->get();
		return in_array(1, $values) ? false : true;
	}
}

/** --------------------------------------------------------
 *  Rater Type
**/
class Xcck_FieldTypeRater implements Xcck_iFieldType
{
    const TYPE = 'rater';

    public function showField(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        return $obj->get($key);
    }

    public function getTableQuery()
    {
        return 'TINYINT(3) SIGNED NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return 0;
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_INT;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_IntProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '<dt></dt><dd><input type="text" value="" name="<{$key}>" /></dd>';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
    	$options = preg_split('/\x0d\x0a|\x0d|\x0a/', $def->get('options'), null);
        if($key=='level'){
        	return $options[0];
        }
    }
}

/** --------------------------------------------------------
 *  String Type
 **/
class Xcck_FieldTypeFile implements Xcck_iFieldType
{
    const TYPE = 'file';

    public function showField(/*** Xcck_PageObject ***/ $obj, /*** string ***/ $key, /*** Xcck_ActionType ***/ $option=0)
    {
        if($option==Xcck_ActionType::NONE||$option==Xcck_ActionType::VIEW){
            $fileManager = new Xcck_File($obj);
            $value = $obj->getShow($key).' ('.$fileManager->getFileSize($key).'KB)';
        }
        elseif($option==Xcck_ActionType::EDIT){
            $value = $obj->get($key);
        }

        return $value;
    }

    public function getTableQuery()
    {
        return 'VARCHAR(255) NOT NULL';
    }

    public function setInitVar(/*** Xcck_DataObject ***/ $obj, /*** string ***/ $key, /*** string ***/ $default)
    {
        $obj->initVar($key, $this->getXObjType(), $default, false, 255);
    }

    public function getDefault(/*** string ***/ $option)
    {
        return isset($option) ? $option : '';
    }

    public function getXObjType()
    {
        return XOBJ_DTYPE_STRING;
    }

    public function getFormPropertyClass()
    {
        return 'XCube_StringProperty';
    }

    public function getSearchFormString(/*** string ***/ $key)
    {
        return '';
    }

    public function getOption(/*** Xcck_DefinitionsObject ***/ $def, /*** string ***/ $key=null)
    {
        $options = explode('|', $def->get('options'));
        switch($key){
            case 'maxFileSize':
                return $options[0];
                break;
            case 'allowedExtensions':
                return explode(',',$options[1]);
                break;
        }
        return $options;
    }
}



?>
