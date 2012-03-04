<?php 

require_once("includes/interfaces/DatabaseInterface.php");

class Database_MySQL implements DatabaseInterface 
{

	private $_linkid = false;
	private $_tabprefix = '';

	public function connect($server, $username, $password)
	{
		$this->_linkid = mysql_connect($server, $username, $password);
		return ($this->_linkid !== false);
	}

	public function selectDb($database_name)
	{
		return mysql_select_db($database_name, $this->_linkid);
	}

	public function escapeString($unescaped_string)
	{
		return mysql_real_escape_string($unescaped_string, $this->_linkid);
	}

	public function query($query)
	{
		return mysql_query($query, $this->_linkid);
	}

	public function numRows($resource)
	{
		return mysql_num_rows($resource);
	}

	public function fetchAssoc($resource)
	{
		return mysql_fetch_assoc($resource);
	}

	public function setTablePrefix($prefix)
	{
		$this->_tabprefix = $prefix;
	}

	public function getTablePrefix()
	{
		return $this->_tabprefix;
	}

}

?>