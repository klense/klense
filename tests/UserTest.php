<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../src/public");

require_once 'PHPUnit/Autoload.php';

require_once '../src/public/includes/classes/User.php';

class UserTest extends PHPUnit_Framework_TestCase
{

	protected $user, $mdao;

	protected function setUp()
	{
		$this->mdao = $this->getMock('UserDao');
		$this->user = new User($this->mdao);
	}

	protected function tearDown()
	{
		unset($this->mdao);
		unset($this->user);
	}

	public function testRegistrationTimeConsistency()
	{
		$this->user->setRegistrationTime(new DateTime('2012-12-00', new DateTimeZone('UTC'))); // Test day-0 and timezone shift

		$this->assertEquals('2012-11-30 01:00:00', $this->user->getRegistrationTime(new DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s'));
	}

}

?>