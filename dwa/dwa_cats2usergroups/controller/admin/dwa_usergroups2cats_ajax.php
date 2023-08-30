<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_usergroups2cats_ajax.php 14762 2020-06-05 09:11:36Z oliver $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_usergroups2cats_ajax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax {protected $_blAllowExtColumns = false;protected $_aColumns = array('container1' => array(array('oxtitle', 'oxcategories', 1, 0, 0 ), array('oxid', 'oxcategories', 0, 0, 0 ), array('oxid', 'oxcategories', 0, 0, 1 ), ), 'container2' => array(array('oxtitle', 'oxcategories', 1, 0, 0 ), array('oxid', 'oxcategories', 0, 0, 0 ), array('oxid', 'oxobject2group', 0, 0, 1 ), ));protected function _getQuery(){$sGroupTable = $this->_getViewName('oxcategories');$sGroupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid' );$sSynchGroupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid' );$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();if (!$sGroupId)$sQAdd = " from $sGroupTable ";else {$sQAdd = " from $sGroupTable, oxobject2group where ";$sQAdd .= " oxobject2group.oxgroupsid = ".$oDb->quote($sGroupId )." and oxobject2group.oxobjectid = $sGroupTable.oxid ";}if (!$sSynchGroupId )$sSynchGroupId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxajax_synchfid');if ($sSynchGroupId && $sSynchGroupId != $sGroupId){if (!$sGroupId )$sQAdd .= 'where ';else $sQAdd .= 'and ';$sQAdd .= " $sGroupTable.oxid not in (select $sGroupTable.oxid from $sGroupTable, oxobject2group where ";$sQAdd .= " oxobject2group.oxgroupsid = ".$oDb->quote($sSynchGroupId )." and oxobject2group.oxobjectid = $sGroupTable.oxid )";}return $sQAdd;}public function removeCatGroup(){$aRemoveGroups = $this->_getActionIds('oxobject2group.oxid' );if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all' )){$sQ = $this->_addFilter("delete oxobject2group.* ".$this->_getQuery());\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($sQ );}elseif ($aRemoveGroups && is_array($aRemoveGroups )){$sQ = "delete from oxobject2group where oxobject2group.oxid in (" . implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getInstance()->quoteArray($aRemoveGroups )). ")";\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($sQ );}}public function addCatGroup(){$aAddGroups = $this->_getActionIds('oxcategories.oxid' );$soxId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all' )){$sGroupTable = $this->_getViewName('oxcategories');$aAddGroups = $this->_getAll($this->_addFilter("select $sGroupTable.oxid ".$this->_getQuery()));}if ($soxId && $soxId != "-1" && is_array($aAddGroups )){foreach ($aAddGroups as $sAddgroup){$oNewGroup = oxNew(\OxidEsales\Eshop\Application\Model\Object2Group::class);$oNewGroup->oxobject2group__oxgroupsid = new \OxidEsales\Eshop\Core\Field($soxId);$oNewGroup->oxobject2group__oxobjectid = new \OxidEsales\Eshop\Core\Field($sAddgroup);$oNewGroup->save();}}}}