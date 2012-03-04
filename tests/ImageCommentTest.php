<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../src/public");

require_once 'PHPUnit/Autoload.php';

require_once 'includes/classes/ImageComment.php';

class ImageCommentTest extends PHPUnit_Framework_TestCase
{

	protected $comment, $dao;

	protected function setUp()
	{
		
	}

	protected function tearDown()
	{
		unset($this->dao);
		unset($this->comment);
	}

	public function testConstruct()
	{
		$row = array(
			'id' => 1,
			'user_id' => 2,
			'image_id' => 3,
			'datetime' => '2012-02-29 01:02:59',
			'content' => 'Awesome content'
		);

		$this->dao = $this->getMock('ImageCommentDao', array(), array(new Dao('pref')));

		$this->dao->expects($this->once())
					->method('getImageCommentFromId')
					->with(1)
					->will($this->returnValue($row));

		$this->comment = new ImageComment($this->dao, 1, null);

		$this->assertEquals('Awesome content', $this->comment->getContent());
		$this->assertEquals('2012-02-29 01:02:59', $this->comment->getDateTime(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
		$this->assertEquals('2012-02-29 02:02:59', $this->comment->getDateTime(new DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s'));
	}

}

?>