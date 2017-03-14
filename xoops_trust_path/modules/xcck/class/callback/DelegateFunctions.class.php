<?php
/**
 * @package xcck
 * @version $Id: DelegateFunctions.class.php,v 1.1 2007/05/15 02:35:07 minahito Exp $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * xcck delegates
**/
class Xcck_DelegateFunctions
{
    /**
     * save page data.
     * Its key is field name and the value is value of page.
     *
     * @param	bool      &$ret
     * @param	Xcck_PageEditForm  $actionForm
     * @param	string, $dirname
     *
     * @return  void
     */ 
    public static function savePage(/*** bool ***/ &$ret, Xcck_PageEditForm $actionForm, /*** string ***/ $dirname)
    {
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        if(! $obj = $handler->get($actionForm->get('page_id'))){
            $obj = $handler->create();
            $obj->set('page_id', $actionForm->get('page_id'));
        }
        $defHandler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $defObjs = $defHandler->getFields();
        foreach($defObjs as $def){
        	$obj->setField($def->get('field_name'), $actionForm->get($def->get('field_name')));
        }
        $ret = $handler->insert($obj, true);
    }

    /**
     * delete page data.
     * Its key is field name and the value is value of page.
     *
     * @param	bool      &$ret
     * @param	Xcck_PageObject  $obj
     * @param	string, $dirname
     *
     * @return  void
     */ 
    public static function deletePage(/*** bool ***/ &$ret, Xcck_PageObject $obj, /*** string ***/ $dirname)
    {
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        $ret = $handler->delete($obj, true);
    }

    /**
     * get page data
     *
     * @param Xcck_PageObject &$page
     * @param string	$dirname
     * @param int       $id    page id
     *
     * @return  void
     */ 
    public static function getPageList(/*** Xcck_PageObject[] ***/ &$pageObjs, /*** string ***/ $dirname, /*** CriteriaCompo ***/ $cri=null)
    {
    	if(! $cri instanceof CriteriaCompo){
    		$cri = Xcck_Utils::getListCriteria($dirname);
    	}
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        $pageObjs = $handler->getObjects($cri);
    }

    /**
     * get page data by id
     *
     * @param Xcck_PageObject &$page
     * @param string	$dirname
     * @param int       $id    page id
     *
     * @return  void
     */ 
    public static function getPage(Xcck_PageObject &$page, /*** string ***/ $dirname, /*** int ***/ $id)
    {
        $handler = Legacy_Utils::getModuleHandler('page', dirname);
        $page = $handler->get($id);
        if(! $page){
            $page = $handler->create();
        }
    }

    /**
     * save definition data.
     * Its key is field name and the value is value of page.
     *
     * @param	bool      &$ret
     * @param	Xcck_DefinitionEditForm  $actionForm
     * @param	string, $dirname
     *
     * @return  void
     */ 
    public static function saveDefinition(/*** bool ***/ &$ret, Xcck_DefinitionEditForm $actionForm, /*** string ***/ $dirname)
    {
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
        if(! $obj = $handler->get($actionForm->get('definition_id'))){
            $obj = $handler->create();
            $obj->set('definition_id', $actionForm->get('definition_id'));
        }
        $actionForm->update($obj);
        $ret = $handler->insert($obj, true);
    }

    /**
     * delete definition data.
     * Its key is field name and the value is value of page.
     *
     * @param	bool      &$ret
     * @param	Xcck_DefinitionObject  $obj
     * @param	string, $dirname
     *
     * @return  void
     */ 
    public static function deleteDefinition(/*** bool ***/ &$ret, Xcck_DefinitionObject $obj, /*** string ***/ $dirname)
    {
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $ret = $handler->delete($obj, true);
    }

    /**
     * get page data by id
     *
     * @param Xcck_DefinitionObject &$definition
     * @param string	$dirname
     * @param int       $id    definition id
     *
     * @return  void
     */ 
    public static function getDefinition(Xcck_DefinitionObject &$definition, /*** string ***/ $dirname, /*** int ***/ $id=0)
    {
        $handler = Legacy_Utils::getModuleHandler('definition', dirname);
        $definition = $handler->get($id);
        if(! $definition){
            $definition = $handler->create();
        }
    }

    /**
     * getDefinitionList
     *
     * @param mixed     &$defArr
     * @param string	$dirname
     *
     * @return  void
     */ 
    public static function getDefinitionList(/*** mixed ***/ &$defArr, /*** string ***/ $dirname)
    {
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
        $defArr = $handler->getFields();
    }

    /**
     * setup Page ActionForm. Add FormProperties and FieldProperties
     *
     * @param Xcck_PageEditForm  &$actionForm
     * @param string	$dirname
     * @param string	$dataname	Page/Definition
     *
     * @return  void
     */ 
    public static function getActionForm(/*** XCube_ActionForm ***/ &$actionForm, /*** string ***/ $dirname, /*** string ***/ $dataname)
    {
		require_once XCCK_TRUST_PATH . '/class/AssetManager.class.php';
		$asset = Xcck_AssetManager::getInstance($dirname);
    	$actionForm = $asset->getObject('form', ucfirst($dataname), false, 'edit');
    	$actionForm->prepare($dirname);
    }

    /**
     * getNormalUri
     *
     * @param string    $uri
     * @param string    $dirname
     * @param string    $dataname
     * @param int       $data_id
     * @param string    $action
     * @param string    $query
     *
     * @return  void
     */ 
    public static function getNormalUri(/*** string ***/ &$uri, /*** string ***/ $dirname, /*** string ***/ $dataname=null, /*** int ***/ $data_id=0, /*** string ***/ $action=null, /*** string ***/ $query=null)
    {
        $sUri = '/%s/index.php?action=%s%s';
        $lUri = '/%s/index.php?action=%s%s&%s=%d';
        switch($dataname){
            case 'subtable':    $key = 'page_id';break;
            default:        $key = $dataname.'_id';break;
        }
    
        $table = isset($dataname) ? $dataname : 'page';
    
        if(isset($dataname)){
            if($data_id>0){
                if(isset($action)){
                    $uri = sprintf($lUri, $dirname, ucfirst($dataname), ucfirst($action), $key, $data_id);
                }
                else{
                    $uri = sprintf($lUri, $dirname, ucfirst($dataname), 'View', $key, $data_id);
                }
            }
            else{
                if(isset($action)){
                    $uri = sprintf($sUri, $dirname, ucfirst($dataname), ucfirst($action));
                }
                else{
                    $uri = sprintf($sUri, $dirname, ucfirst($dataname), 'List');
                }
            }
            $uri = isset($query) ? $uri.'&'.$query : $uri;
        }
        else{
            if($data_id>0){
                if(isset($action)){
                    die('invalid uri');
                }
                else{
                    $handler = Legacy_Utils::getModuleHandler($table, $dirname);
                    $key = $handler->mPrimary;
                    $uri = sprintf($lUri, $dirname, ucfirst($table), 'View', $key, $data_id);
                }
                $uri = isset($query) ? $uri.'&'.$query : $uri;
            }
            else{
                if(isset($action)){
                    die('invalid uri');
                }
                else{
                    $uri = sprintf('/%s/', $dirname);
                    $uri = isset($query) ? $uri.'index.php?'.$query : $uri;
                }
            }
        }
    }

	public static function getBreadcrumbs(/*** mixed[] ***/ $breadcrumbs, /***string ***/ $dirname, /*** Xcck_PageObject ***/ $page=null)
	{
		$categoryDirname = null;
	
		//module name set
		$modHandler = xoops_gethandler('module');
		$module = $modHandler->getByDirname($dirname);
		$breadcrumbs[] = array('name'=>$module->getVar('name'), 'url'=>Legacy_Utils::renderUri($dirname));
	
		//catetgory name set
		if($page instanceof Xcck_PageObject){
		    if($page->get('category_id')>0){
				$accessController = Xcck_Utils::getAccessController($dirname);
				if($accessController instanceof XoopsModule){
					$categoryDirname = $accessController->getVar('dirname');
				}
				switch($accessController->getInfo('role')){
				case 'cat':
					$catArr = array();
					XCube_DelegateUtils::call('Legacy_Category.'.$categoryDirname.'.GetCatPath', new XCube_Ref($catArr), $categoryDirname, $page->get('category_id'), 'ASC');
					foreach(array_keys($catArr['title']) as $key){
						$breadcrumbs[] = array('name'=>$catArr['title'][$key], 'url'=>Legacy_Utils::renderUri($dirname, 'page', 0, null, 'category_id='.$catArr['cat_id'][$key]));
					}
					break;
				case 'group':
					$groupName = null;
					XCube_DelegateUtils::call('Legacy_Category.'.$categoryDirname.'.GetTitle', new XCube_Ref($groupName), $categoryDirname, $page->get('category_id'));
					$breadcrumbs[] = array('name'=>$groupName, 'url'=>Legacy_Utils::renderUri($dirname, 'page', 0, null, 'category_id='.$page->get('category_id')));
					break;
				default:
				}
			}
			//parent page set
			$pageIdArr = array_reverse($page->mPath['page_id']);
			$titleArr = array_reverse($page->mPath['title']);
			foreach(array_keys($pageIdArr) as $key){
				$breadcrumbs[] = array('name'=>$titleArr[$key], 'url'=>Legacy_Utils::renderUri($dirname, null, $pageIdArr[$key]));
			}
		}
	}

	public static function deleteUserData($user)
	{
        //don't call this method multiple times when site owner duplicate.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
    
    	$uid = $user->get('uid');
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
		foreach($dirnames as $dirname){
			$handler = Legacy_Utils::getModuleHandler('page', $dirname);
			$handler->deleteAll(new Criteria('uid', $uid));
		}
	
        $isCalled = true;
	}
}
?>
