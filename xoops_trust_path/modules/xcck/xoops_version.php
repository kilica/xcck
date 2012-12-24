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

if(!defined('XCCK_TRUST_PATH'))
{
    define('XCCK_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/xcck');
}

require_once XCCK_TRUST_PATH . '/class/XcckUtils.class.php';

//
// Define a basic manifesto.
//
$modversion['name'] = $myDirName;
$modversion['version'] = 0.89;
$modversion['description'] = _MI_XCCK_DESC_XCCK;
$modversion['author'] = _MI_XCCK_LANG_AUTHOR;
$modversion['credits'] = _MI_XCCK_LANG_CREDITS;
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/xcck.png';
$modversion['dirname'] = $myDirName;
$modversion['trust_dirname'] = 'xcck';

$modversion['cube_style'] = true;
$modversion['legacy_installer'] = array(
    'installer'   => array(
        'class'     => 'Installer',
        'namespace' => 'Xcck',
        'filepath'  => XCCK_TRUST_PATH . '/admin/class/installer/XcckInstaller.class.php'
    ),
    'uninstaller' => array(
        'class'     => 'Uninstaller',
        'namespace' => 'Xcck',
        'filepath'  => XCCK_TRUST_PATH . '/admin/class/installer/XcckUninstaller.class.php'
    ),
    'updater' => array(
        'class'     => 'Updater',
        'namespace' => 'Xcck',
        'filepath'  => XCCK_TRUST_PATH . '/admin/class/installer/XcckUpdater.class.php'
    )
);
$modversion['disable_legacy_2nd_installer'] = false;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = array(
    '{prefix}_{dirname}_definition',
    '{prefix}_{dirname}_page',
    '{prefix}_{dirname}_revision'
##[cubson:tables]
##[/cubson:tables]
);

//
// Templates. You must never change [cubson] chunk to get the help of cubson.
//
$modversion['templates'] = array(
/*
    array(
        'file'        => '{dirname}_xxx.html',
        'description' => _MI_XCCK_TPL_XXX
    ),
*/
##[cubson:templates]
    array('file' => '{dirname}_category_list.html','description' => _MI_XCCK_TPL_PAGE_LIST),
    array('file' => '{dirname}_page_list.html','description' => _MI_XCCK_TPL_PAGE_LIST),
    array('file' => '{dirname}_page_edit.html','description' => _MI_XCCK_TPL_PAGE_EDIT),
    array('file' => '{dirname}_page_delete.html','description' => _MI_XCCK_TPL_PAGE_DELETE),
    array('file' => '{dirname}_page_inc.html','description' => _MI_XCCK_TPL_PAGE_INC),
    array('file' => '{dirname}_page_inc_activity.html','description' => 'for Activity List'),
    array('file' => '{dirname}_page_search.html','description' => 'Search condition and result'),
    array('file' => '{dirname}_page_album.html','description' => 'image album type list'),
    array('file' => '{dirname}_page_view.html','description' => _MI_XCCK_TPL_PAGE_VIEW),
    array('file' => '{dirname}_definition_list.html','description' => _MI_XCCK_TPL_DEFINITION_LIST),
    array('file' => '{dirname}_definition_edit.html','description' => _MI_XCCK_TPL_DEFINITION_EDIT),
    array('file' => '{dirname}_definition_delete.html','description' => _MI_XCCK_TPL_DEFINITION_DELETE),
    array('file' => '{dirname}_definition_view.html','description' => _MI_XCCK_TPL_DEFINITION_VIEW),
    array('file' => '{dirname}_subtable_view.html','description' => _MI_XCCK_TPL_SUBTABLE_VIEW),
    array('file' => '{dirname}_inc_menu.html','description' => 'menu'),
##[/cubson:templates]
);

//
// Admin panel setting
//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Index';
$modversion['adminmenu'] = array(
  array(
        'title'    => _MI_XCCK_LANG_SETTING_OUTPUT,
        'link'     => 'admin/index.php?action=SettingOutput',
        'keywords' => _MI_XCCK_KEYWORD_SETTING_OUTPUT,
        'show'     => true,
        'absolute' => false
    ),
  array(
        'title'    => _MI_XCCK_LANG_TEMPLATE_OUTPUT,
        'link'     => 'admin/index.php?action=TemplateOutput',
        'keywords' => _MI_XCCK_KEYWORD_TEMPLATE_OUTPUT,
        'show'     => true,
        'absolute' => false
    ),
  array(
        'title'    => _MI_XCCK_LANG_ORDER_SHOW,
        'link'     => 'admin/index.php?action=OrderShow',
        'keywords' => 'order number',
        'show'     => true,
        'absolute' => false
    ),

##[cubson:adminmenu]

##[/cubson:adminmenu]
);

//
// Public side control setting
//
$modversion['hasMain'] = 1;

// Search
//$modversion['hasSearch'] = 1;
$modversion['hasSearch'] = 0;
$modversion['search']['file'] = 'search.php';
$modversion['search']['func'] = 'xcck_search';


$modversion['sub'] = array(
/*
    array(
        'name' => _MI_XCCK_LANG_SUB_ADD_A_PAGE,
        'url'  => 'index.php?action=PageEdit'
    ),
    array(
        'name' => _MI_XCCK_LANG_SUB_DEFINITION_LIST,
        'url'  => 'index.php?action=DefinitionList'
    ),
    array(
        'name' => _MI_XCCK_LANG_SUB_ADD_A_DEFINITION,
        'url'  => 'index.php?action=DefinitionEdit'
    ),
*/
##[cubson:submenu]
##[/cubson:submenu]
);

//
// Config setting
//
$modversion['config'] = array(
    array(
        'name'          => 'access_controller',
        'title'         => '_MI_XCCK_LANG_ACCESS_CONTROLLER',
        'description'   => '_MI_XCCK_DESC_ACCESS_CONTROLLER',
        'formtype'      => 'server_module',
        'valuetype'     => 'string',
        'default'       => '',
        'options'       => array("none", "cat", "group")
    ),
    array(
        'name'          => 'tag_dirname',
        'title'         => '_MI_XCCK_LANG_TAG_DIRANME',
        'description'   => '_MI_XCCK_DESC_TAG_DIRNAME',
        'formtype'      => 'server_module',
        'valuetype'     => 'string',
        'default'       => '',
        'options'       => array("none", "tag")
    ),
    array(
        'name'          => 'comment_dirname',
        'title'         => '_MI_XCCK_LANG_COMMENT_DIRNAME',
        'description'   => '_MI_XCCK_DESC_COMMENT_DIRNAME',
        'formtype'      => 'server_module',
        'valuetype'     => 'string',
        'default'       => '',
        'options'       => array("none", "comment")
    ),
    array(
        'name'          => 'hierarchical',
        'title'         => '_MI_XCCK_LANG_HIERARCHICAL',
        'description'   => '_MI_XCCK_DESC_HIERARCHICAL',
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
    ),
    array(
        'name'          => 'list_order',
        'title'         => '_MI_XCCK_LANG_LIST_ORDER',
        'description'   => '_MI_XCCK_DESC_LIST_ORDER',
        'formtype'      => 'select',
        'valuetype'     => 'string',
        'default'       => 'flat',
        'options'       => array("_MI_XCCK_LANG_ORDER_FLAT"=>"flat", "_MI_XCCK_LANG_ORDER_CATEGORIZED"=>"categorized", "_MI_XCCK_LANG_ORDER_ALBUM"=>"album")
    ),
    array(
        'name'          => 'default_order',
        'title'         => '_MI_XCCK_LANG_DEFAULT_ORDER',
        'description'   => '_MI_XCCK_DESC_DEFAULT_ORDER',
        'formtype'      => 'textbox',
        'valuetype'     => 'int',
        'default'       => 8,
        'options'       => array()
    ),
    array(
        'name'          => 'auth_type' ,
        'title'         => "_MI_XCCK_LANG_AUTH_TYPE" ,
        'description'   => "_MI_XCCK_DESC_AUTH_TYPE" ,
        'formtype'      => 'textbox' ,
        'valuetype'     => 'string' ,
        'default'       => 'viewer|poster|reviewer|manager' ,
        'options'       => array()
    ) ,
    array(
        'name'          => 'singlepost',
        'title'         => '_MI_XCCK_LANG_SINGLEPOST',
        'description'   => '_MI_XCCK_DESC_SINGLEPOST',
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
    ),
    array(
        'name'          => 'default_action' ,
        'title'         => "_MI_XCCK_LANG_DEFAULT_ACTION" ,
        'description'   => "_MI_XCCK_DESC_DEFAULT_ACTION" ,
        'formtype'      => 'select' ,
        'valuetype'     => 'string' ,
        'default'       => 'List' ,
        'options'       => array("List"=>"List", "Search"=>"Search", "View"=>"View", "Edit"=>"Edit")
    ) ,
    array(
        'name'          => 'default_query' ,
        'title'         => "_MI_XCCK_LANG_DEFAULT_QUERY" ,
        'description'   => "_MI_XCCK_DESC_DEFAULT_QUERY" ,
        'formtype'      => 'textbox' ,
        'valuetype'     => 'text' ,
        'default'       => '' ,
        'options'       => array()
    ) ,
    array(
        'name'          => 'forward_action' ,
        'title'         => "_MI_XCCK_LANG_FORWARD_ACTION" ,
        'description'   => "_MI_XCCK_DESC_FORWARD_ACTION" ,
        'formtype'      => 'select' ,
        'valuetype'     => 'string' ,
        'default'       => 'View' ,
        'options'       => array("view"=>"view", "list"=>"list", "search"=>"search")
    ) ,
    array(
        'name'          => 'publish',
        'title'         => '_MI_XCCK_LANG_PUBLISH',
        'description'   => '_MI_XCCK_DESC_PUBLISH',
        'formtype'      => 'select',
        'valuetype'     => 'string',
        'default'       => 'none',
        'options'       => array("none"=>"none", "linear"=>"linear")
    ),
    array(
        'name'          => 'threshold',
        'title'         => '_MI_XCCK_LANG_THRESHOLD',
        'description'   => '_MI_XCCK_DESC_THRESHOLD',
        'formtype'      => 'textbox',
        'valuetype'     => 'string',
        'default'       => 1,
        'options'       => array()
    ),
    array(
        'name'          => 'maintable',
        'title'         => '_MI_XCCK_LANG_MAIN_TABLE',
        'description'   => '_MI_XCCK_DESC_MAIN_TABLE',
        'formtype'      => 'textbox',
        'valuetype'     => 'string',
        'default'       => "",
    ),
    array(
        'name'          => 'subtable_auth' ,
        'title'         => "_MI_XCCK_LANG_SUBTABLE_AUTH" ,
        'description'   => "_MI_XCCK_DESC_SUBTABLE_AUTH" ,
        'formtype'      => 'select',
        'valuetype'     => 'string',
        'default'       => 'none',
        'options'       => array("post"=>"post", "view"=>"view", "review"=>"review", "manage"=>"manage")
    ) ,
	array(
		'name'          => 'setup_field',
		'title'         => '_MI_XCCK_LANG_SETUP_FIELD',
		'description'   => '_MI_XCCK_DESC_SETUP_FIELD',
		'formtype'      => 'textarea',
		'valuetype'     => 'text',
		'default'       => '',
	),
	array(
        'name'          => 'images',
        'title'         => '_MI_XCCK_LANG_IMAGES',
        'description'   => '_MI_XCCK_DESC_IMAGES',
        'formtype'      => 'textarea',
        'valuetype'     => 'text',
        'default'       => '',
    ),
    array(
        'name'          => 'use_map',
        'title'         => '_MI_XCCK_LANG_USE_MAP',
        'description'   => '_MI_XCCK_DESC_USE_MAP',
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
    ),
    array(
        'name'          => 'css_file',
        'title'         => '_MI_XCCK_LANG_CSS_FILE',
        'description'   => '_MI_XCCK_DESC_CSS_FILE',
        'formtype'      => 'textbox' ,
        'valuetype'     => 'text' ,
        'default'       => '/modules/'.$myDirName.'/style.css',
    ),
    array(
        'name'          => 'description',
        'title'         => '_MI_XCCK_LANG_DESCRIPTION',
        'description'   => '_MI_XCCK_DESC_DESCRIPTION',
        'formtype'      => 'textarea',
        'valuetype'     => 'text',
        'default'       => '',
    ),
/*
    array(
        'name'          => 'xxxx',
        'title'         => '_MI_XCCK_TITLE_XXXX',
        'description'   => '_MI_XCCK_DESC_XXXX',
        'formtype'      => 'xxxx',
        'valuetype'     => 'xxx',
        'options'       => array(xxx => xxx,xxx => xxx),
        'default'       => 0
    ),
*/
##[cubson:config]
##[/cubson:config]
);

//
// Block setting
//
require_once XCCK_TRUST_PATH.'/class/Enum.class.php';
$modversion['blocks'] = array(
    1 => array(
        'func_num'          => 1,
        'file'              => 'ListBlock.class.php',
        'class'             => 'ListBlock',
        'name'              => _MI_XCCK_BLOCK_NAME_LIST,
        'description'       => _MI_XCCK_BLOCK_DESC_LIST,
        'options'           => '5||'.Xcck_Order::WEIGHT_ASC.'|false',
        'template'          => '{dirname}_block_list.html',
        'show_all_module'   => true,
        'can_clone'         => true,
        'visible_any'       => false
    ),
    2 => array(
        'func_num'          => 2,
        'file'              => 'CategoryBlock.class.php',
        'class'             => 'CategoryBlock',
        'name'              => _MI_XCCK_BLOCK_NAME_CATEGORY,
        'description'       => _MI_XCCK_BLOCK_DESC_CATEGORY,
        'options'           => '',
        'template'          => '{dirname}_block_category.html',
        'show_all_module'   => true,
        'can_clone'         => true,
        'visible_any'       => false
    ),
/*
    x => array(
        'func_num'          => x,
        'file'              => 'xxxBlock.class.php',
        'class'             => 'xxx',
        'name'              => _MI_XCCK_BLOCK_NAME_xxx,
        'description'       => _MI_XCCK_BLOCK_DESC_xxx,
        'options'           => '',
        'template'          => '{dirname}_block_xxx.html',
        'show_all_module'   => true,
        'visible_any'       => true
    ),
*/
##[cubson:block]
##[/cubson:block]
);

?>
