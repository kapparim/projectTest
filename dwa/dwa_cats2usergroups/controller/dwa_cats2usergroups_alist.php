<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_cats2usergroups_alist.php 14762 2020-06-05 09:11:36Z oliver $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_cats2usergroups_alist extends dwa_cats2usergroups_alist_parent {public function render(){if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dwa_cats2ug_catsnotvisible')&& (!$this->getUser()|| !$this->getUser()->dwaIsAdminUser())&& $this->dwaHasNoAccessToCat()){$myConfig = \OxidEsales\Eshop\Core\Registry::getConfig();\OxidEsales\Eshop\Core\Registry::getUtils()->redirect($myConfig->getShopHomeURL());}return parent::render();}public function dwaHasNoAccessToCat(){if (!$oCategory = $this->getActCategory()){return false;}$oUser = $this->getUser();if ($oUser && $oUser->getUserGroups()){foreach ($oUser->getUserGroups()as $oGroup ){$aGroups[] = '"'.$oGroup->getId().'"';}if (is_array($aGroups)){$userGroups = implode(',', $aGroups);}$userGroups .= ',"dwanotloggedin"';}else {$userGroups = '"dwanotloggedin"';}$sql = 'SELECT 1 FROM oxobject2group WHERE oxobjectid = "'.$oCategory->getId().'" AND oxgroupsid IN ('.$userGroups .')';if (!\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sql)){if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('dwa_cats2ug_showcatswithoutug')){$sql = 'SELECT 1 FROM oxobject2group WHERE oxobjectid = "'.$oCategory->getId().'"';if (!\OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sql)){return false;}}return true;}}}