<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../src/public");

require_once 'PHPUnit/Autoload.php';

require_once '../src/public/includes/classes/User.php';
require_once "includes/interfaces/DatabaseInterface.php";

class UserTest extends PHPUnit_Framework_TestCase
{

	public function testRegistrationTimeConsistency()
	{
		$db = $this->getMock('DatabaseInterface');
		$user = new User($db);
		$user->setRegistrationTime(new DateTime('2012-12-00', new DateTimeZone('UTC'))); // Test day-0 and timezone shift

		$this->assertEquals('2012-11-30 01:00:00', $user->getRegistrationTime(new DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s'));
	}

}

?>