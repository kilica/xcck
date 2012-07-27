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
 * Xcck_CategoryBlock
**/
class Xcck_CategoryBlock extends Legacy_BlockProcedure
{
    /**
     * @var Xcck_TopicHandler
     * 
     * @private
    **/
    protected $_mHandler = null;
    
    /**
     * @protected Legacy_AbstractCategoryObject
     * 
     * @private
    **/
    protected $_mOject = null;
    
    /**
     * @protected int
     * 
     * @private
    **/
    protected $_mCount = array();
    
    /**
     * @protected string[]
     * 
     * @private
    **/
    protected $_mOptions = array();
    
    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @public
    **/
    public function prepare()
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
    protected function _parseOptions()
    {
        $opts = explode('|',$this->_mBlock->get('options'));
        $this->_mOptions = array(
            'catId'	=> $opts[0],
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
    public function getBlockOption($key)
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
    public function getOptionForm()
    {
        if(!$this->prepare())
        {
            return null;
        }
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_catId">'._AD_XCCK_LANG_SHOW_CAT.'</label>&nbsp;:
		<input type="text" size="64" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_catId" value="'.$this->getBlockOption('catId').'" /><br />';
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
    protected function _setupObject()
    {
		$objects = array();
		$accessController = Xcck_Utils::getAccessController($this->_mBlock->get('dirname'));
		if(! ($accessController instanceof XoopsModule)){
			return false;
		}
    
        $this->_mHandler = Legacy_Utils::getModuleHandler('page', $this->_mBlock->get('dirname'));
    
    	XCube_DelegateUtils::call('Legacy_Category.'.$accessController->get('dirname').'.GetTree', new XCube_Ref($this->_mObject), $accessController->get('dirname'), 'view', $this->getBlockOption('catId'));
    
    	foreach(array_keys($this->_mObject) as $key){
    		$this->_mCount[$key] = $this->_mHandler->countPageByCategory($this->_mObject[$key]->get('cat_id'));
    	}
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
    public function execute()
    {
        $root =& XCube_Root::getSingleton();
    
        $render =& $this->getRenderTarget();
        $render->setTemplateName($this->_mBlock->get('template'));
        $render->setAttribute('tree', $this->_mObject);
        $render->setAttribute('countArr', $this->_mCount);
        $render->setAttribute('bid', $this->_mBlock->get('bid'));
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        $renderSystem->renderBlock($render);
    }
}

?>
