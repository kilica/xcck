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

/**
 * Xcck_Utils
**/
class Xcck_Utils
{
    /**
     * getMyId
     * 
     * @param   string  $dirname
     * @param   int     $maintableId
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getMyId(/*** string ***/ $dirname)
    {
        $uid = Legacy_Utils::getUid();
        if($uid==0){
            return;
        }
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        $objs = $handler->getObjects(new Criteria('uid', $uid));
        if(count($objs)===1){
            $id = array_shift($objs)->get('page_id');
        }
        return $id;
    }

    /**
     * getXoopsHandler
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  object
    **/
    public static function &getXoopsHandler(/*** string ***/ $name,/*** bool ***/ $optional = false)
    {
        return xoops_gethandler($name,$optional);
    }

    /**
     * getModuleConfig
     * 
     * @param   string  $dirname
     * @param   string  $key
     * 
     * @return  mixed
    **/
    public static function getModuleConfig(/*** string ***/ $dirname, /*** string ***/ $key)
    {
        static $config = array();
        if(! isset($config[$dirname])){
            $chandler = xoops_gethandler('config');
            $config[$dirname] = $chandler->getConfigsByDirname($dirname);
        }
        return isset($config[$dirname][$key]) ? $config[$dirname][$key] : null;
    }

    public static function getPermittedIdList(/*** string ***/ $dirname, /*** string ***/ $action=null, /*** int ***/ $categoryId=0)
    {
        $action = isset($action) ? $action : Xcck_AuthType::VIEW;
    
        $accessController = self::getAccessController($dirname);
    
        if(! is_object($accessController)) return;
    
        $role = $accessController->get('role');
        $idList = array();
        if($role=='cat'){
            $delegateName = 'Legacy_Category.'.$accessController->dirname().'.GetPermittedIdList';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), self::getActor($dirname, $action), Legacy_Utils::getUid(), $categoryId);
        }
        elseif($role=='group'){
            $delegateName = 'Legacy_Group.'.$accessController->dirname().'.GetGroupIdListByAction';
            XCube_DelegateUtils::call($delegateName, new XCube_Ref($idList), $accessController->dirname(), $dirname, 'page', $action);
        }
        else{   //has user group permission ?
            ///TODO
        }
        return $idList;
    }

    public static function getAccessController(/*** string ***/ $dirname)
    {
        $chandler = xoops_gethandler('config');
        $conf = $chandler->getConfigsByDirname($dirname);
        if(trim($conf['maintable'])){
            $conf = $chandler->getConfigsByDirname(trim($conf['maintable']));
        }
        $handler = xoops_gethandler('module');
           return $handler->getByDirname($conf['access_controller']);
    }

    /**
     * getActor
     * 
     * @param   string  $dirname
     * @param   string  $action
     * 
     * @return  string
    **/
    public static function getActor(/*** string ***/ $dirname, /*** string ***/ $action)
    {
        $authSetting = self::getModuleConfig($dirname, 'auth_type');
        $authType = isset($authSetting) ? explode('|', $authSetting) : array('viewer', 'poster', 'manager', 'manager');
        switch($action){
            case Xcck_AuthType::VIEW:
                return trim($authType[0]);
                break;
            case Xcck_AuthType::POST:
                return trim($authType[1]);
                break;
            case Xcck_AuthType::REVIEW:
                return trim($authType[2]);
                break;
            case Xcck_AuthType::MANAGE:
                return trim($authType[3]);
                break;
        }
    }

    /**
     * getListCriteria
     * 
     * @param   string  $dirname
     * @param   int     $categoryId
     * @param   bool    $childCategory Get child categories of $categodyId ?
     * @param   int[]    $sortArr
     * @param   Lenum_Status    $status
     * @param    bool    $term    if between startdate and enddate
     * 
     * @return  CriteriaCompo
    **/
    public static function getListCriteria(/*** string ***/ $dirname, /*** int ***/ $categoryId=0, /*** bool ***/ $childCategory=false, /*** int[] ***/ $sortArr=null, /*** Enum ***/ $status=Lenum_Status::PUBLISHED, $term=true)
    {
        $accessController = self::getAccessController($dirname);
    
        $cri = new CriteriaCompo();
    
        $idList = self::getPermittedIdList($dirname);
        //category
        if($categoryId>0){
            //get child category of $categoryId ?
            if($childCategory===true){
                $ids = array($categoryId);
                $tree = array();    // Legacy_Category Objects array
                $categoryDir = $accessController->dirname();
                XCube_DelegateUtils::call('Legacy_Category.'.$categoryDir.'.GetTree', new XCube_Ref($tree), $categoryDir, 'viewer', $categoryId);
                foreach($tree as $cat){
                    $ids[] = $cat->get('cat_id');
                }
                $cri->add(new Criteria('category_id', $ids, 'IN'));
            }
            else{
                $cri->add(new Criteria('category_id', $categoryId));
            }
        }
        else{
            //get permitted categories to show
            if($accessController instanceof XoopsModule && ($accessController->get('role')=='cat' || $accessController->get('role')=='group')){
                if(count($idList)>0){
                    $cri->add(new Criteria('category_id', $idList, 'IN'));
                }
                else{
                    //no date will be return by this criteria.
                    $cri->add(new Criteria('category_id', array(0), 'IN'));
                }
            }
        }
    
        //Is hierarchical ?
        if(self::getModuleConfig($dirname, 'hierarchical')==true && self::getModuleConfig($dirname, 'list_order')!='categorized'){
            $cri->add(new Criteria('p_id', 0));
        }
    
        //status
        if($status<=Lenum_Status::PUBLISHED){
            $cri->add(new Criteria('status', $status));
        }
        //startdate and enddate
        if($term===true){
            $defHandler = Legacy_Utils::getModuleHandler('definition', $dirname);
            if($startField = $defHandler->getStartField()){
                $cri->add(new Criteria($startField->get('field_name'), time(), '<'));
            }
            if($endField = $defHandler->getEndField()){
                $endCri = new CriteriaCompo();
                $endCri->add(new Criteria($endField->get('field_name'), time(), '>'));
                $endCri->add(new Criteria($endField->get('field_name'), 0), 'OR');
                $cri->add($endCri);
            }
        }
    
        //set sort/order
        $list = self::getOrderList($dirname);
        if(is_array($sortArr)){
            foreach($sortArr as $sort){
                if(abs($sort)>0 && in_array(abs($sort), array_keys($list))){
                       $cri->addSort($list[abs($sort)], $sort>0 ? 'ASC' : 'DESC');
                   }
               }
           }
        else{
            $default = self::getModuleConfig($dirname, 'default_order');
            if(in_array(abs($default), array_keys($list))){
                $cri->setSort($list[abs($default)], $default>0 ? 'ASC' : 'DESC');
            }
            else{
                $cri->setSort('weight', 'ASC');
            }
        }

        XCube_DelegateUtils::call(
            'Module.'.$dirname.'.SetupListCriteria',
            $cri
        );

        return $cri;
    }

    public static function getOrderList(/*** string ***/ $dirname)
    {
        $fieldArr = array(
            1 => 'page_id',
            2 => 'title',
            3 => 'category_id',
            4 => 'maintable_id',
            5 => 'p_id',
            6 => 'descendant',
            7 => 'uid',
            8 => 'weight',
            9 => 'status',
            10 => 'posttime',
            11 => 'updatetime'
        );
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $defs = $handler->getFields();
        foreach($defs as $def){
            $fieldArr[$def->get('definition_id')+2] = $def->getShow('field_name');
        }
    
        return $fieldArr;
    }

    public static function getImageNameList(/*** string ***/ $dirname)
    {
        $list = array();
        $images = trim(self::getModuleConfig($dirname, 'images')) ? preg_split('/\x0d\x0a|\x0d|\x0a/', self::getModuleConfig($dirname, 'images'), null) : array();
        $i = 1;
        foreach($images as $image){
            $imageArr = explode('|', trim($image));
            if(count($imageArr)>1){
                $list[$i] = trim($imageArr[1]);
            }
            elseif(count($imageArr)==1){
                $list[$i] = trim($imageArr[0]);
            }
            $i++;
        }
        return $list;
    }

    /**
     * make selectbox options tag about search condition
     * 
     * @param   Xcck_DefinitionObject   $def
     * @param   int     $num
     * @param   Enum[]   $conditions    list of Xcck_Cond
     * @param   Enum     $selected    selected condition
     * 
     * @return  string
    **/
    public static function makeCondSelector(Xcck_DefinitionObject $def, /*** int ***/ $num, /*** Enum[] ***/ $conditions, /*** Enum ***/ $selected)
    {
        $optionTag = '<option value="%s"%s>%s</option>'."\n";
        $html = sprintf('<select name="%s[%d][1]">', $def->getShow('field_name'), $num);
        foreach($conditions as $cond){
            $selectedTag = ($cond===$selected) ? 'selected="selected"' : null;
            $html .= sprintf($optionTag, $cond, $selectedTag, Xcck_Cond::getString($cond));
        }
        $html .= '</select>';
        return $html;
    }

    public static function getDefaultStatus(/*** string ***/ $dirname)
    {
        $publish = self::getModuleConfig($dirname, 'publish');
        if($publish==='linear'){
            return Lenum_Status::PROGRESS;
        }
        else{
            return Lenum_Status::PUBLISHED;
        }
    }

    public static function setupPageByRevision(Xcck_RevisionObject $revision)
    {
        $handler = Legacy_Utils::getModuleHandler('page', $revision->getDirname());
        $page = $handler->get($revision->get('page_id'));
        if(! $page instanceof Xcck_PageObject){
            $page = $handler->create();
        }
        $page->mLatestRevision = $revision;
        $page->loadDefinition();

        $page->set('page_id', $revision->get('page_id'));
        $page->set('title', $revision->get('title'));
        $page->set('category_id', $revision->get('category_id'));
        $page->set('maintable_id', $revision->get('maintable_id'));
        $page->set('p_id', $revision->get('p_id'));
        $page->set('descendant', $revision->get('descendant'));
        $page->set('uid', $revision->get('uid'));
        $page->set('status', $revision->get('status'));
        $page->set('weight', $revision->get('weight'));
        $page->set('updatetime', $revision->get('updatetime'));

        $page->mImage = $revision->mImage;
        $page->mTag = $revision->mTag;
        $page->mLatlng = $revision->mLatlng;

        foreach($page->mDef as $def){
            $page->set($def->getShow('field_name'), $revision->get($def->getShow('field_name')));
        }

        return $page;
    }

    public static function setupRevisionByPage(Xcck_PageObject $page)
    {
        $handler = Legacy_Utils::getModuleHandler('revision', $page->getDirname());
        $revision = $handler->get($page->get('page_id'));
        if(! $revision instanceof Xcck_RevisionObject){
            $revision = $handler->create();
        }
        else{
            $revision->setNew();
            $revision->set('revision_id', 0);
        }

        $revision->set('page_id', $page->get('page_id'));
        $revision->set('title', $page->get('title'));
        $revision->set('category_id', $page->get('category_id'));
        $revision->set('maintable_id', $page->get('maintable_id'));
        $revision->set('p_id', $page->get('p_id'));
        $revision->set('descendant', $page->get('descendant'));
        $revision->set('uid', $page->get('uid'));
        $revision->set('status', $page->get('status'));
        $revision->set('weight', $page->get('weight'));
        $revision->set('updatetime', $page->get('updatetime'));

        $revision->mImage = $page->mImage;
        $revision->mTag = $page->mTag;
        $revision->mLatlng = $page->mLatlng;
    
        $page->loadDefinition();
        foreach($page->mDef as $def){
            $revision->set($def->getShow('field_name'), $page->get($def->getShow('field_name')));
        }

        return $revision;
    }

}
?>
