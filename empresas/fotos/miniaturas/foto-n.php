<?php
include("m2brimagem.class.php");
$arquivo    = "../".$_GET['imagem'];
$largura    = 300;
$altura     = 400;
$oImg = new m2brimagem($arquivo);
$valida = $oImg->valida();
if ($valida == 'OK') {
        if($largura == "0"){
        $oImg->redimensiona(NULL,$altura,'crop');
        }else{
        $oImg->redimensiona($largura,$altura,'crop');
        }
        //$oImg->marcaFixa('../logo-com-m.png','baixo_direita');
    $oImg->grava();
} else {
    die($valida);
}
exit;
?>
