<?php
/**
 * This class creates customised photoframe for your pictures.
 *
 * @author Rochak Chauhan
 */
define("WHITE", "255,255,255");
define("BLACK", "0,0,0");
define("GREY", "128,128,128");
define("RED", "255,0,0");
define("MAROON", "128,0,0");
define("LIGHT_GREEN", "0,255,0");
define("DARK_GREEN", "0,128,0");
define("BLUE", "0,0,255");
define("NAVY_BLUE", "0,0,128");
define("PINK", "255,0,255");

class PhotoFrame {
	private $textColor;
	protected $image;
	private $frameWidth;
	private $height;
	private $width;
	
	private $fontSize = 20;
	private $text = "Rochak Chauhan";
	private $centerPic = "centerpic.png";
	private $backgroundImage = 'bg.png';
	private $alphabetDir = '/public/_data/images/libs';
	protected $imageResource = '';
	
	private $text_X = 0;
	private $text_Y = 0;

	public function PhotoFrame($frameWidth, $frameHeight, $textarray, $bgColor = BLACK, $textColor = WHITE) {

		if($frameWidth <= 0 || $frameHeight <= 0 || !is_numeric($frameWidth) || !is_numeric($frameHeight) )  {
			die("<BR><font color='red' size='3' face='verdana'><b>ERROR:</b> Invalid Width and/or height.   </font>");
		}
		if (trim($textColor) == "") {
			$textColor = $this->textColor;
		}

		$this->width = $frameWidth;
		$this->height = $frameHeight;
		$this->imageResource = imagecreate($frameWidth, $frameHeight) or die("Cannot Initialize new GD image stream");

		$bgColorArray = explode(",", $bgColor);
		$textColorArray = explode(",", $textColor);

		imagecolorallocate($this->imageResource, $bgColorArray[0], $bgColorArray[1], $bgColorArray[2]);
		$this->textColor = imagecolorallocate($this->imageResource, $textColorArray[0], $textColorArray[1], $textColorArray[2]);
		$this->ShowTextAsPng($textarray);
	}

	private function ShowTextAsPng($textarray){
	
		$randArrayX = array();
		$randArrayY = array();
		// set random x array
		for ($i=0; $i<count($textarray); $i++) {
			$randArrayX[] = $this->width/rand(1,10);
		}
		// set random y array
		for ($i=0; $i<count($textarray); $i++){
			$randArrayY[] = ($this->height/count($textarray))*$i;
		}
		for($i=0; $i<count($textarray); $i++){
			$this->TestOnImage($textarray[$i], $randArrayX[$i], $randArrayY[$i]);
		}		
	}
	
	/**
	 * Contructor function
	 *
	 * @param string $string
	 * @param int $text_X
	 * @param int $text_Y
	 * @param string $backgroundImage
	 * @param string $alphabetDir
	 * 
	 * @return object
	 */
	public function TestOnImage($string, $text_X = 0, $text_Y = 0, $backgroundImage = '', $alphabetDir = '') {
		
		$text_X = (int) $text_X;
		$text_Y = (int) $text_Y;
		
		if ($text_X < 0 || $text_X > 999) {
			trigger_error('Please enter a numeric value for Y coordinate between 0 - 999. You entered: '.$text_X, E_USER_ERROR);
			exit;
		}
		else {
			$this->text_X = $text_X;
		}
				
		if ($text_Y < 0 || $text_Y > 999) {
			trigger_error('Please enter a numeric value for the Y coordinate between 0 - 999. You entered: '.$text_Y, E_USER_ERROR);
			exit;
		}
		else {
			$this->text_Y = $text_Y;
		}
		
		if(trim($backgroundImage)  != '') {
			$this->backgroundImage = $backgroundImage;
		}
		
		if(trim($alphabetDir)  != '') {
			$this->alphabetDir = $alphabetDir;
		}
		if(trim($string) != '') {
			$this->text = $string;
			$this->createImage();
		}
	}


   /**
	* Function to create image from text 
	*
	*/
	function createImage() {
		$letterArray = array();
		for ($i=0; $i < strlen($this->text); $i++) {

			$var  = strtolower($this->text[$i]);
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			if ($var == ' ') {
				$var = $this->alphabetDir.DIRECTORY_SEPARATOR."space.gif";
			}
			elseif ($var == '.') {
				$var = $this->alphabetDir.DIRECTORY_SEPARATOR."dot.gif";
			}
			else {
				$var = $this->alphabetDir."/$var.gif";
			}
			$letterArray[] = $var;
		}
				
		$y = 0;
		
		for ($i=0; $i<count($letterArray); $i++) {
			$s[$i] = imagecreatefromgif($letterArray[$i]);			
			imagecopymerge($this->imageResource, $s[$i], ($this->text_X+$y), $this->text_Y, 0, 0, 20, 30, 100);			
			$y = $y+20;
		}
		
		$centerPic = imagecreatefrompng($this->centerPic);
		$centerPicSize = getimagesize($this->centerPic);
		
		//imagecopymerge($this->imageResource, $centerPic, $centerPicSize[0]/2, $centerPicSize[1]/2, 0, 0, 20, 30, 100);			
		imagecopymerge($this->imageResource, $centerPic, ($this->width - $centerPicSize[0])/2, ($this->height - $centerPicSize[1])/2, 0, 0, $centerPicSize[0], $centerPicSize[1], 100);			
		imagepng($this->imageResource, 'final.png');
	}
}
?>