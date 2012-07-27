<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

define('_MD_XCCK_ERROR_REQUIRED', '{0} is required.');
define('_MD_XCCK_ERROR_MINLENGTH', 'Input {0} with {1} or more characters.');
define('_MD_XCCK_ERROR_MAXLENGTH', 'Input {0} with {1} or less characters.');
define('_MD_XCCK_ERROR_EXTENSION', 'Uploaded file\'s extension does not match any entry in the allowed list.');
define('_MD_XCCK_ERROR_INTRANGE', 'Incorrect input on {0}.');
define('_MD_XCCK_ERROR_MIN', 'Input {0} with {1} or more numeric value.');
define('_MD_XCCK_ERROR_MAX', 'Input {0} with {1} or less numeric value.');
define('_MD_XCCK_ERROR_OBJECTEXIST', 'Incorrect input on {0}.');
define('_MD_XCCK_ERROR_DBUPDATE_FAILED', 'Failed updating database.');
define('_MD_XCCK_ERROR_EMAIL', '{0} is an incorrect email address.');
define('_MD_XCCK_ERROR_INVALID_MAINTABLE', 'Can\'t get Main Table.');
define('_MD_XCCK_LANG_FIELD_NAME_RESERVED', 'This Field name is reserved by XCCK. Change to the other field name.');
define('_MD_XCCK_LANG_FIELD_NAME_DUPLICATED', 'This field_name is already used.');

define('_MD_XCCK_MESSAGE_CONFIRM_DELETE', 'Are you sure you want to delete your selection?');

/*** menu ***/
define('_MD_XCCK_LANG_SHOW_ALL', 'Show All Page');
define('_MD_XCCK_LANG_ADD_A_NEW_DEFINITION', 'Add a new definition');
define('_MD_XCCK_LANG_DEFINITION_EDIT', 'Edit Definition');
define('_MD_XCCK_LANG_DEFINITION_DELETE', 'Delete Definition');
define('_MD_XCCK_LANG_DEFINITION_LIST', 'Definition List');
define('_MD_XCCK_LANG_ADD_A_NEW_PAGE', 'Add a new Page');
define('_MD_XCCK_LANG_PAGE_EDIT', 'Edit Page');
define('_MD_XCCK_LANG_PAGE_DELETE', 'Delete Page');

/*** main ***/
define('_MD_XCCK_LANG_DEFINITION', 'DEFINITION');
define('_MD_XCCK_LANG_DEFINITION_ID', 'DEFINITION ID');
define('_MD_XCCK_LANG_FIELD_NAME', 'FIELD NAME');
define('_MD_XCCK_LANG_LABEL', 'LABEL');
define('_MD_XCCK_LANG_FIELD_TYPE', 'FIELD TYPE');
define('_MD_XCCK_LANG_VALIDATION', 'VALIDATION');
define('_MD_XCCK_LANG_REQUIRED', 'REQUIRED');
define('_MD_XCCK_LANG_DESCRIPTION', 'DESCRIPTION');
define('_MD_XCCK_LANG_OPTIONS', 'OPTIONS');
define('_MD_XCCK_LANG_CONTROL', 'CONTROL');
define('_MD_XCCK_ERROR_CONTENT_IS_NOT_FOUND', 'Content item is not found');
define('_MD_XCCK_LANG_PAGE_ID', 'ID');
define('_MD_XCCK_LANG_TITLE', 'TITLE');
define('_MD_XCCK_LANG_UID', 'UID');
define('_MD_XCCK_LANG_MAINTABLE_ID', "Main Table");
define('_MD_XCCK_LANG_P_ID', 'Parent Page ID');
define('_MD_XCCK_LANG_DESCENDANT', 'Child Pages');
define('_MD_XCCK_LANG_WEIGHT', 'Weight');
define('_MD_XCCK_LANG_POSTTIME', 'Submit Time');
define('_MD_XCCK_LANG_UPDATETIME', 'Update Time');
define('_MD_XCCK_LANG_DEFINITION_VIEW', 'DEFINITION VIEW');
define('_MD_XCCK_LANG_CATEGORY_ID', 'Category');
define('_MD_XCCK_LANG_STATUS', 'Status');
define('_MD_XCCK_LANG_SHOW_LIST', 'Show on list');
define('_MD_XCCK_LANG_SEARCH', 'Search');
define('_MD_XCCK_LANG_SEARCH_FLAG', 'Search Condition');
define('_MD_XCCK_LANG_STATUS_DELETED', 'deleted');
define('_MD_XCCK_LANG_STATUS_REJECTED', 'rejected');
define('_MD_XCCK_LANG_STATUS_POSTED', 'posted');
define('_MD_XCCK_LANG_STATUS_PUBLISHED', 'published');
define('_MD_XCCK_ERROR_NO_PERMISSION', 'You don\'t have sufficient permissions');
define('_MD_XCCK_TIPS_FIELD_NAME', 'Field name in Database. Use only alphabet, number and _');
define('_MD_XCCK_TIPS_LABEL', 'field title for displaying');
define('_MD_XCCK_ERROR_DUPLICATE_DATA', 'Duplicated Field Name Error');
define('_MD_XCCK_LANG_YES', 'Yes');
define('_MD_XCCK_LANG_NO', 'No');
define('_MD_XCCK_TIPS_OPTIONS', '<p>When Field Type is ...</p><ul><li>Select box :Set options by one option in one line.</li><li>Check box :Set the display string when "checked" and "unchecked", separated by ENTER code. When empty, "'._MD_XCCK_LANG_YES.'" and "'._MD_XCCK_LANG_YES.'" is used.</li><li>String or Int : Set the default value.</li><li>Date: Set "hour" if you select date and hour. Set "half" if you select date, hour and every half hour. Set "quarter" if you select date, hour and every quarter hour. Set "10min" if you select date, hour and 10 minute. Set "minute" if you select date, hour and minute.</li><li>Text : Set "html" if you use wysiwyg editor(default is bbcode editor)</li><li>Category : Set LEGACY_CATEGORY module\'s dirname.</li></ul>');
define('_MD_XCCK_DESC_FIELD_SELECTBOX', 'Set options by one option in one line.');
define('_MD_XCCK_DESC_FIELD_CHECKBOX', 'Set the display string when "checked" and "unchecked", separated by ENTER code. When empty, "'._MD_XCCK_LANG_YES.'" and "'._MD_XCCK_LANG_YES.'" is used.');
define('_MD_XCCK_DESC_FIELD_STRING', 'Set the default value.');
define('_MD_XCCK_DESC_FIELD_INT', 'Set the default value.');
define('_MD_XCCK_DESC_FIELD_TEXT', 'Select "html" if you use wysiwyg editor.');
define('_MD_XCCK_DESC_FIELD_DATE', '<ul><li>Select "hour" if you select date and hour. </li><li>Select "half" if you select date, hour and every half hour. </li><li>Select "quarter" if you select date, hour and every quarter hour. </li><li>Select "10min" if you select date, hour and 10 minute. </li><li>Select "minute" if you select date, hour and minute.</li></ul>');
define('_MD_XCCK_DESC_FIELD_CATEGORY', 'Select LEGACY_CATEGORY module\'s dirname.');
define('_MD_XCCK_LANG_IMAGE_LIST', 'Image List');
define('_MD_XCCK_LANG_PAGE_TREE', 'Page Tree');

define('_MD_XCCK_TITLE_ACTION_VIEW', 'View');
define('_MD_XCCK_TITLE_ACTION_POST', 'Post');
define('_MD_XCCK_TITLE_ACTION_REVIEW', 'Review');
define('_MD_XCCK_TITLE_ACTION_MANAGE', 'Manage');
define('_MD_XCCK_DESC_ACTION_VIEW', 'View Data');
define('_MD_XCCK_DESC_ACTION_POST', 'Post Data');
define('_MD_XCCK_DESC_ACTION_REVIEW', 'Review Data');
define('_MD_XCCK_DESC_ACTION_MANAGE', 'Manage Module');

define('_MD_XCCK_ERROR_MAINTABLE_REQUIRED', 'Maintable\'s page ID is required.');
define('_MD_XCCK_MESSAGE_STATUS_POSTED', 'Waiting to be published.');
define('_MD_XCCK_LANG_PAGE_TREE_DELETE', 'Children pages will be deleted at the same time.');
define('_MD_XCCK_DESC_DEFAULT_FIELDS', 'The following fields have been already defined. Title, User ID, Order, Post time, Update time.');

define('_MD_XCCK_DESC_ENABLE_DATE', 'Set the term');

define('_MD_XCCK_LANG_MAP', 'Map');

?>
