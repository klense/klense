<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../src/public");

require_once 'PHPUnit/Autoload.php';

require_once '../src/public/includes/classes/User.php';

class UserTest extends PHPUnit_Framework_TestCase
{

	protected $user, $mdao;

	protected function setUp()
	{
		$this->mdao = $this->getMock('UserDao', array(), array(new Dao('prefix')));
		$this->user = new User($this->mdao, 0);
	}

	protected function tearDown()
	{
		unset($this->mdao);
		unset($this->user);
	}

	public function testId()
	{
		$this->assertEquals(0, $this->user->getId());
	}

	public function testUsernameStartsEmpty()
	{
		$this->assertEquals("", $this->user->getUsername());
	}

	public function testUsernameDoesNotContainInvalidChars()
	{
		$this->setExpectedException('InvalidArgumentException');
/*
		$this->mdao->expects($this->once())
					->method('getUserIdFromUsername')
					->with('')
					->will($this->returnValue($this->row));
*/
		//$this->user->setUsername("dniaele");
		$this->user->setUsername("Aàjĸ½-Doo");
		//$this->user->setUsername("Aàjĸ½-Doo");
		//$this->assertEquals("Aàjĸ½-Doo", $this->user->getUsername());

		//$this->markTestIncomplete();
	}

	public function testUsernameIsAtLeast5Chars()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->user->setUsername("aaaa");
	}

	public function testUsernameIsAtLeast5Chars2()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->user->setUsername("");
	}

	public function testUsernameIsNotModifiedIfNotValid()
	{
		try {
			$this->user->setUsername("#đ¶");
		} catch(InvalidArgumentException $ex) { }
		$this->assertEquals("", $this->user->getUsername());
	}

	public function testUsernameIsUnique()
	{
		$this->mdao->expects($this->once())
					->method('getUserIdFromUsername')
					->with('test_username')
					->will($this->returnValue(-1));

		$this->user->setUsername("test_username");
	}

	public function testUsernameIsUnique2()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->mdao->expects($this->once())
					->method('getUserIdFromUsername')
					->with('test_username')
					->will($this->returnValue(123));

		$this->user->setUsername("test_username");
	}

	public function testUsername()
	{
		$this->mdao->expects($this->once())
					->method('getUserIdFromUsername')
					->with('test_username')
					->will($this->returnValue(-1));

		$this->user->setUsername("test_username");
		$this->assertEquals("test_username", $this->user->getUsername());
	}

	public function testPasswordIsAtLeast6Chars()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->user->setPassword("passw");
	}

	public function testPasswordIsAtLeast6Chars2()
	{
		$this->setExpectedException('InvalidArgumentException');
		$this->user->setPassword("");
	}

	public function testPassword()
	{
		$this->user->setPassword("pass_word");
	}

	public function testEmailIsUnique()
	{
		$this->mdao->expects($this->once())
					->method('getUserIdFromEmail')
					->with('aaa@bbb.ccc')
					->will($this->returnValue(-1));

		$this->user->setEmail('aaa@bbb.ccc');
	}

	public function testEmailIsUnique2()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->mdao->expects($this->once())
					->method('getUserIdFromEmail')
					->with('aaa@bbb.ccc')
					->will($this->returnValue(123));

		$this->user->setEmail('aaa@bbb.ccc');
	}

	public function testEmail()
	{
		$this->mdao->expects($this->once())
					->method('getUserIdFromEmail')
					->with('aaa@bbb.ccc')
					->will($this->returnValue(-1));

		$this->user->setEmail("aaa@bbb.ccc");
		$this->assertEquals("aaa@bbb.ccc", $this->user->getEmail());
	}

	public function testActivated()
	{
		$this->user->setActivated(true);
		$this->assertEquals(true, $this->user->getActivated());
	}

	public function testActivationCode()
	{
		$oldact = $this->user->getActivationCode();
		$this->user->setNewActivationCode();
		$this->assertNotEquals($oldact, $this->user->getActivationCode());
	}

	public function testRegistrationTime()
	{
		$this->user->setRegistrationTime(new DateTime('2012-12-00', new DateTimeZone('UTC'))); // Test day-0 and timezone shift
		$this->assertEquals('2012-11-30 01:00:00', $this->user->getRegistrationTime(new DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s'));
	}

	public function testTimezone()
	{
		$this->user->setTimezone(new DateTimeZone('Europe/Rome'));
		$this->assertEquals(new DateTimeZone('Europe/Rome'), $this->user->getTimezone());
	}

	public function testDeleteThrowsExceptionIfUserIsNew()
	{
		$this->setExpectedException('LogicException');
		$this->user->delete();
	}

}

?>