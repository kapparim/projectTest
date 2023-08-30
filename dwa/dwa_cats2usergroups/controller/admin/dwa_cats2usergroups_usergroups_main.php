<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_cats2usergroups_usergroups_main.php 14762 2020-06-05 09:11:36Z oliver $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_cats2usergroups_usergroups_main extends dwa_cats2usergroups_usergroups_main_parent {public function render(){$tpl = parent::render();if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("dwaaoc")){$oMainAjax = oxNew('dwa_usergroups2cats_ajax' );$this->_aViewData['oxajax'] = $oMainAjax->getColumns();return "dwa_usergroups2cats_ajax.tpl";}return $tpl;}}