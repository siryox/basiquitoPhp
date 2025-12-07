<html lang="es" xml:lang="es">
<head>
	<title>ESTADO DE CUENTA CREDITO</title>
    <meta charset="utf-8">
<?php 
    include('ConexionMeta.php');
    $idCredito= $_GET['IdCredito'];
	$paginaActual = 1; //$_POST['partida'];
    $p='{"idCredito":'.$idCredito.',"nombreUsuario":"Rafucho el Maracucho"}';
    $k="Fetch select prepFrmEdoCta('".$p."')as frm";
    $frm       = EjDb($k,'');
    $aCred       = EjDb("Fetch select * from vCreditos where id='$idCredito'",'');
    $aReg        = EjDb("CALL edoCtaCred('$idCredito')",'');
    $filename    ='EdoCuentaCred_'.$idCredito.''.$aCred['RazonSocial'].''.$aCred['rubro'].'_'.$aCred['ciclo'].'.xls';
    header("Pragma: public");
    header("Expires: 0");
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    echo $frm[0];



    ?>