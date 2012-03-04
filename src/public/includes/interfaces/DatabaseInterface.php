<?php 

interface DatabaseInterface
{

	public function connect($server, $username, $password);
	public function selectDb($database_name);
	public function escapeString($unescaped_string);
	public function query($query);
	public function numRows($resource);
	public function fetchAssoc($resource);

	public function setTablePrefix($prefix);
	public function getTablePrefix();
	public function getPrefixedTable($table_name);

}

?>