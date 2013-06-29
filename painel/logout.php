<?php
session_start();
session_unset();
session_destroy();
include '../modulosPHP/class.wTools.php';

$oUtil = new wTools();
$aRet = $oUtil->buscarParametro(array('PG_LOGOFF'));
$sUrlRetorno = $aRet['PG_LOGOFF'][0];
header('Location:'.$sUrlRetorno);
  

?>