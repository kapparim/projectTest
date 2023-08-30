<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_cats2usergroups_oxuser.php 14762 2020-06-05 09:11:36Z oliver $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_cats2usergroups_oxuser extends dwa_cats2usergroups_oxuser_parent {public function dwaGetSqlUsergroupsSnippet($default){if (!$this->getId()){$this->loadActiveUser();}if ($this->dwaIsAdminUser()){return $default;}if (!$this->getUserGroups()|| count($this->getUserGroups())< 1){return '"dwanotloggedin"';}foreach ($this->getUserGroups()as $oGroup ){$aGroups[] = '"'.$oGroup->getId().'"';}return implode(',', $aGroups);}public function dwaIsAdminUser(){if ($this->oxuser__oxrights->value == 'malladmin'){return true;}}}