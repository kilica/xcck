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

require_once XCCK_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Xcck_PageEditAction
**/
class Xcck_PageEditAction extends Xcck_AbstractEditAction
{
	public $mDefinitions = array();

	/**
	 * _getId
	 * 
	 * @param   void
	 * 
	 * @return  int
	**/
	protected function _getId()
	{
		$id = parent::_getId();
		if(! $id && $this->mRoot->mContext->mModuleConfig['singlepost']){
			$id = Xcck_Utils::getMyId($this->mAsset->mDirname);
		}
		return $id;
	}

	/**
	 * _getCatId
	 * 
	 * @param   void
	 * 
	 * @return  int
	**/
	protected function _getCatId()
	{
		$catId = 0;
		if($this->mObject->get('category_id')>0){
			$catId = $this->mObject->get('category_id');
		}
		elseif($pId = $this->_getParentId()>0){
			$handler = Legacy_Utils::getModuleHandler('page', $this->mAsset->mDirname);
			$parent = $handler->get($pId);
			$catId = $parent->get('category_id');
		}
		elseif(intval($this->mRoot->mContext->mRequest->getRequest('category_id'))>0){
			$catId = intval($this->mRoot->mContext->mRequest->getRequest('category_id'));
		}
		return $catId;
	}

	/**
	 * _getParentId
	 * 
	 * @param   void
	 * 
	 * @return  int
	**/
	protected function _getParentId()
	{
		return intval($this->mRoot->mContext->mRequest->getRequest('p_id'));
	}

	/**
	 * &_getHandler
	 * 
	 * @param   void
	 * 
	 * @return  Xcck_PageHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'page');
		return $handler;
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
		return $this->mObject->getShow('title');
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
		return Xcck_AuthType::POST;
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
		$catId = $this->_getCatId();
	
		if($catId>0){
			//is Manager ?
			if($this->mCategoryManager->check($this->_getCatId(), Xcck_AuthType::MANAGE, 'page')==true){
				return true;
			}
			//is new post and has post permission ?
			$check = $this->mCategoryManager->check($this->_getCatId(), Xcck_AuthType::POST, 'page');
			if($check==true && $this->mObject->isNew()){
				return true;
			}
			//is old post and your post ?
			if($check==true && ! $this->mObject->isNew() && $this->mObject->get('uid')==Legacy_Utils::getUid() && $this->mObject->get('uid')>0){
					return true;
			}
		}
		else{
			$idList = $this->mCategoryManager->getPermittedIdList(Xcck_AuthType::POST, $catId);
			if(count($idList)>0 || ($this->mCategoryManager->getCategoryType()=='none' && $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser'))){
				return true;
			}
		}
	
		return false;
	}

	/**
	 * _setupActionForm
	 * 
	 * @param   void
	 * 
	 * @return  void
	**/
	protected function _setupActionForm()
	{
		$this->mActionForm =& $this->mAsset->getObject('form', 'page', false, 'edit');
		$this->mActionForm->prepare($this->mAsset->mDirname);
	}

	/**
	 * _setupObject
	 *
	 * @param	void
	 *
	 * @return	void
	 **/
	protected function _setupObject()
	{
		$handler = Legacy_Utils::getModuleHandler('revision', $this->mAsset->mDirname);
		$id = $this->_getId();

		$this->mObjectHandler =& $this->_getHandler();

		$this->mObject = $handler->getLatestRevision($id, Lenum_Status::REJECTED);

		if($this->mObject == null && $this->_isEnableCreate())
		{
			$this->mObject =& $handler->create();
		}
	}

	protected function _prepare()
	{
		$request = $this->mRoot->mContext->mRequest;
		$textFilter = $this->mRoot->getTextFilter();
		$setupFields = explode(",", Xcck_Utils::getModuleConfig($this->mAsset->mDirname, 'setup_fields'));
		foreach($setupFields as $field){
			$this->mObject->set($field, $textFilter->toEdit($request->getRequest($field)));
		}
		XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.PrepareEditAction', $this->mObject);
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
		parent::prepare();

		$this->mObject->set('status', Xcck_Utils::getDefaultStatus($this->mAsset->mDirname));

		//subtable requires category_id(parent id)
		if($this->_getCatId()==0 && $this->_isSubtable()){
			$this->mRoot->mController->executeRedirect($this->_getNextUri('page', 'list'), 1, _MD_XCCK_ERROR_MAINTABLE_REQUIRED);
		}
	
		$this->mDefinitions = Legacy_Utils::getModuleHandler('definition', $this->mAsset->mDirname)->getFields();
		if($this->mObject->isNew()){
			$this->mObject->set('uid', Legacy_Utils::getUid());
			$this->mObject->set('p_id', $this->_getParentId());
			$this->mObject->set('category_id', $this->_getCatId());
		}
		$this->_setupCategoryManager('page');
		$this->mObject->loadPath();
	
		//setup tags
		$this->mObject->loadTag();
		$this->_prepare();

		return true;
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
		$render->setTemplateName($this->mAsset->mDirname . '_page_edit.html');
	
		$this->_setupAttributes($render);
	
		$render->setAttribute('categoryType', $this->mCategoryManager->getCategoryType()=='none' ? false : true);
		$render->setAttribute('accessController', $this->mCategoryManager);
	
		$render->setAttribute('isSubtable', $this->_isSubtable());
		$render->setAttribute('defaultOrder', $this->mRoot->mContext->mModuleConfig['default_order']);
	
		//date field option
		$render->setAttribute('hours', range(1,24));
		$render->setAttribute('minutes', range(0,59));
	
	
		//set main category
		$render->setAttribute('mainCatTree',$this->mCategoryManager->getTree(Xcck_AuthType::POST)); 
		//set categories of custom field
		$tree = array();
		$groups = array();
		foreach($this->mDefinitions as $field){
			if($field->get('field_type')==Xcck_FieldType::CATEGORY){
				$tree[$field->get('field_name')] = array();
				XCube_DelegateUtils::call('Legacy_Category.'.$field->get('options').'.GetTree', new XCube_Ref($tree[$field->get('field_name')]), $field->get('options'), 'viewer');
			}
            /*
			elseif($field->get('field_type')==Xcck_FieldType::GROUP){
				$groups[$field->get('field_name')] = array();
				XCube_DelegateUtils::call('Legacy_Group.'.$field->get('options').'.GetGroupListByAction', new XCube_Ref($groups[$field->get('field_name')]), $field->get('options'), $this->mAsset->mDirname, 'page', 'view');
			}
            */
		}
		$render->setAttribute('catTree',$tree);
		$render->setAttribute('groupList',$groups);
	}

	/**
	 * setup render attributes commonly used in Input and Preview
	 * 
	 * @param   XCube_RenderTarget  &$render
	 * 
	 * @return  void
	**/
	protected function _setupAttributes(XCube_RenderTarget $render)
	{
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$dataname = $this->_isSubtable() ? 'subtable' : 'page';
		$render->setAttribute('dataname', $dataname);
		$render->setAttribute('fields',$this->mDefinitions);
	
		//set tag usage
		$render->setAttribute('useTag', ($this->mRoot->mContext->mModuleConfig['tag_dirname']) ? true : false);
		$render->setAttribute('tag_dirname', $this->mRoot->mContext->mModuleConfig['tag_dirname']);
	
		//set map usage
		$render->setAttribute('useMap', ($this->mRoot->mContext->mModuleConfig['use_map']) ? true : false);
	
		//setup images
		$this->mObject->setupImages($isPost=false);
		$render->setAttribute('imageObjs', $this->mObject->mImage);
		$render->setAttribute('imageNameList', Xcck_Utils::getImageNameList($this->mAsset->mDirname));
	
		$render->setAttribute('xoops_breadcrumbs', $this->_getBreadcrumb($this->mObject));
	}

	/**
	 * _doExecute
	 *
	 * @param	void
	 *
	 * @return	Enum
	 **/
	protected function _doExecute()
	{
        $page = Xcck_Utils::setupPageByRevision($this->mObject);

		if($this->mObjectHandler->insert($page)===false){
            return XCCK_FRAME_VIEW_ERROR;
        }
        $this->mObject->set('page_id', $page->get('page_id'));
        if($this->mObject->revise()===false){
            return XCCK_FRAME_VIEW_ERROR;
        }

        if(Xcck_Utils::getModuleConfig($this->mAsset->mDirname, 'publish')=='linear'){
            if($this->mObject->getShow('status')!=Lenum_Status::DELETED){
                $this->_saveWorkflow($this->mObject);
            }
        }

        return XCCK_FRAME_VIEW_SUCCESS;
	}

    /**
     * save workflow
     *
     * @param XoopsSimpleObject	$obj
     *
     * @return	void
     */
    protected function _saveWorkflow(/*** XoopsSimpleObject ***/ $obj)
    {
        XCube_DelegateUtils::call(
            'Legacy_Workflow.AddItem',
            $obj->getShow('title'),
            $obj->getDirname(),
            'page',
            $obj->get('page_id'),
            Legacy_Utils::renderUri($obj->getDirname(), 'revision', $obj->get('revision_id'))
        );
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
		$headerScript = $this->mRoot->mContext->getAttribute('headerScript');
		//DatePicker Script
		$headerScript->addScript($this->_getDatePickerScript());
		//$headerScript->addScript("\n".'$(".enableDate").each(function(){var targetId = "#date-"+$(this).attr("id").split("_").pop();$(targetId+" input,"+targetId+" select").each(function(){this.disabled=true;})});'."\n");
		$headerScript->addScript("\n".'$(".enableDate").each(function(){var checkboxId=$(this).attr("id");var targetId="#date-"+checkboxId.split("_").pop();$(targetId+" input,"+targetId+" select").filter(function(){return $("#"+checkboxId).attr("checked")?false:true;}).attr("disabled", true)});'."\n");
		$headerScript->addScript('$(".enableDate").click(function(){var enableChecked = $(this).attr("checked");var targetId = "#date-"+$(this).attr("id").split("_").pop();$(targetId+" input,"+targetId+" select").each(function(){if(enableChecked=="checked"){$(this).attr("disabled",false);}else{$(this).attr("disabled", true);}})});'."\n");
	}

	/**
	 * _getGmapEditScript
	 * 
	 * @param   string  $fieldName
	 * 
	 * @return  string
	**/
	protected function _getGmapEditScript(/*** string ***/ $fieldName)
	{
		$mapObjName = $this->mAsset->mDirname.'_'.$fieldName.'_map';
		return sprintf('
google.maps.event.addListener(%s, "click", function(e) 
{
	// set position
	markerObj.position = e.latLng; 

	// set marker
	markerObj.setMap(%s); 
	$("#legacy_xoopsform_%s").value(e.latLng.lat()); 
	$("#legacy_xoopsform_%s").value(e.latLng.lng()); 
}); 
		', $mapObjName, $mapObjName, $fieldName, $fieldName);
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
		$url = null;
		XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Event.GetForwardUri.Success', new XCube_Ref($url), $this->mObject);
		if(isset($url)){
			$this->mRoot->mController->executeForward($url);
		}
	
		if($this->mRoot->mContext->mModuleConfig['forward_action']=='list'){
			$this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname));
		}
		if($this->mRoot->mContext->mModuleConfig['forward_action']=='search'){
			$this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname, 'page', 0, 'search'));
		}
		else{
			$this->mRoot->mController->executeForward($this->_getNextUri('page'));
		}
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
		$this->mRoot->mController->executeRedirect($this->_getNextUri('page', 'list'), 1, _MD_XCCK_ERROR_DBUPDATE_FAILED);
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
		$this->mRoot->mController->executeForward($this->_getNextUri('page'));
	}
}

?>
