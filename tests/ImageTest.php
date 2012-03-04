<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "../src/public");

require_once 'PHPUnit/Autoload.php';

require_once 'includes/classes/Image.php';

class ImageTest extends PHPUnit_Framework_TestCase
{

	protected $image, $dao;

	private $row;

	protected function setUp()
	{
		$this->row = array(
			'display_name' => "Test",
			'file_name' => "a/b/c/aaa-bbb",
			'owner_id' => 3,
			'exif' => serialize(array("A"=>"b")),
			'upload_time' => '2012-02-29 01:02:59',
			'tags' => 'tag1 tag2',
			'width' => 640,
			'height'=> 480,
			'mime' => 'image/jpg',
			'hide_exif' => false,
			'description' => "Test image"
		);
		$this->dao = $this->getMock('ImageDao', array(), array(new Dao('prefix')));
	}

	protected function tearDown()
	{
		unset($this->dao);
		unset($this->image);
	}

	public function testConstruct()
	{

		$this->dao->expects($this->once())
					->method('getImageFromId')
					->with(1)
					->will($this->returnValue($this->row));

		$this->image = new Image($this->dao, 1);

		$this->assertEquals('Test', $this->image->getDisplayName());
		$this->assertEquals('2012-02-29 01:02:59', $this->image->getUploadTime(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'));
		$this->assertEquals('2012-02-29 02:02:59', $this->image->getUploadTime(new DateTimeZone('Europe/Rome'))->format('Y-m-d H:i:s'));

	}

	public function testCleanUpdate()
	{

		$this->dao->expects($this->once())
					->method('getImageFromId')
					->with(1)
					->will($this->returnValue($this->row));

		$this->image = new Image($this->dao, 1);

		$this->dao->expects($this->once())
					->method('updateImageFromId')
					->with(1, "Test", 3, serialize(array("A"=>"b")), new DateTime('2012-02-29 01:02:59', new DateTimeZone('UTC')), 
								'tag1 tag2', 640, 480, 'image/jpg', false, "Test image")
					->will($this->returnValue(true));

		$this->assertEquals(1, $this->image->save());

	}

	public function testDirtyUpdate()
	{

		$this->dao->expects($this->once())
					->method('getImageFromId')
					->with(1)
					->will($this->returnValue($this->row));

		$this->image = new Image($this->dao, 1);

		$this->image->setDisplayName("TestUPD");
		$this->image->setOwnerId(4);
		$this->image->setExif(array("b"=>"C"));
		$this->image->setUploadTime(new DateTime('2012-02-31 01:02:59', new DateTimeZone('Europe/Rome')));
		$this->image->setTags(array("aaaa","bbbb", "cccc"));
		$this->image->setHideExif(true);
		$this->image->setDescription("Test image UPD");

		$this->dao->expects($this->once())
					->method('updateImageFromId')
					->with(1, "TestUPD", 4, serialize(array("b"=>"C")), new DateTime('2012-03-02 00:02:59', new DateTimeZone('UTC')), 
								'aaaa bbbb cccc', 640, 480, 'image/jpg', true, "Test image UPD")
					->will($this->returnValue(true));

		$this->assertEquals(1, $this->image->save());

	}

}

?>