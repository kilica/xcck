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
require_once XCCK_TRUST_PATH . '/class/Enum.class.php';

/**
 * Xcck_AbstractAction
**/
abstract class Xcck_AbstractAction
{
    public /*** XCube_Root ***/ $mRoot = null;

    public /*** Xcck_Module ***/ $mModule = null;

    public /*** Xcck_AssetManager ***/ $mAsset = null;

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        $this->mRoot =& XCube_Root::getSingleton();
        $this->mModule =& $this->mRoot->mContext->mModule;
        $this->mAsset =& $this->mModule->mAssetManager;
    }

    /**
     * _getTagRequest
     * 
     * @param   void
     * 
     * @return  int
    **/
    protected function _getTagRequest()
    {
        return $this->mRoot->mContext->mRequest->getRequest('tag');
    }

    /**
     * _getCategoryFields
     * 
     * @param   void
     * 
     * @return  int[]
    **/
    protected function _getCategoryFields()
    {
        $catObjs = Legacy_Utils::getModuleHandler('definition', $this->mAsset->mDirname)->getObjects(new Criteria('field_type', Xcck_FieldType::CATEGORY));
        foreach($catObjs as $cat){
            if(! $cat->get('options')){
                unset($cat);
            }
        }
        return $catObjs;
    }

    /**
     * _getConfigForCategory
     * 
     * @param   void
     * 
     * @return  mixed[]
    **/
    protected function _getConfigForCategory()
    {
        return $this->mRoot->mContext->mModuleConfig;
    }

    /**
     * _setupCategoryManager
     * 
     * @param   string  $dataname
     * 
     * @return  void
    **/
    protected function _setupCategoryManager(/*** string ***/ $dataname)
    {
        $server = Xcck_Utils::getAccessController($this->mAsset->mDirname);
        if($server instanceof XoopsModule){
        	$serverDirname = $server->dirname();
        }
    
        //get server's role
        $handler = xoops_gethandler('module');
        //$module = $handler->getByDirname($serverDirname);
        if(! isset($serverDirname) || ! $module=$handler->getByDirname($serverDirname)){
            require_once XCCK_TRUST_PATH . '/class/NoneCategoryManager.class.php';
            $this->mCategoryManager = new Xcck_NoneCategoryManager(null, $this->mAsset->mDirname, $dataname);
            return;
        }
        $role = $module->get('role');
    
        switch($role){
        case 'cat':
            require_once XCCK_TRUST_PATH . '/class/CatCategoryManager.class.php';
            $this->mCategoryManager = new Xcck_CatCategoryManager($serverDirname, $this->mAsset->mDirname, $dataname);
            break;
        case 'group':
            require_once XCCK_TRUST_PATH . '/class/GroupCategoryManager.class.php';
            $this->mCategoryManager = new Xcck_GroupCategoryManager($serverDirname, $this->mAsset->mDirname, $dataname);
            break;
        default:
            require_once XCCK_TRUST_PATH . '/class/NoneCategoryManager.class.php';
            $this->mCategoryManager = new Xcck_NoneCategoryManager($serverDirname, $this->mAsset->mDirname, $dataname);
            break;
        }
    }

    /**
     * _isSubtable()
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function _isSubtable()
    {
        return ($this->mRoot->mContext->mModuleConfig['maintable']) ? true : false;
    }

    /**
     * _getXcckAuthType
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function _getXcckAuthType()
    {
        return Xcck_AuthType::VIEW;
    }

    /**
     * _getActionName
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getActionName()
    {
        return null;
    }

    /**
     * _getPageTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getPagetitle()
    {
        return null;
    }

    /**
     * getPageTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getPagetitle()
    {
        $title = null;
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.SetPagetitle', new XCube_Ref($title), $this->mAsset->mDirname, $this->_getPagetitle(), $this->_getActionName());

        return isset($title) ? $title : Legacy_Utils::formatPagetitle($this->mRoot->mContext->mModule->mXoopsModule->get('name'), $this->_getPagetitle(), $this->_getActionName());
    }

    /**
     * _getStylesheet
     * 
     * @param   void
     * 
     * @return  String
    **/
    protected function _getStylesheet()
    {
        return $this->mRoot->mContext->mModuleConfig['css_file'];
    }

    /**
     * _setHeaderScript
     * 
     * @param   void
     * 
     * @return  void
    **/
    protected function _setHeaderScript()
    {
    }

    /**
     * setHeaderScript
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function setHeaderScript()
    {
        $headerScript = $this->mRoot->mContext->getAttribute('headerScript');
        if(file_exists(XOOPS_ROOT_PATH.$this->_getStylesheet())){
	        $headerScript->addStylesheet($this->_getStylesheet());
	    }
        $this->_setHeaderScript();
    }

    /**
     * getAuthType
     * 
     * @param   string  $type
     * 
     * @return  string
    **/
    public function getAuthType()
    {
        $authType = new Xcck_AuthType($this->mAsset->mDirname);
        return $authType->getAuthType($this->_getXcckAuthType());
    }

	protected function _getBreadcrumb(/*** XoopsSimpleObject ***/ $object=null)
	{
    	$breadcrumbs = array();
    	XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $object);
    	return $breadcrumbs;
    }

    /**
     * _isDefinitionEditor
     * 
     * @param   void
     * 
     * @return  bool
    **/
    protected function _isDefinitionEditor()
    {
    	if(! $role = $this->mRoot->getSiteConfig($this->mAsset->mDirname.'.Definition', 'editor')){
    		$role = 'Site.Owner';
    	}
        return ($this->mRoot->mContext->mUser->isInRole($role)) ? true : false;
	}

    /**
     * isEditor
     * 
     * @param   string  $type
     * 
     * @return  bool
    **/
    public function isEditor()
    {
        ///TODO
        if($this->mObject->get('uid')==Legacy_Utils::getUid()>0){
            return true;
        }
    
        return $this->mCategoryManager->check($this->mObject->get('category_id'), Xcck_AuthType::MANAGE);
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        return true;
    }

    /**
     * hasPermission
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function hasPermission()
    {
        return true;
    }

    /**
     * getDefaultView
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    public function getDefaultView()
    {
        return XCCK_FRAME_VIEW_NONE;
    }

    /**
     * _getDatePickerScript
     * 
     * @param   void
     * 
     * @return  String
    **/
    protected function _getDatePickerScript()
    {
        return '
$(".datepicker").each(function(){$(this).datepicker({dateFormat: "'._JSDATEPICKSTRING.'"});});';
    }

    /**
     * _getGMapScript
     * 
     * @param   void
     * 
     * @return  String
    **/
    protected function _getGMapScript(Xcck_DefinitionObject $field, /*** decimal ***/ $lat=null, /*** decimal ***/ $lon=null)
    {
        //[0]default latitude, [1]default longitude, [2]zoom
        $options = $field->getOptions();
        $latitude = isset($lat) ? $lat : $options[0];
        $longitude = isset($lng) ? $lng : $options[1];
        $prefix = $field->getDirname() .'_'. $field->getShow('field_name');
    
        return sprintf('
var %s_Latlng = new google.maps.LatLng(%s, %s);
var %s_Options = {
  zoom: %d,
  center: %s_Latlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};
var markerObj = new google.maps.Marker({ 
    position: %s_Latlng, 
    draggable: true, 
    title: "marker", 
    map: %s 
}); 

var %s_map = new google.maps.Map($("#%s_map").get(0), %s_Options);', $prefix, $latitude, $longitude, $prefix, $options[2], $prefix, $prefix, $prefix.'_map', $prefix, $prefix, $prefix);
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    public function execute()
    {
        return XCCK_FRAME_VIEW_NONE;
    }

    /**
     * executeViewSuccess
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewError
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewIndex
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewInput
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewPreview
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewPreview(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewCancel
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * _getNextUri
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getNextUri($tableName, $actionName=null)
    {
        $handler = $this->_getHandler();
        if($this->mObject->get($handler->mPrimary)>0){
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, $this->mObject->get($handler->mPrimary), $actionName);
        }
        else{
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, 0, $actionName);
        }
    }
}

?>
