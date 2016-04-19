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
class Xcck_TreeBlock extends Legacy_BlockProcedure
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
    var $_mObject = null;
    
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
        $opts = explode('|', $this->_mBlock->get('options'));
        $this->_mOptions = array(
            'catIds'	=> $opts[0],
            'order'	=> $opts[1]
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
		$form = '
		<label for="'. $this->_mBlock->get('dirname') .'block_catIds">'._AD_XCCK_LANG_SHOW_CAT.'</label>&nbsp;:
		<input type="text" size="64" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_catIds" value="'.$this->getBlockOption('catIds').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_order">Parent ID</label>&nbsp;:
		<input type="text" size="5" name="options[1]" id="'. $this->_mBlock->get('dirname') .'block_p_id" value="'.$this->getBlockOption('p_id').'" /><br />';
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
        $root = XCube_Root::getSingleton();
        $request = $root->mContext->mRequest;
    	$categoryIds = null;

        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.xcck.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler =& $asset->getObject('handler','page');
        // by xacro
        $criteria = Xcck_Utils::getListCriteria($this->_mBlock->get('dirname'), null, false, 8);
        XCube_DelegateUtils::call('Module.'.$this->_mBlock->get('dirname').'.SetupBlockCriteria', $criteria, $this->_mBlock->get('bid'));

        $categoryIds = explode(',', $this->getBlockOption('catIds'));
        $pageIds = array();
        if ($this->getBlockOption('p_id') > 0) {
            $pageIds = array($this->getBlockOption('p_id'));
        }
    	elseif (count($categoryIds) > 0) {
        	$criteria->add(new Criteria('category_id', $categoryIds, 'IN'));
            $criteria->add(new Criteria('p_id', 0));
            $criteria->setSort('category_id', 'ASC');
            $criteria->addSort('weight');
            $pageIds = $this->_mHandler->getIdList($criteria);
	    }
        else {
            $pageId = $request->getRequest('page_id');
            $categoryId = $pageId->get('category_id');

            $criteria->setSort('category_id', 'ASC');
            $criteria->addSort('weight');
            $criteria->add(new Criteria('p_id', 0));
            $pageIds = $this->_mHandler->getIdList($criteria);
        }

        $objects = array();
        foreach ($pageIds as $id) {
            $tree = $this->_mHandler->getTree($id);
            foreach ($tree as $page) {
                $objects[] = $page;
            }
        }

        $this->_mObject = $objects;
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
        $render->setAttribute('dataname', 'page');
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
