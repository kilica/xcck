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
 * Xcck_ListBlock
**/
class Xcck_ListBlock extends Legacy_BlockProcedure
{
    /**
     * @var Xcck_ItemsHandler
     * 
     * @private
    **/
    var $_mHandler = null;
    
    /**
     * @var Xcck_ItmesObject
     * 
     * @private
    **/
    var $_mOject = null;
    
    /**
     * @var string[]
     * 
     * @private
    **/
    var $_mOptions = array();
    
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
        $this->_mOptions = array(
            'limit'	=> (intval($opts[0])>0 ? intval($opts[0]) : 5),
            'catIds'	=> $opts[1],
            'order'	=> $opts[2]
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
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_limit">'._AD_XCCK_LANG_DISPLAY_NUMBER.'</label>&nbsp;:
		<input type="text" size="5" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_limit" value="'.$this->getBlockOption('limit').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_catIds">'._AD_XCCK_LANG_SHOW_CAT.'</label>&nbsp;:
		<input type="text" size="64" name="options[1]" id="'. $this->_mBlock->get('dirname') .'block_catIds" value="'.$this->getBlockOption('catIds').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_order">'._AD_XCCK_LANG_ORDER.'</label>&nbsp;:
		<input type="text" size="5" name="options[2]" id="'. $this->_mBlock->get('dirname') .'block_order" value="'.$this->getBlockOption('order').'" /> <a href="'.XOOPS_URL.'/modules/'.$this->_mBlock->get('dirname').'/admin/index.php?action=OrderShow">'._AD_XCCK_LANG_ORDER.'</a><br />';
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
    	$limit = $this->getBlockOption('limit');
    
        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.xcck.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler =& $asset->getObject('handler','page');
        $criteria = Xcck_Utils::getListCriteria($this->_mBlock->get('dirname'), null, false, $this->getBlockOption('order'));
        XCube_DelegateUtils::call('Module.'.$this->_mBlock->get('dirname').'.SetupBlockCriteria', $criteria, $this->_mBlock->get('bid'));
    	if($this->getBlockOption('catIds')){
	    	$categoryIds = explode(',', $this->getBlockOption('catIds'));
	    	if(count($categoryIds)>0){
		    	$criteria->add(new Criteria('category_id', $categoryIds, 'IN'));
		    }
	    }
        $criteria->setLimit($limit);
	
        $this->_mObject = $this->_mHandler->getObjects($criteria);
	
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
        $render->setAttribute('block', $this->_mObject);
        $render->setAttribute('bid', $this->_mBlock->get('bid'));
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        $list = array();
        $category = Xcck_Utils::getAccessController($this->_mBlock->get('dirname'));
        if($category){
            XCube_DelegateUtils::call('Legacy_Category.'.$category->get('dirname').'.GetTitleList', new XCube_Ref($list), $category->get('dirname'));
        }
        $render->setAttribute('catTitleList', $list);
        $renderSystem->renderBlock($render);
    }
}

?>
