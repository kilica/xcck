<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

define('_AD_XCCK_DESC_XCCK', 'xCCK is Contents Creation Kit for XOOPS Cube Legacy. With this module, you can design fields you want freely. You can add fields of string, integer, textarea, select box, date, category and images.');

define('_MD_XCCK_LANG_CONFIG_CATEGORY_WIZARD', 'Category Setting');
define('_MD_XCCK_DESC_CONFIG_CATEGORY_WIZARD', 'xCCK can have category and permission management function through a LEGACY_CATEGORY("cat") or a LEGACY_GROUP("group") module. Select its type "cat", "group" or no category system.');
define('_MD_XCCK_LANG_CONFIG_CATEGORY2_WIZARD', 'Detailed Category Setting');
define('_MD_XCCK_DESC_CONFIG_CATEGORY2_WIZARD', 'Select a category module\'s dirname. If no select option exists, you must install category module first.');
define('_MD_XCCK_LANG_CONFIG_MAINTABLE_WIZARD', 'Parent xCCK Setting');
define('_MD_XCCK_DESC_CONFIG_MAINTABLE_WIZARD', 'If you will use this xCCK as a subtable module of other xCCK module, select the parent xCCK module\'s dirname.');
define('_MD_XCCK_LANG_CONFIG_IMAGES_WIZARD', 'Attached Image Setting');
define('_MD_XCCK_DESC_CONFIG_IMAGES_WIZARD', 'xCCK can hold attached images through a LEGACY_IMAGE module. You can write a image\'s name in form in each line.');
define('_MD_XCCK_LANG_CONFIG_PROGRESS_WIZARD', 'Publish Workflow Management Setting');
define('_MD_XCCK_DESC_CONFIG_PROGRESS_WIZARD', 'xCCK can use workflow management system through a LEGACY_WORKFLOW module.');

define('_AD_XCCK_LANG_ACCESS_CONTROLLER', 'dirname of the category management module');
define('_AD_XCCK_LANG_SHOW_ORDER', 'How to show list');
define('_AD_XCCK_LANG_AUTH_TYPE', 'Authentication Group Setting');
define('_AD_XCCK_LANG_USE_CATEGORY', 'Type of category management');
define('_AD_XCCK_LANG_IMAGES', 'Attached image name');
define('_AD_XCCK_LANG_PARENT', 'Maintable xCCK module\'s dirname');
define('_AD_XCCK_LANG_PUBLISH', 'Type of Workflow Management');
define('_AD_XCCK_LANG_THRESHOLD', 'Number of votes to publish');

define('_AD_XCCK_LANG_SETTING_OUTPUT', 'Export the settings');
define('_AD_XCCK_DESC_SETTING_OUTPUT', 'Export the fields and Preference settings of this module on another site. Copy &amp; paste the php code in the textarea, and make the file "{Xcck\'s dirname}Install.class.php" under (html)/modules/legacy/preload/. Then, you install the module !<br />For example, xcck\'s dirname is "Content", make the file "ContentInstall.class.php" by copy the textarea.');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT', 'Template');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_VIEW', 'View Template');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_VIEW', 'If you want to layout PageView fields on xcck_page_view.html template freely, you can copy and paste the following code. Instead, delete the code {foreach item=definition from=$definitions} ... {foreach}');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_EDIT', 'Edit Template');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_EDIT', 'If you want to layout PageEdit fields on xcck_page_edit.html template freely, you can copy and paste the following code. Instead, delete the code {foreach item=field from=$fields} ... {foreach}');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_LIST', 'List Template');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_List', 'If you want to layout PageList fields on xcck_page_list.html template freely, you can copy and paste the following code. Instead, delete the code {foreach item=def from=$definitions} ... {foreach}');
define('_AD_XCCK_LANG_ASC_ORDER_NUMBER', 'The Num. for ASC');
define('_AD_XCCK_LANG_DESC_ORDER_NUMBER', 'The Num. for DESC');
define('_AD_XCCK_LANG_ORDER_SHOW', 'The numbers for Default Order');
define('_AD_XCCK_DESC_ORDER_SHOW', 'You set the following number at Preference menu.');

/* Block */
define('_AD_XCCK_LANG_DISPLAY_NUMBER', 'Number of Display');
define('_AD_XCCK_LANG_SHOW_CAT', 'Category No');
define('_AD_XCCK_LANG_ORDER', 'Order');
define('_AD_XCCK_LANG_WEIGHT_ASC', 'Weight, Ascending');
define('_AD_XCCK_LANG_WEIGHT_DESC', 'Weight, Descending');
define('_AD_XCCK_LANG_POSTTIME_ASC', 'Posttime, Ascending');
define('_AD_XCCK_LANG_POSTTIME_DESC', 'Posttime, Descending');
?>
