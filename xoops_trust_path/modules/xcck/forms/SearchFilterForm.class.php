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

require_once XCCK_TRUST_PATH . '/forms/PageFilterForm.class.php';

/**
 * Xcck_SearchFilterForm
**/
class Xcck_SearchFilterForm extends Xcck_PageFilterForm
{
	public $mHandler = null;	//Xcck_DefinitionHandler

    /**
     * fetch
     * 
     * @param   string	$dirname
     * 
     * @return  void
    **/
    public function fetch($dirname)
    {
        $this->mNavi->fetch();
        $this->fetchSort();	
    
    	$this->mHandler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $request = XCube_Root::getSingleton()->mContext->mRequest;
	
        if (($catId = $request->getRequest('category_id')) !== null) {
            $this->mNavi->addExtra('category_id', intval($catId));
        }
	
		//get child category's data ?
		$child = false;
		if($request->getRequest('child')=="all"){
			$child = true;
		}
		elseif($request->getRequest('child')=="single"){
			$child = false;
		}
	
		$this->_mCriteria = Xcck_Utils::getListCriteria(
			$dirname,
			intval($catId),
			$child,
			$this->mSort,
			Lenum_Status::PUBLISHED
		);
	
    	//Search previously defined fields
    	$definedFields = array(
    		'name'=>array('page_id', 'title', 'uid', 'maintable_id', 'posttime', 'updatetime'),
    		'type'=>array(Xcck_FieldType::INT, Xcck_FieldType::STRING, Xcck_FieldType::INT, Xcck_FieldType::INT, Xcck_FieldType::DATE, Xcck_FieldType::DATE)
    	);
    	foreach(array_keys($definedFields['name']) as $key){
	        if($value = $request->getRequest($definedFields['name'][$key])!==null){
    			$this->_setDefinedFieldRequest($definedFields['name'][$key], $definedFields['type'][$key], $value);
    		}
    	}
	
    	//Search User defined fields
        foreach($this->mDefinitions as $definition){
	        if ($value = $request->getRequest($definition->get('field_name')) !== null) {
	            $value = $request->getRequest($definition->get('field_name'));
	            $this->_setRequest($definition, $value);
	        }
        }
	
        //Search by keyword: search all string and text field
        if (($value = $request->getRequest('keyword')) !== null) {
            $this->mNavi->addExtra('keywords', $value);
            Xcck_SearchUtils::makeSearchCriteria($this->_mCriteria, $dirname, $value);
        }
	
        if (($value = $request->getRequest('child')) !== null) {
            $this->mNavi->addExtra('child', $value);
        }
    
    	//filter by tag
		if(($tags = $request->getRequest('tag')) !==null){
			$this->_setTagRequest($tags, $dirname);
		}
	
        //$this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }

    /**
     * Set defined field's request
     * 
     * @param   string	$fieldName
     * @param   string	$fieldType
     * @param   mixed	$value	//requested value
     * 
     * @return  void
    **/
	protected function _setDefinedRequest(/*** string ***/ $fieldName, /*** string ***/ $fieldType, /*** mixed ***/ $value)
	{
		$definition = $this->mHandler->create();
		$definition->set('field_name', $fieldName);
		$definition->set('field_type', $fieldType);
		$definition->set('label', constant('_MD_XCCK_LANG_'.strtoupper($fieldName)));
	
		$this->_setRequest($definition, $value);
	}


    /**
     * _setRequest
     * 
     * @param   Xcck_DefinitionObject	$definition
     * @param   mixed[]	$request
     * 
     * @return  void
    **/
    protected function _setRequest(Xcck_DefinitionObject $definition, /*** mixed[] ***/ $request)
    {
        if (! isset($request)) return;
    	foreach(array_keys($request) as $key){
    		if(count($request[$key])!==2) continue;
    		$value = $request[$key][0];
    		$cond = intval($request[$key][1]);	//Xcck_Cond search condition
    		//set criteria
    		switch($definition->get('field_type')){
    		case Xcck_FieldType::STRING:
    		case Xcck_FieldType::TEXT:
    		case Xcck_FieldType::URI:
    			if($cond===Xcck_Cond::LIKE || $conf===Xcck_Cond::NOTLIKE){
			        $reqArr = Xcck_SearchUtils::splitKeywords($value);
			        if(count($reqArr)===0){
			            continue 2;
			        }
			        $cri = new CriteriaCompo();
			        foreach($reqArr as $value){
			            $cri->add(new Criteria($definition->get('field_name'), Xcck_SearchUtils::makeKeyword($value), Xcck_Cond::getString($cond)));
			        }
			        $this->_mCriteria->add($cri);
    			}
    			else{
			        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $value, Xcck_Cond::getString($cond)));
    			}
    			break;
    		case Xcck_FieldType::DATE:
    			if($cond===Xcck_Cond::LIKE){
			        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $this->_makeUnixtime($value, 'start'), Xcck_Cond::getString(Xcck_Cond::GE)));
			        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $this->_makeUnixtime($value, 'end'), Xcck_Cond::getString(Xcck_Cond::LT)));
    			}
    			elseif($cond===Xcck_Cond::LE){
			        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $this->_makeUnixtime($value, 'end'), Xcck_Cond::getString(Xcck_Cond::LT)));
    			}
    			else{
			        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $this->_makeUnixtime($value, 'start'), Xcck_Cond::getString($cond)));
    			}
    			break;
    		default:
		        $this->_mCriteria->add(new Criteria($definition->get('field_name'), $value, Xcck_Cond::getString($cond)));
    			break;
    		}
	    
	    	//set page navi
	        $this->mNavi->addExtra($definition->get('field_name'), $request);
//	        $this->mNavi->addExtra(sprintf('%s[%d][%d]', $definition->get('field_name'), $key, 1), $request[$key][1]);
	    }
	}

    /**
     * _makeUnixtime
     * 
     * @param   string	$value	//Y-m-d or Ymd format date
     * @param   string	$type	//return starttime or endtime
     * 
     * @return  void
    **/
	protected function _makeUnixtime(/*** string ***/ $value, /*** int ***/ $type='start')
	{
    	$value = str_replace('-', '', $value);
    	$len = strlen($value);
    	switch($len){
    	case 4:	//year
    		$starttime = mktime(0,0,0,1,1,$value);
    		$endtime = mktime(0,0,0,1,1,$value+1);
    		break;
    	case 6:	//year-month
    		$starttime = mktime(0,0,0,substr($value, 4,2),1,substr($value,0,4));
    		$endtime = mktime(0,0,0,substr($value, 4,2)+1,1,substr($value,0,4));
    		break;
    	case 8:	//year-month-day
    		$starttime = mktime(0,0,0,substr($value, 4,2),substr($value, 6,2),substr($value, 0,4));
    		$endtime = $starttime + 86400;
    		break;
    	}
    	if($type==='start'){
    		return $starttime;
    	}
    	elseif($type==='end'){
    		return $endtime;
    	}
	}

}

?>
