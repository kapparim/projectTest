<?php
 /**
  * OXID Modul:  DWA_cats2usergroups
  * @version:    $Id: dwa_cats2usergroups_events.php 14762 2020-06-05 09:11:36Z oliver $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link https://www.web-grips.de
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
 class dwa_cats2usergroups_events {public static function onActivate(){$modulName = 'dwa_cats2usergroups';$oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();$directory = $oConfig->getConfigParam('sShopDir').'modules/dwa/'.$modulName;$sqlFile = $directory .'/changes.sql';$logfile = fopen($directory .'/logfile.txt', 'a+');if (file_exists($sqlFile)){fwrite($logfile, "\r\n" . date('d.m.Y H:i: ').'WEB-Grips - Start');fwrite($logfile, "\r\n" .'Verarbeite Datei '.$sqlFile);$statements = explode(';', file_get_contents($sqlFile));$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC );foreach ($statements as $query){$query = trim($query);if (!empty($query)){$oShop = $oConfig->getActiveShop();$oxshopid = $oShop->getId();$query = str_replace('oxbaseshop', $oxshopid, $query);try {$oDB->execute($query);$text[] = $query .' => Success';}catch (\Exception $e){$text[] = $e;}}}$oMetaData = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);if ($oMetaData->updateViews()== 1){$text[] = 'Views wurden aktualisiert';}else {$text[] = 'Views wurden NICHT aktualisiert';oxUtilsView::getInstance()->addErrorToDisplay('Bitte Views aktualisieren unter Service -> Tools.', false, true);}$text[] = 'WEB-Grips - Ende';foreach ($text as $value){fwrite($logfile, "\r\n" .$value);}fclose($logfile);unlink($sqlFile);$oShop = $oConfig->getActiveShop();$oModul = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);$oModul->load($modulName);$oEmail = oxNew(\OxidEsales\Eshop\Core\Email::class);$oEmail->setRecipient('info@die-web-architektin.de');$oEmail->setFrom($oShop->oxshops__oxowneremail->value, $oShop->oxshops__oxname->getRawValue());$oEmail->setBody('Es wurde ein Modul aktiviert:
Modulname : '.$modulName .'
Version   : '.$oModul->getInfo('version').'
Server    : '.$_SERVER['SERVER_NAME']);$oEmail->isHtml(false);$oEmail->setSmtp($oShop );$oEmail->setSubject('Aktivierung Modul - '.$modulName .'');$oEmail->send();}}}