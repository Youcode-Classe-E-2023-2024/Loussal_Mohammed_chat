<?php

//Database_connection.php

class Database_connection
{
    /**
     * @return PDO
     */
	public function connect()
	{
		$connect = new PDO("mysql:host=localhost; dbname=chat", "root", "");

		return $connect;
	}
}

?>