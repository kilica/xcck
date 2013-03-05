<?php

class Xcck_SearchUtils
{
    public static function makeKeyword(/*** string ***/ $keyword)
    {
        return '%'.addslashes(stripslashes($keyword)).'%';
    }

    public static function splitKeywords(/*** string ***/ $keywords)
    {
        return explode(' ', mb_convert_kana($keywords, 's'));
    }

    public static function makeKeywordCriteria(CriteriaCompo $cri, /*** string ***/ $dirname, /*** string ***/ $keywords, /*** string ***/ $andor='AND')
    {
        $handler = Legacy_Utils::getModuleHandler('definition', $dirname);
    	//keywords
    	$keywordArr = self::splitKeywords($keywords);
    
        //definition
        $cri->add(new Criteria('search_flag', 1));
        $cri->add(new Criteria('field_type', array(Xcck_FieldType::STRING, Xcck_FieldType::TEXT, Xcck_FieldType::URI), 'IN'));
        $defObjs =$handler->getObjects($cri);
        foreach($defObjs as $obj){
            $fieldList['field_name'][] = $obj->get('field_name');
            $fieldList['field_type'][] = $obj->get('field_type');
        }
        //page
        $cri = new CriteriaCompo();
        foreach($defObjs as $def){
            foreach($keywordArr as $keyword){
                if(strtoupper($andor)=='OR'){
                    $cri->add(new Criteria($def->get('field_name'), self::makeKeyword($keyword), 'LIKE'), 'OR');
                }
                else{
                    $cri->add(new Criteria($def->get('field_name'), self::makeKeyword($keyword), 'LIKE'));
                }
            }
        }
        return $cri;
    }

    public static function searchKeyword(/*** string ***/ $dirname, /*** string[] ***/ $keywords, /*** string ***/ $andor='AND', /*** int ***/ $limit=null, /*** int ***/ $offset=null, /*** int ***/ $uid=null)
    {
        $cri = Xcck_Utils::getListCriteria($dirname);
        $cri = self::makeKeywordCriteria($cri, $dirname, $keywords, $andor);
        if(intval($uid)>0){
            $cri->add(new Criteria('uid', $uid));
        }
    
        $handler = Legacy_Utils::getModuleHandler('page', $dirname);
        $pageObjs = $handler->getObjects($cri, $limit, $offset);
        foreach($pageObjs as $page){
            $ret[] = array(
                'link' => "index.php?page_id=".$page->getShow('page_id'),
                'title' => $page->getShow('title'),
                'time' => $page->get('posttime'),
                'uid' => $uid,
                "context" => '',
            );
        }
        return $ret;
    }

}
?>
