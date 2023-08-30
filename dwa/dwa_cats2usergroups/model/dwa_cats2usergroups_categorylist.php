<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_cats2usergroups_categorylist.php 14858 2020-06-22 17:08:34Z bettina $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_cats2usergroups_categorylist extends dwa_cats2usergroups_categorylist_parent {protected function _getSelectString($blReverse = false, $aColumns = null, $sOrder = null){if (!\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dwa_cats2ug_catsnotvisible')|| $this->isAdmin()|| ($this->getUser()&& $this->getUser()->dwaIsAdminUser())){return parent::_getSelectString($blReverse, $aColumns, $sOrder);}else {$sGroupViewName = getViewName("oxgroups", 0 );$sViewName = $this->getBaseObject()->getViewName();$sFieldList = $this->_getSqlSelectFieldsForTree($sViewName, $aColumns);if (!$this->isAdmin()&& !$this->_blHideEmpty && !$this->_blForceFull){$oCat = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);if (!($this->_sActCat && $oCat->load($this->_sActCat)&& $oCat->oxcategories__oxrootid->value)){$oCat = null;$this->_sActCat = null;}$sUnion = '';$sWhere = '1';}else {$sUnion = '';$sWhere = '1';}$oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);$sJoin = 'oxobject2group.oxobjectid = '.$sViewName .'.oxid';$sWhere .= ' and (oxobject2group.oxgroupsid IN ('.$oUser->dwaGetSqlUsergroupsSnippet('oxgroupsid').')';if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dwa_cats2ug_showcatswithoutug')){$sWhere .= ' OR oxobject2group.oxgroupsid IS NULL';}$sWhere .= ')';if (!$sOrder){$sOrdDir = $blReverse?'desc':'asc';$sOrder = "oxrootid $sOrdDir, oxleft $sOrdDir";}return "select $sFieldList from $sViewName LEFT JOIN oxobject2group ON $sJoin where $sWhere $sUnion order by $sOrder";}}protected function _getDepthSqlUnion($oCat, $aColumns = null){if (!$oCat){return '';}$sViewName = $this->getBaseObject()->getViewName();$oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);$sJoinWhere .= ' (oxobject2group.oxgroupsid IN ('.$oUser->dwaGetSqlUsergroupsSnippet('oxgroupsid').')';if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dwa_cats2ug_showcatswithoutug')){$sJoinWhere .= ' OR oxobject2group.oxgroupsid IS NULL';}$sJoinWhere .= ')';$sql = "UNION SELECT ".$this->_getSqlSelectFieldsForTree('maincats', $aColumns)." FROM oxcategories AS subcats" ." LEFT JOIN $sViewName AS maincats on maincats.oxparentid = subcats.oxparentid" ." LEFT JOIN
                        (  SELECT  *
                            FROM    oxobject2group
                            WHERE " .$sJoinWhere . "
                        )oxobject2group
                        ON oxobject2group.oxobjectid = maincats.oxid" ." WHERE subcats.oxrootid = ".\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quote($oCat->oxcategories__oxrootid->value)." AND subcats.oxleft <= ". (int)$oCat->oxcategories__oxleft->value ." AND subcats.oxright >= ".(int)$oCat->oxcategories__oxright->value .$sWhere;return $sql;}}