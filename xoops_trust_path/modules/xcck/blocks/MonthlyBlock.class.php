<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit();
}

/**
 * Xcck_MonthlyBlock
**/
class Xcck_MonthlyBlock extends Legacy_BlockProcedure
{
    /**
     * @var Xcck_pageHandler
     * 
     * @private
    **/
    private $_mHandler = null;
    
    private $_mResult = array();
    
    /**
     * @var string[]
     * 
     * @private
    **/
    private $_mOptions = array();
    
    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @public
    **/
    function prepare()
    {
        return parent::prepare() && $this->_parseOptions() && $this->_setupObject();
    }
    
    /**
     * _parseOptions
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    function _parseOptions()
    {
        $opts = explode('|',$this->_mBlock->get('options'));
        if(isset($opts[1])){
        	$definitionExists = Legacy_Utils::getModuleHandler('definition', $this->_mBlock->get('dirname'))->getCount(new Criteria('field_name', $opts[1]));
        	$dateField = count($definitionExists)>0 ? $opts[1] : 'posttime';
        }
        else{
        	$dateField = 'posttime';
        }
        $this->_mOptions = array(
            'catIds'	=> $opts[0],
            'date_field'	=> $dateField,
            'year'	=> intval($opts[2])
        );
        return true;
    }
    
    /**
     * getBlockOption
     * 
     * @param   string  $key
     * 
     * @return  string
     * 
     * @public
    **/
    function getBlockOption($key)
    {
        return isset($this->_mOptions[$key]) ? $this->_mOptions[$key] : null;
    }
    
    /**
     * getOptionForm
     * 
     * @param   void
     * 
     * @return  string
     * 
     * @public
    **/
    function getOptionForm()
    {
        if(!$this->prepare())
        {
            return null;
        }
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_catIds">'._AD_XCCK_LANG_SHOW_CAT.'</label>&nbsp;:
		<input type="text" size="64" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_catIds" value="'.$this->getBlockOption('catIds').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_date_field">'._AD_XCCK_LANG_DATE_FIELD.'</label>&nbsp;:
		<input type="text" size="64" name="options[1]" id="'. $this->_mBlock->get('dirname') .'block_date_field" value="'.$this->getBlockOption('date_field').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_year">'._AD_XCCK_LANG_YEAR.'</label>&nbsp;:
		<input type="text" size="5" name="options[2]" id="'. $this->_mBlock->get('dirname') .'block_year" value="'.$this->getBlockOption('year').'" />'._AD_XCCK_LANG_YEAR;
		return $form;
    }

    /**
     * _setupObject
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    function _setupObject()
    {
    	$categoryIds = null;
		$objects = array();
		$catIdArr = array();

    	//get block options
    	$year = $this->getBlockOption('year');
    	$dateField = $this->getBlockOption('date_field');
    
        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.xcck.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler =& $asset->getObject('handler','page');
        $criteria = Xcck_Utils::getListCriteria($this->_mBlock->get('dirname'), null, false);
        XCube_DelegateUtils::call('Module.'.$this->_mBlock->get('dirname').'.SetupBlockCriteria', new XCube_Ref($criteria), $this->_mBlock->get('bid'));
    	if($this->getBlockOption('catIds')){
	    	$categoryIds = explode(',', $this->getBlockOption('catIds'));
	    	if(count($categoryIds)>0){
		    	$criteria->add(new Criteria('category_id', $categoryIds, 'IN'));
		    }
	    }
	    $count = array();
	    if($year>1900){
	    	for($i=1;$i<13;$i++){
	    		$cri = clone $criteria;
		    	$cri->add(new Criteria($dateField, strtotime(sprintf('%d-%02d-01', $year, $i)), ('>=')));
		    	$cri->add(new Criteria($dateField, strtotime(sprintf('%d-%02d-01', $year, $i+1)), ('<')));
		    	$count[$year][$i] = $this->_mHandler->getCount($cri);
		    	unset($cri);
		    }
	    }
	    else{
	    	$startCri = clone $criteria;
	    	$startCri->setSort($dateField, 'ASC');
	    	$objs = $this->_mHandler->getObjects($startCri, 1);
	    	if($objs){
				$obj = array_shift($objs);
	    		$startYear = date('Y', $obj->get($dateField));
	    		$startMonth = date('m', $obj->get($dateField));
	    	}
	    	$endCri = clone $criteria;
	    	$endCri->setSort($dateField, 'DESC');
	    	$objs = $this->_mHandler->getObjects($endCri, 1);
	    	if($objs){
				$obj = array_shift($objs);
	    		$endYear = date('Y', $obj->get($dateField));
	    		$endMonth = date('m', $obj->get($dateField));
	    	}
			$ym = $startYear *100 + $startMonth;
			$endYm = $endYear *100 + $endMonth;
	    	while($ym <= $endYm){
				$year = substr($ym, 0, 4);
				$month = substr($ym, 4, 2);
	    		$cri = clone $criteria;
		    	$cri->add(new Criteria($dateField, strtotime(sprintf('%d-%02d-01', $year, $month)), ('>=')));
		    	$cri->add(new Criteria($dateField, strtotime(sprintf('%d-%02d-01', $year, $month)." + 1 month"), ('<')));
	    		$count[$year.'-'.$month] = $this->_mHandler->getCount($cri);
	    		unset($cri);
	    		if($month==12){
	    			$month=1;
	    			$year++;
	    		}
	    		else{
	    			$month++;
	    		}
				$ym = $year*100 + $month;
	    	}
	    }
		// 降順にする（年月の新しいものから順番に並べる
		krsort($count);
		foreach (array_keys($count) as $key) {
			list($year, $month) = explode('-', $key);
			$ret[$year][$month] = $count[$key];
		}
	    $this->_mResult = $ret;
	
        return true;
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  void
     * 
     * @public
    **/
    function execute()
    {
        $root =& XCube_Root::getSingleton();
    
        $render =& $this->getRenderTarget();
        $render->setTemplateName($this->_mBlock->get('template'));
        $render->setAttribute('block', $this->_mResult);
        $render->setAttribute('bid', $this->_mBlock->get('bid'));
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        $renderSystem->renderBlock($render);
    }
}

?>
