<?php 

// http://www.white-hat-web-design.co.uk/blog/resizing-images-with-php/

class ImageManipulator {

	private $image;
	private $image_type;

	function load($filename)
	{
		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=90, $permissions=null)
	{
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image, $filename, $compression);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image, $filename);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image, $filename);
		}
		if( $permissions != null) {
			chmod($filename, $permissions);
		}
	}

	function output($image_type=IMAGETYPE_JPEG)
	{
		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {
			imagegif($this->image);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image);
		}
	}

	function getWidth()
	{
		return imagesx($this->image);
	}

	function getHeight()
	{
		return imagesy($this->image);
	}

	function getImageType()
	{
		return $this->image_type;
	}

	function resizeToHeight($height)
	{
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}

	function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}

	function scale($scale)
	{
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}

	function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}

	function resizeCrop($thumbnail_width, $thumbnail_height) {
		$ratio_orig = $this->getWidth() / $this->getHeight();

		if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
			$new_height = $thumbnail_width / $ratio_orig;
			$new_width = $thumbnail_width;
		} else {
			$new_width = $thumbnail_height * $ratio_orig;
			$new_height = $thumbnail_height;
		}

		$x_mid = $new_width / 2;  // horizontal middle
		$y_mid = $new_height / 2; // vertical middle

		$process = imagecreatetruecolor(round($new_width), round($new_height));

		imagecopyresampled($process, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
		$thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

		imagedestroy($process);

		$this->image = $thumb;
	}
 
}
?>