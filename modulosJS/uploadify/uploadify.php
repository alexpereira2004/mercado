<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
include_once '../../modulosPHP/config.php';
// Define a destination
//$CFGsPastaImagensProdutos = '/Mercadodossabores/comum/imagens/produtos'; // Relative to the root


// Teste de variavel
ob_start();
echo var_dump($_SERVER['DOCUMENT_ROOT'] . $CFGsPastaImagensProdutos);
echo "\n";
$oArq = fopen('D:\Alex\meus documentos\minhas webs\htdocs\debug.txt', 'w+');



$verifyToken = md5('unique_hash' . $_POST['timestamp']);



if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $CFGsPastaImagensProdutos;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
    $sResultado = 'Sucesso!';
	} else {
		echo 'Invalid file type.';
    $sResultado = 'Erro';
	}
} else {
  $sResultado = 'Erro na verificaчуo do arquivo';
}

echo $sResultado."\n";
fwrite($oArq, ob_get_contents());
ob_end_clean();
?>