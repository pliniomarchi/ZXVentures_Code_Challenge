<?php
$connection = new PDO('sqlite:messaging.sqlite3');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
//
$sql  = "CREATE TABLE IF NOT EXISTS pdv (
                    id INTEGER PRIMARY KEY, 
                    document TEXT, 
                    tradingname TEXT, 
					ownername TEXT, 
					lat TEXT, 
					long TEXT, 
					coverarea TEXT)";
$stmt = $connection->prepare($sql);
$stmt->execute();
?>