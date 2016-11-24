<?php
//stoled from http://www.cleverlogic.net/tutorials/how-dynamically-get-your-sites-main-or-base-url
function curPageURL()
{
	/* First we need to get the protocol the website is using */
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';

	/* returns /myproject/index.php */
	$path = $_SERVER['PHP_SELF'];

	/*
	 * returns an array with:
	 * Array (
	 *  [dirname] => /myproject/
	 *  [basename] => index.php
	 *  [extension] => php
	 *  [filename] => index
	 * )
	 */
	$path_parts = pathinfo($path);
	$directory = $path_parts['dirname'];
	/*
	 * If we are visiting a page off the base URL, the dirname would just be a "/",
	 * If it is, we would want to remove this
	 */
	$directory = ($directory == "/") ? "" : $directory;

	/* Returns localhost OR mysite.com */
	$host = $_SERVER['HTTP_HOST'];

	/*
	 * Returns:
	 * http://localhost/mysite
	 * OR
	 * https://mysite.com
	 */
	return $protocol . $host . $directory . "/";
}

	header("content-type: text/plain");
	$rawPost = file_get_contents("php://input");
	$xml = trim($rawPost);
	if(empty($xml) || $xml == "")
	{
		echo "No POST data received.";
		exit;
	}
	
	$db = new SQLite3('/Users/AndrewMohawk/www/html/mapme/mapme.db');
	
	$key = MD5(microtime());
	$statement = $db->prepare("INSERT INTO xml( ID,Key,XML ) VALUES ( NULL, :key, :xml )");
	$statement->bindValue(':key', $key,SQLITE3_TEXT);
	$statement->bindValue(':xml', $xml,SQLITE3_TEXT);
	$result = $statement->execute();
	
	if($result)
	{
	
		$path = curPageURL() . "view.php?key=$key";
		echo $path;
	}
	else
	{
		echo "Failed to Insert data :(";
	}
