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

require_once XCCK_TRUST_PATH . '/class/AbstractFilterForm.class.php';

define('XCCK_DEFINITION_SORT_KEY_DEFINITION_ID', 1);
define('XCCK_DEFINITION_SORT_KEY_FIELD_NAME', 2);
define('XCCK_DEFINITION_SORT_KEY_LABEL', 3);
define('XCCK_DEFINITION_SORT_KEY_FIELD_TYPE', 4);
define('XCCK_DEFINITION_SORT_KEY_VALIDATION', 5);
define('XCCK_DEFINITION_SORT_KEY_REQUIRED', 6);
define('XCCK_DEFINITION_SORT_KEY_WEIGHT', 7);
define('XCCK_DEFINITION_SORT_KEY_SHOW_LIST', 8);
define('XCCK_DEFINITION_SORT_KEY_SEARCH_FLAG', 9);
define('XCCK_DEFINITION_SORT_KEY_DESCRIPTION', 10);
define('XCCK_DEFINITION_SORT_KEY_OPTIONS', 11);
define('XCCK_DEFINITION_SORT_KEY_DEFAULT', XCCK_DEFINITION_SORT_KEY_WEIGHT);

/**
 * Xcck_DefinitionFilterForm
**/
class Xcck_DefinitionFilterForm extends Xcck_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
        XCCK_DEFINITION_SORT_KEY_DEFINITION_ID => 'definition_id',
        XCCK_DEFINITION_SORT_KEY_FIELD_NAME => 'field_name',
        XCCK_DEFINITION_SORT_KEY_LABEL => 'label',
        XCCK_DEFINITION_SORT_KEY_FIELD_TYPE => 'field_type',
        XCCK_DEFINITION_SORT_KEY_VALIDATION => 'validation',
        XCCK_DEFINITION_SORT_KEY_REQUIRED => 'required',
        XCCK_DEFINITION_SORT_KEY_WEIGHT => 'weight',
        XCCK_DEFINITION_SORT_KEY_SHOW_LIST => 'show_list',
        XCCK_DEFINITION_SORT_KEY_SEARCH_FLAG => 'search_flag',
        XCCK_DEFINITION_SORT_KEY_DESCRIPTION => 'description',
        XCCK_DEFINITION_SORT_KEY_OPTIONS => 'options'
    );

    /**
     * getDefaultSortKey
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function getDefaultSortKey()
    {
        return XCCK_DEFINITION_SORT_KEY_DEFAULT;
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        parent::fetch();
    
        $root = XCube_Root::getSingleton();
    
        if (($value = $root->mContext->mRequest->getRequest('definition_id')) !== null) {
            $this->mNavi->addExtra('definition_id', $value);
            $this->_mCriteria->add(new Criteria('definition_id', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('field_name')) !== null) {
            $this->mNavi->addExtra('field_name', $value);
            $this->_mCriteria->add(new Criteria('field_name', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('label')) !== null) {
            $this->mNavi->addExtra('label', $value);
            $this->_mCriteria->add(new Criteria('label', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('field_type')) !== null) {
            $this->mNavi->addExtra('field_type', $value);
            $this->_mCriteria->add(new Criteria('field_type', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('validation')) !== null) {
            $this->mNavi->addExtra('validation', $value);
            $this->_mCriteria->add(new Criteria('validation', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('required')) !== null) {
            $this->mNavi->addExtra('required', $value);
            $this->_mCriteria->add(new Criteria('required', $value));
        }
    
        if (($value = $root->mContext->mRequest->getRequest('weight')) !== null) {
            $this->mNavi->addExtra('weight', $value);
            $this->_mCriteria->add(new Criteria('weight', $value));
        }
    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
