<?php
session_start();
$id_usuario = $_SESSION['id_usuario'];
$nombreUsuario = $_SESSION['alias'];


$mysqli = new mysqli("localhost", "dbmaster", "*dbmaster#", "gfa_ve_agrimeta");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}else
    {
        $p='{"idCredito":'.$_GET['id'].',"nombreUsuario":"'.$nombreUsuario.'"}';
        $sql = "Fetch select prepFrmEdoCta('".$p."')as frm";
        if ($resultado = mysqli_query($mysqli, $sql)) {

            print_r($resultado);
        }


    } 


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<?php 
header("Pragma: public");
header("Expires: 0");
$filename = "nombreArchivoQueDescarga.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

?>
</head>
<body>

</body>
</html>