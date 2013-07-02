<?php
	$link = mysql_connect('localhost', 'root', 'root');
      
	if (!$link) {
		die('Não foi possível conectar: ' . mysql_error());
	}else {
            
            mysql_select_db("db_lv", $link);
        }
        
?>