<?php 

interface DaoInterface
{

	public function __construct(Dao $dao);
	public function getDao();
	public function setDao(Dao $dao);

}

?>