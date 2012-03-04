<?php

class Dao {

	protected $table_prefix;

	public function __construct($table_prefix)
	{
		$this->table_prefix = $table_prefix;
	}

	public function connect($server, $username, $password)
	{
		$linkid = mysql_connect($server, $username, $password);
		return ($linkid !== false);
	}

	public function setTablePrefix($prefix)
	{
		$this->table_prefix = $prefix;
	}

	public function getTablePrefix()
	{
		return $this->table_prefix;
	}

	public function getPrefixedTable($table_name)
	{
		return $this->table_prefix . '_' . $table_name;
	}

}

?>
