<?php
	@session_start();
	mysql_connect("127.0.0.1", "root", "Mathwizard366960706");   //classic sql connection
	mysql_select_db("main");                  //classic sql connection

	$db = new mysqli("127.0.0.1", "root", "Mathwizard366960706");
?>	