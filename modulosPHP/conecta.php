<?php
	$link = mysql_connect('localhost', 'root', 'root');
      
	if (!$link) {
		die('N�o foi poss�vel conectar: ' . mysql_error());
	}else {
            
            mysql_select_db("db_lv", $link);
        }
        
?>