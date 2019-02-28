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

require_once XCCK_TRUST_PATH.'/forms/PageFilterForm.class.php';

/**
 * Xcck_PageFilterForm
**/
class Xcck_RevisionFilterForm extends Xcck_PageFilterForm
{
    /**
     * fetch
     * 
     * @param   string	$dirname
     * 
     * @return  void
    **/
    public function fetch($dirname)
    {
        parent::fetch();
        $request = XCube_Root::getSingleton()->mContext->mRequest;
	
        if (($catId = $request->getRequest('category_id')) !== null) {
            $this->mNavi->addExtra('category_id', intval($catId));
        }
		//show all term or show only from start to end date
		$show = ($request->getRequest('show')=='all') ? false : true;
		$status = $request->getRequest('status');
		$status = $show ? 10 : (isset($status) ? intval($status) : Lenum_Status::PUBLISHED);
	
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
			$status,
			$show
		);
	
        if (($value = $request->getRequest('page_id')) !== null) {
            $this->mNavi->addExtra('page_id', $value);
            $this->_mCriteria->add(new Criteria('page_id', $value));
        }
    
        if (($value = $request->getRequest('title')) !== null) {
            $this->_setTextRequest('title', $value);
        }
    
        if (($value = $request->getRequest('p_id')) !== null) {
            $this->mNavi->addExtra('p_id', $value);
            $this->_mCriteria->add(new Criteria('p_id', $value));
        }
    
        if (($value = $request->getRequest('descendant')) !== null) {
            $this->mNavi->addExtra('descendant', $value);
            $this->_mCriteria->add(new Criteria('descendant', $value));
        }
    
        if (($value = $request->getRequest('uid')) !== null) {
            $this->mNavi->addExtra('uid', $value);
            $this->_mCriteria->add(new Criteria('uid', $value));
        }
    
        if (($value = $request->getRequest('maintable_id')) !== null) {
            $this->mNavi->addExtra('maintable_id', $value);
            $this->_mCriteria->add(new Criteria('maintable_id', $value));
        }
    
        if (($value = $request->getRequest('status')) !== null) {
            $this->mNavi->addExtra('status', $value);
            //$this->_mCriteria->add(new Criteria('status', $value));
        }
    
        if (($value = $request->getRequest('posttime')) !== null) {
            $this->mNavi->addExtra('posttime', $value);
            $this->_mCriteria->add(new Criteria('posttime', $value));
        }
    
        if (($value = $request->getRequest('updatetime')) !== null) {
            $this->mNavi->addExtra('updatetime', $value);
            $this->_mCriteria->add(new Criteria('updatetime', $value));
        }
    
        //Search by keyword: search all string and text field
        if (($value = $request->getRequest('keyword')) !== null) {
            $this->mNavi->addExtra('keywords', $value);
            Xcck_SearchUtils::makeSearchCriteria($this->_mCriteria, $dirname, $value);
        }
    
        foreach($this->mDefinitions as $definition){
            $value = $request->getRequest($definition->get('field_name'));
            if (isset($value) && $value!=="") {
                if($definition->get('field_type')==Xcck_FieldType::STRING || $definition->get('field_type')==Xcck_FieldType::TEXT){
                    $this->_setTextRequest($definition->get('field_name'), $value);
                }
                else{
                    $this->mNavi->addExtra($definition->get('field_name'), $value);
                    $this->_mCriteria->add(new Criteria($definition->get('field_name'), $value));
                }
            }
        }
	
        if (($value = $request->getRequest('show')) !== null) {
            $this->mNavi->addExtra('show', $value);
        }
	
        if (($value = $request->getRequest('child')) !== null) {
            $this->mNavi->addExtra('child', $value);
        }
    
		if(($tags = $request->getRequest('tag')) !==null){
			$this->_setTagRequest($tags, $dirname);
		}
	
        //$this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }

    /**
     * _setTagRequest
     * 
     * @param   string[]	$tags
     * @param   string		$dirname
     * 
     * @return  void
    **/
    protected function _setTagRequest(/*** string[] ***/ $tags, /*** string ***/ $dirname)
    {
        $this->mNavi->addExtra('tag', $tags);
		$ids = array();
		$tagArr = explode('+', $tags);
		$tDirname = XCube_Root::getSingleton()->mContext->mModuleConfig['tag_dirname'];
		XCube_DelegateUtils::call('Legacy_Tag.'.$tDirname.'.GetDataIdListByTags', new XCube_Ref($ids), $tDirname, $tagArr, $dirname, 'page');
		$this->_mCriteria->add(new Criteria('page_id', $ids, 'IN'));
	}
}

?>
