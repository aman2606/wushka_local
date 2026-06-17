<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
// load and register the autoload function
require_once ('stamp/library/SetaPDF/Autoload.php');
//----------------------------------Save Submitted Values----------------------------------\\
if(isset($_POST['isClipArt']) && ($_POST['isClipArt'] == 'yes' || $_POST['isClipArt'] == 'ebook'))
{
	$file = trim(str_replace(' ','',$_POST['fileURL']));
	$parsed_url = parse_url($file);
	$newURL = '';
	foreach ($parsed_url as $key => $value)
	{
		if($key == 'scheme')
			$value = $value . '://';
		if($key == 'port')
			$value = ':'.$value;
		if($key == 'query')
			$value = '?'.$value;
		if($key == 'path')
		{
			$explodePath = explode("/", $value);
			foreach ($explodePath as $part => $val)
			{
				$encodedname = urlencode($val);
				$val = $encodedname;
				$explodePath[$part] = $val;
			}
			$implodedPath = implode('/', $explodePath);
			$value = $implodedPath;
		}
		$newURL .= $value;
	}

	$filename = trim(str_replace(' ', '',$newURL));
	$imgInfo = getimagesize($filename);
	header('Content-Type: '.$imgInfo['mime']);
	header('Content-Disposition: attachment; filename="'.$_POST['fileName'].'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
	readfile($filename);
	exit;
}
else
{
// If A User was logged in when the stamped the PDF add their name to footer.
	if( isset ( $_POST['currentUser'] ))
	{
		$hasAnswer = $_POST['hasAnswer'];
		$downloadStatus = strtolower($_POST['downloadStatus']);
	}
	else
	{
		echo 'Please Log in to Stamp this PDF.';
		exit();
	}

	if($hasAnswer == "yes" && $downloadStatus == "download")
	{
		runAnswerDownloadStamper();
        }
        else if( isset ( $_POST['is_studentsList'] ) && ($_POST['is_studentsList'] == "yes" )) {

                runStudentsListDownloadStamper();
	}
	else
	{
		runNoAnswerStamper();
	}
}
function runAnswerDownloadStamper()
{
	$file = $_POST['pdfFilePath'];
	//echo 'sent file: '.$file .'<br /><br />';
	$parsed_url = parse_url($file);
	$newURL = '';
	foreach ($parsed_url as $key => $value)
	{
			//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
			if($key == 'scheme')
			{

				$value = $value . '://';
			}
			if($key == 'port')
			{

				$value = ':'.$value;
			}
			if($key == 'query')
			{
				$value = '?'.$value;
			}
			if($key == 'path')
			{
				//echo 'path: '.$value .'<br />';
				$explodePath = explode("/", $value);
				//$newVal = '';
				foreach ($explodePath as $part => $val)
				{
					//echo 'val = '.$val.'<br />';


					//echo 'filenamepath : '. $val .'<br />';
					$encodedname = urlencode($val);
					//echo 'urlencode filename: '.$encodedname.'<br />';

					$val = $encodedname;

					//echo 'new val = '.$val.'<br /><br />';
					$explodePath[$part] = $val;
				}

				$implodedPath = implode('/', $explodePath);
				//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
				$value = $implodedPath;

			}



			$newURL .= $value;
		}
		//echo 'parsed file: '.$newURL.'<br /><br />';
		$pdfFilePath = file_get_contents($newURL);

		// ----------------- Checks is PDF Has Answer Sheet ---------------
		$hasAnswer			 	= $_POST['hasAnswer'];
		if($hasAnswer == 'yes')
		{
			$ansFileName 		= $_POST['ansFileName'].'.pdf';

			$name = explode("_", $_POST['ansFileName']);
			//$name[1] = iconv("UTF-8", "UNICODE", $name[1]);
			//$pdfFileName = implode("_", $name);
			$ansZipFileName = $name[0] . '.pdf';

			$file = $_POST['ansFilePath'];
			//echo 'sent file: '.$file .'<br /><br />';
			$parsed_url = parse_url($file);
			$newURL = '';
			foreach ($parsed_url as $key => $value)
			{
				//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
				if($key == 'scheme')
				{

					$value = $value . '://';
				}
				if($key == 'port')
				{

					$value = ':'.$value;
				}
				if($key == 'query')
				{
					$value = '?'.$value;
				}
				if($key == 'path')
				{
					//echo 'path: '.$value .'<br />';
					$explodePath = explode("/", $value);
					//$newVal = '';
					foreach ($explodePath as $part => $val)
					{
						//echo 'val = '.$val.'<br />';


						//echo 'filenamepath : '. $val .'<br />';
						$encodedname = urlencode($val);
						//echo 'urlencode filename: '.$encodedname.'<br />';

						$val = $encodedname;

						//echo 'new val = '.$val.'<br /><br />';
						$explodePath[$part] = $val;
					}

					$implodedPath = implode('/', $explodePath);
					//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
					$value = $implodedPath;

				}



				$newURL .= $value;
			}
			//echo 'parsed file: '.$newURL.'<br /><br />';
			$ansFilePath = file_get_contents($newURL);
		}
		// ----------------------------------------------------------------


		//Load Submitted Values into new Variables
		$customField			= $_POST['customField'];
		$currentUser 			= $_POST['currentUser'];
		$copydec				= $_POST['copyDeclaration'];
		$termsandconditions		= $_POST['termsAndConditions'];

		//Logo Image URL Parse
		if(!isset($_POST['logoImage']))
			$uploadedLogo 		= 'none';
		if(isset($_POST['logoImage']))
		{
			$file = $_POST['logoImage'];
			if($file == 'none')
				$uploadedLogo = 'none';
			else if($file == '')
				$uploadedLogo 	= 'none';
			else
			{
				$parsed_url = parse_url($file);
				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$uploadedLogo = file_get_contents($newURL);
			}
		}//End isset logo image
		//URL Background Image Parse
		$stampBackground 		= $_POST['stampBackground'];
		if(!isset($_POST['backgroundImage']) && $stampBackground == 'image')
			$stampBackground 	= 'noBackground';
		if(isset($_POST['backgroundImage'])  && $stampBackground == 'image')
		{
			$file = $_POST['backgroundImage'];
			if($file == 'none')
				$stampBackground 	= 'noBackground';
			else if($file == '')
				$stampBackground 	= 'noBackground';
			else
			{
				$parsed_url = parse_url($file);
				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$stampBackgroundImage = file_get_contents($newURL);
			}
		}//End isset logo image


		$logoSize	 			= $_POST['logoSize'];
		$logoWidth	 			= $_POST['logoWidth'];
		$logoHeight				= $_POST['logoHeight'];

		if(!isset($_POST['pdfFileName']))
		{
			$pdfFileName 		= 'example.pdf';
			$zipFileName		= 'example.zip';
		}
		else
		{
			$fileName = $_POST['pdfFileName'];
			$tmpName = explode("_", $fileName);
			//$test = $tmpName[1];

		/* if($test === mb_convert_encoding(mb_convert_encoding($test, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
		{
		  $check = $test;
		}else
		{
		  // otherwise we should use
		  $check = mb_convert_encoding($test, 'UTF-8','CP850');
		} */
			$pdfZipFileName = $tmpName[0].'.pdf';
			//$pdfZipFileName = $fileName;
			//$pdfZipFileName = $fixedFilename;

			$pdfFileName 	= $_POST['pdfFileName'] . '.pdf';
			$zipFileName	= $_POST['pdfFileName'].'.zip';
		}

		$format 				= $_POST['pageFormat'];
		$stampType				= $_POST['stampPlacement'];


		$backColour 			= $_POST['backgroundColour'];
		$textColour				= $_POST['textColour'];
		$offset					= $_POST['heightOffset'];
		$stampHeight			= $_POST['stampHeight'];
		$watermarkRotate		= $_POST['wmRotation'];

		$downloadStatus = strtolower($_POST['downloadStatus']);

		//Set Default Distance From Edge(Any Lower Won't Print)
		$bottomOffset = 8;
		$topOffset	  = 22;

		//Adjust Height to setting option
		$bottomOffset 	= $bottomOffset + $offset;
		$topOffset 		= $topOffset 	+ $offset;

		//Break apart the Hex Code and turn into RGB values for Background Colour
		$back = str_replace ("#", "", $backColour);
		if(strlen($back) == 3)
		{
			$bk_R = hexdec(substr($back,0,1).substr($back,0,1));
			$bk_G = hexdec(substr($back,1,1).substr($back,1,1));
			$bk_B = hexdec(substr($back,2,1).substr($back,2,1));
		}
		else
		{
			$bk_R = hexdec(substr($back,0,2));
			$bk_G = hexdec(substr($back,2,2));
			$bk_B = hexdec(substr($back,4,2));
		}

		//RGB Values to change Background Colour
		$bk_R = $bk_R / 255;
		$bk_G = $bk_G / 255;
		$bk_B = $bk_B / 255;

		//Hex to Decimal Converter for Text Colour
		$text = str_replace ("#", "", $textColour);
		if(strlen($text) == 3)
		{
			$txt_R = hexdec(substr($text,0,1).substr($text,0,1));
			$txt_G = hexdec(substr($text,1,1).substr($text,1,1));
			$txt_B = hexdec(substr($text,2,1).substr($text,2,1));
		}
		else
		{
			$txt_R = hexdec(substr($text,0,2));
			$txt_G = hexdec(substr($text,2,2));
			$txt_B = hexdec(substr($text,4,2));
		}

		//RGB Values to change Background Colour
		$txt_R = $txt_R / 255;
		$txt_G = $txt_G / 255;
		$txt_B = $txt_B / 255;

	$zip = new ZipArchive();
	$zip->open( '../../uploads/'.$zipFileName, ZIPARCHIVE::OVERWRITE);
	for($p=0; $p<=1; $p++):
	if($p == 1)
	{
		$pdfFilePath = $ansFilePath;
		$pdfFileName = $ansFileName;
		$pdfZipFileName = $ansZipFileName;
	}

	//----------------------------------Load Original PDF Document----------------------------------------------------------------------------------------------------------------------------\\
	// let's get access to the file
	$reader = new SetaPDF_Core_Reader_String($pdfFilePath);
	// create a HTTP writer
	if($downloadStatus == 'download')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, false);
	}
	if($downloadStatus == 'print')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, true);
	}

	// let's get the document
	$document = SetaPDF_Core_Document::load($reader, $writer);

	//---------------------------------Get The Number of Pages-------------------------------------\\
	$pages = $document->getCatalog()->getPages();
	$pageCount = $pages->count();

	//----------------------------------Determine if Landscape or Portrait-------------------------\\
	$myformat = $format;
	for ( $i=1; $i <= $pageCount; $i++)
	{
		//get page width and height
		$page = $document->getCatalog()->getPages()->getPage($i);
		list($width, $height) = $page->getWidthAndHeight(); // array(width, height)

		//if page width is longer than page height it is a landscape page
		//if not it is a portrait format page
		if ($format == 'default' )
		{
			if ($height > $width)
				$myformat = 'portrait';
			else
				$myformat = 'landscape';
		}


		//----------------------------------Create Background Stamper----------------------------------\\

		//create new stamper instance
		$stamper = new SetaPDF_Stamper($document);
		$bgHeight = $stampHeight;

		// initiate the stamps
		//If Background is set to No Background
		if ( $stampBackground == 'noBackground' );
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
			// set transparency
			$stamp->setOpacity(0);
		}
		//If Background is set to Image
		if ( $stampBackground == 'image' )
		{

			$reader = new SetaPDF_Core_Reader_String($stampBackgroundImage);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));
			$stamp->setOpacity(0.5);
			$stamp->setHeight($bgHeight);
		}

		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//If page is a Portrait format and background is set to colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Add Stamp Positions
			//if Stamp is a Header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$offset );
			//if Stamp is a Footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0 , $offset );
		}

		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			//If landscape stamp background is colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Adjust Height and Width to suit the landscape page measurements
			$stamp->setWidth($height);
			$stamp->setHeight($bgHeight);
			//if stamp is set to header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $offset, 0, 90 );
			//if stmap is set to footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$offset, 0, 90 );

		}

		//if Stamp Type is set to Watermark
		if ( $stampType == 'watermark' )
		{
			//If stamp background is set to image
			if ( $stampBackground == 'image')
			{
				$stamp->setHeight(50);
				$stamp->setOpacity(0.8);
			}
			//if stamp background is set to colour
			if ( $stampBackground == 'colour')
			{
				$stamp->setWidth(200);
				$stamp->setHeight(150);
				$stamp->setOpacity(0.8);
			}

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.001;
			$y = 0.001;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}
		//Stamp to page
		$stamper->stamp();
		//----------------------------------Create Logo Stamper----------------------------------\\
		// initiate an Image stamper instance
		$stamper = new SetaPDF_Stamper($document);


		if($uploadedLogo == 'none')
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, 10, 10));
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);

			// set transparency
			$stamp->setOpacity(0);
		}
		else
		{
			$reader = new SetaPDF_Core_Reader_String($uploadedLogo);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));

			// set transparency
			$stamp->setOpacity(0.7);
		}

		// set height (and width until no setWidth is set the ratio will retain)
		if ( $logoSize == 'auto' )
			$stamp->setHeight($bgHeight- $topOffset);

		if ( $logoSize == 'newSet')
		{
			$stamp->setWidth($logoWidth);
			$stamp->setHeight($logoHeight);
		}
		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//Add Stamp Positions
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20, -$bottomOffset-3 );

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20, $topOffset-3 );
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $bottomOffset ,20 , 90 );
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$topOffset ,20 , 90 );
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 60;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			// set transparency
			$stamp->setOpacity(0.6);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}

		//Stamp to page
		$stamper->stamp();

		//----------------------------------Create Text Stamper----------------------------------\\

		// init the font you want to use for the text
		//$font = SetaPDF_Core_Font_Standard_Helvetica::create($document);
		$font = SetaPDF_Core_Font_TrueType::create ( $document, 'stamp/library/SetaPDF/Core/Font/TrueType/SofiaPro.ttf', 'WinAnsiEncoding', 'auto');
		//Set text Stamp and desired Style and Size
		$stamp = new SetaPDF_Stamper_Stamp_Text($font, 8);
		$stamp->setTextColor(array($txt_R,$txt_G,$txt_B));

		//------------------------------------ CopyRight Declaration -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		//Change Stamp to Copyright Line
		$stamp->setText($copydec);

		// set transparency
		$stamp->setOpacity(0.9);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20 , -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $topOffset , 20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$bottomOffset , 20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -40;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate);
		}

		//Stamp to page
		$stamper->stamp();
		//------------------------------------ CustomField Display  -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Add Custom Field Text to Stamp
		if ( $customField == 'none')
		{
			// set transparency
			$stamp->setOpacity(0.0);
		}
		else
		{
			$stamp->setText($customField);
		}

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -20, -$bottomOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -20, $topOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, $bottomOffset, -20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -$topOffset, -20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 40;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x, $y, $watermarkRotate);
		}


		//Stamp to page
		$stamper->stamp();
		//------------------------------------ Terms and Cond. -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Change Stamp to T and C's
		$stamp->setText($termsandconditions);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -20, -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i,-20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, $topOffset, -20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -$bottomOffset, -20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}


		//Stamp to page
		$stamper->stamp();

		//------------------------------------ Current User -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Change Stamp to Middle License Text
		$stamp->setText($currentUser);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$topOffset);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0, $topOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $topOffset, 0, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$topOffset, 0, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}
		//Stamp to page
		$stamper->stamp();
	}

	//save and finish the resulting document

	$document->save();


//	$zipName = $zipName. '.pdf';

	$zip->addFromString($pdfZipFileName, $writer);

	endfor;
	$zip->close();

	if(file_exists('../../uploads/'.$zipFileName))
	{
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$zipFileName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
		header('Content-Length: ' . filesize('../../uploads/'.$zipFileName));
		ob_clean();
		flush();
		readfile('../../uploads/'.$zipFileName);
		unlink('../../uploads/'.$zipFileName);

		exit;
	}

	$document->finish()->cleanup();
}

function runNoAnswerStamper()
{

		$file = $_POST['pdfFilePath'];

		$parsed_url = parse_url($file);
                    print_r($_POST['logoImage']);
		$newURL = '';
		foreach ($parsed_url as $key => $value)
		{
			//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
			if($key == 'scheme')
			{

				$value = $value . '://';
			}
			if($key == 'port')
			{

				$value = ':'.$value;
			}
			if($key == 'query')
			{
				$value = '?'.$value;
			}
			if($key == 'path')
			{
				//echo 'path: '.$value .'<br />';
				$explodePath = explode("/", $value);
				//$newVal = '';
				foreach ($explodePath as $part => $val)
				{
					//echo 'val = '.$val.'<br />';


					//echo 'filenamepath : '. $val .'<br />';
					$encodedname = urlencode($val);
					//echo 'urlencode filename: '.$encodedname.'<br />';

					$val = $encodedname;

					//echo 'new val = '.$val.'<br /><br />';
					$explodePath[$part] = $val;
				}

				$implodedPath = implode('/', $explodePath);
				//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
				$value = $implodedPath;

			}
			$newURL .= $value;
		}

		$pdfFilePath = file_get_contents($newURL);

		// ----------------- Checks is PDF Has Answer Sheet ---------------
		$hasAnswer			 	= $_POST['hasAnswer'];
		if($hasAnswer == 'yes')
		{
			$ansFileName 		= $_POST['ansFileName'].'.pdf';
			$file = $_POST['ansFilePath'];
			//echo 'sent file: '.$file .'<br /><br />';
			$parsed_url = parse_url($file);
			$newURL = '';
			foreach ($parsed_url as $key => $value)
			{
				//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
				if($key == 'scheme')
				{

					$value = $value . '://';
				}
				if($key == 'port')
				{

					$value = ':'.$value;
				}
				if($key == 'query')
				{
					$value = '?'.$value;
				}
				if($key == 'path')
				{
					//echo 'path: '.$value .'<br />';
					$explodePath = explode("/", $value);
					//$newVal = '';
					foreach ($explodePath as $part => $val)
					{
						//echo 'val = '.$val.'<br />';


						//echo 'filenamepath : '. $val .'<br />';
						$encodedname = urlencode($val);
						//echo 'urlencode filename: '.$encodedname.'<br />';

						$val = $encodedname;

						//echo 'new val = '.$val.'<br /><br />';
						$explodePath[$part] = $val;
					}

					$implodedPath = implode('/', $explodePath);
					//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
					$value = $implodedPath;

				}



				$newURL .= $value;
			}
			//echo 'parsed file: '.$newURL.'<br /><br />';
			$ansFilePath = file_get_contents($newURL);
		}
		// ----------------------------------------------------------------


		//Load Submitted Values into new Variables
		$customField			= $_POST['customField'];
		$currentUser 			= $_POST['currentUser'];
		$copydec				= $_POST['copyDeclaration'];
		$termsandconditions		= $_POST['termsAndConditions'];

		//Logo Image URL Parse
		if(!isset($_POST['logoImage']))
			$uploadedLogo 		= 'none';
		if(isset($_POST['logoImage']))
		{
			$file = $_POST['logoImage'];
			if($file == 'none')
				$uploadedLogo = 'none';
			else if($file == '')
				$uploadedLogo 	= 'none';
			else
			{
				$parsed_url = parse_url($file);

				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$uploadedLogo = file_get_contents($newURL);
			}
		}//End isset logo image
		//URL Background Image Parse
		$stampBackground 		= $_POST['stampBackground'];
		if(!isset($_POST['backgroundImage']) && $stampBackground == 'image')
			$stampBackground 	= 'noBackground';
		if(isset($_POST['backgroundImage'])  && $stampBackground == 'image')
		{
			$file = $_POST['backgroundImage'];
			if($file == 'none')
				$stampBackground 	= 'noBackground';
			else if($file == '')
				$stampBackground 	= 'noBackground';
			else
			{
				$parsed_url = parse_url($file);
				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$stampBackgroundImage = file_get_contents($newURL);
			}
		}//End isset logo image


		$logoSize	 			= $_POST['logoSize'];
		$logoWidth	 			= $_POST['logoWidth'];
		$logoHeight				= $_POST['logoHeight'];

		if(!isset($_POST['pdfFileName']))
		{
			$pdfFileName 		= 'example.pdf';
			$zipFileName		= 'example.zip';
		}
		else
		{
			$pdfFileName 		= $_POST['pdfFileName'].'.pdf';
			$zipFileName		= $_POST['pdfFileName'].'.zip';
		}

		$format 				= $_POST['pageFormat'];
		$stampType				= $_POST['stampPlacement'];


		$backColour 			= $_POST['backgroundColour'];
		$textColour				= $_POST['textColour'];
		$offset					= $_POST['heightOffset'];
		$stampHeight			= $_POST['stampHeight'];
		$watermarkRotate		= $_POST['wmRotation'];

		$downloadStatus = strtolower($_POST['downloadStatus']);

		//Set Default Distance From Edge(Any Lower Won't Print)
		$bottomOffset = 8;
		$topOffset	  = 22;

		//Adjust Height to setting option
		$bottomOffset 	= $bottomOffset + $offset;
		$topOffset 		= $topOffset 	+ $offset;

		//Break apart the Hex Code and turn into RGB values for Background Colour
		$back = str_replace ("#", "", $backColour);
		if(strlen($back) == 3)
		{
			$bk_R = hexdec(substr($back,0,1).substr($back,0,1));
			$bk_G = hexdec(substr($back,1,1).substr($back,1,1));
			$bk_B = hexdec(substr($back,2,1).substr($back,2,1));
		}
		else
		{
			$bk_R = hexdec(substr($back,0,2));
			$bk_G = hexdec(substr($back,2,2));
			$bk_B = hexdec(substr($back,4,2));
		}

		//RGB Values to change Background Colour
		$bk_R = $bk_R / 255;
		$bk_G = $bk_G / 255;
		$bk_B = $bk_B / 255;

		//Hex to Decimal Converter for Text Colour
		$text = str_replace ("#", "", $textColour);
		if(strlen($text) == 3)
		{
			$txt_R = hexdec(substr($text,0,1).substr($text,0,1));
			$txt_G = hexdec(substr($text,1,1).substr($text,1,1));
			$txt_B = hexdec(substr($text,2,1).substr($text,2,1));
		}
		else
		{
			$txt_R = hexdec(substr($text,0,2));
			$txt_G = hexdec(substr($text,2,2));
			$txt_B = hexdec(substr($text,4,2));
		}

		//RGB Values to change Background Colour
		$txt_R = $txt_R / 255;
		$txt_G = $txt_G / 255;
		$txt_B = $txt_B / 255;
	//----------------------------------Load Original PDF Document----------------------------------------------------------------------------------------------------------------------------\\
	for($p=0; $p<=1; $p++):
		if($p == 1)
		{
			$pdfFilePath = $ansFilePath;
			$pdfFileName = $ansFileName;
		}
	// let's get access to the file
	$reader = new SetaPDF_Core_Reader_String($pdfFilePath);
	// create a HTTP writer
	if($downloadStatus == 'download')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, false);
	}
	if($downloadStatus == 'print')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, true);
	}

	// let's get the document
	$document = SetaPDF_Core_Document::load($reader, $writer);

	//---------------------------------Get The Number of Pages-------------------------------------\\
	$pages = $document->getCatalog()->getPages();
	$pageCount = $pages->count();

	//----------------------------------Determine if Landscape or Portrait-------------------------\\
	$myformat = $format;
	for ( $i=1; $i <= $pageCount; $i++)
	{
		//get page width and height
		$page = $document->getCatalog()->getPages()->getPage($i);
		list($width, $height) = $page->getWidthAndHeight(); // array(width, height)

		//if page width is longer than page height it is a landscape page
		//if not it is a portrait format page
		if ($format == 'default' )
		{
			if ($height > $width)
				$myformat = 'portrait';
			else
				$myformat = 'landscape';
		}


		//----------------------------------Create Background Stamper----------------------------------\\

		//create new stamper instance
		$stamper = new SetaPDF_Stamper($document);
		$bgHeight = $stampHeight;

		// initiate the stamps
		//If Background is set to No Background
		if ( $stampBackground == 'noBackground' );
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
			// set transparency
			$stamp->setOpacity(0);
		}
		//If Background is set to Image
		if ( $stampBackground == 'image' )
		{

			$reader = new SetaPDF_Core_Reader_String($stampBackgroundImage);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));
			$stamp->setOpacity(0.5);
			$stamp->setHeight($bgHeight);
		}

		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//If page is a Portrait format and background is set to colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Add Stamp Positions
			//if Stamp is a Header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$offset );
			//if Stamp is a Footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0 , $offset );
		}

		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			//If landscape stamp background is colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Adjust Height and Width to suit the landscape page measurements
			$stamp->setWidth($height);
			$stamp->setHeight($bgHeight);
			//if stamp is set to header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $offset, 0, 90 );
			//if stmap is set to footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$offset, 0, 90 );

		}

		//if Stamp Type is set to Watermark
		if ( $stampType == 'watermark' )
		{
			//If stamp background is set to image
			if ( $stampBackground == 'image')
			{
				$stamp->setHeight(50);
				$stamp->setOpacity(0.8);
			}
			//if stamp background is set to colour
			if ( $stampBackground == 'colour')
			{
				$stamp->setWidth(200);
				$stamp->setHeight(150);
				$stamp->setOpacity(0.8);
			}

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.001;
			$y = 0.001;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}
		//Stamp to page
		$stamper->stamp();
		//----------------------------------Create Logo Stamper----------------------------------\\
		// initiate an Image stamper instance
		$stamper = new SetaPDF_Stamper($document);


		if($uploadedLogo == 'none')
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, 10, 10));
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);

			// set transparency
			$stamp->setOpacity(0);
		}
		else
		{
			$reader = new SetaPDF_Core_Reader_String($uploadedLogo);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));

			// set transparency
			$stamp->setOpacity(0.7);
		}

		// set height (and width until no setWidth is set the ratio will retain)
		if ( $logoSize == 'auto' )
			$stamp->setHeight($bgHeight- $topOffset);

		if ( $logoSize == 'newSet')
		{
			$stamp->setWidth($logoWidth);
			$stamp->setHeight($logoHeight);
		}
		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//Add Stamp Positions
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20, -$bottomOffset-3 );

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20, $topOffset-3 );
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $bottomOffset ,20 , 90 );
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$topOffset ,20 , 90 );
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 60;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			// set transparency
			$stamp->setOpacity(0.6);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}

		//Stamp to page
		$stamper->stamp();

		//----------------------------------Create Text Stamper----------------------------------\\

		// init the font you want to use for the text
		//$font = SetaPDF_Core_Font_Standard_Helvetica::create($document);
		$font = SetaPDF_Core_Font_TrueType::create ( $document, 'stamp/library/SetaPDF/Core/Font/TrueType/SofiaPro.ttf', 'WinAnsiEncoding', 'auto');
		//Set text Stamp and desired Style and Size
		$stamp = new SetaPDF_Stamper_Stamp_Text($font, 8);
		$stamp->setTextColor(array($txt_R,$txt_G,$txt_B));

		//------------------------------------ CopyRight Declaration -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		//Change Stamp to Copyright Line
		$stamp->setText($copydec);

		// set transparency
		$stamp->setOpacity(0.9);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20 , -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $topOffset , 20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$bottomOffset , 20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -40;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate);
		}

		//Stamp to page
		$stamper->stamp();
		//------------------------------------ CustomField Display  -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Add Custom Field Text to Stamp
		if ( $customField == 'none')
		{
			// set transparency
			$stamp->setOpacity(0.0);
		}
		else
		{
			$stamp->setText($customField);
		}

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -20, -$bottomOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -20, $topOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, $bottomOffset, -20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -$topOffset, -20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 40;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x, $y, $watermarkRotate);
		}


		//Stamp to page
		$stamper->stamp();
		//------------------------------------ Terms and Cond. -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Change Stamp to T and C's
		$stamp->setText($termsandconditions);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -20, -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i,-20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, $topOffset, -20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -$bottomOffset, -20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}


		//Stamp to page
		$stamper->stamp();

		//------------------------------------ Current User -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
		//Change Stamp to Middle License Text
		$stamp->setText($currentUser);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$topOffset);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0, $topOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $topOffset, 0, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$topOffset, 0, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}
		//Stamp to page
		$stamper->stamp();
	}

	$document->save()->finish();

	endfor;

	$document->cleanup();
} //End RunNoAnswerStamper


/*Students list download stamper*/
function runStudentsListDownloadStamper()
{

include "../../../wp-config.php";
include "../../../wp-includes.php";
$user_ID = get_current_user_id();


        // updated Feb 2019 to prevent count_total performing slow query
        $args = array(
            'role'       => 'student',
            'count_total'   => false,
            'meta_query' => array(
                'relation' => 'AND',
                0 => array(
                    'key'     => 'teacher_id',
                    'value'   => (!empty($user_ID) ? $user_ID : '')
                ),
                1 => array(
                    'key'     => 'active',
                    'value'   => 1
                )
            )
        );
        echo 'User is ' . $user_ID;
        $user_query = new WP_User_Query( $args );  // args updated for slow query
        $total = 0;
        if ( ! empty( $user_query->results ) ) {
            foreach ( $user_query->results as $idx  => $user ) {
                $total += 1;
            }
        }
        // User Loop

        /*End of getting student related query*/

		$file = $_POST['pdfFilePath'];

		$parsed_url = parse_url($file);
                print_r($_POST['logoImage']);
		$newURL = '';
		foreach ($parsed_url as $key => $value)
		{
			//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
			if($key == 'scheme')
			{

				$value = $value . '://';
			}
			if($key == 'port')
			{

				$value = ':'.$value;
			}
			if($key == 'query')
			{
				$value = '?'.$value;
			}
			if($key == 'path')
			{
				//echo 'path: '.$value .'<br />';
				$explodePath = explode("/", $value);
				//$newVal = '';
				foreach ($explodePath as $part => $val)
				{
					//echo 'val = '.$val.'<br />';


					//echo 'filenamepath : '. $val .'<br />';
					$encodedname = urlencode($val);
					//echo 'urlencode filename: '.$encodedname.'<br />';

					$val = $encodedname;

					//echo 'new val = '.$val.'<br /><br />';
					$explodePath[$part] = $val;
				}

				$implodedPath = implode('/', $explodePath);
				//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
				$value = $implodedPath;

			}
			$newURL .= $value;
		}

		$pdfFilePath = file_get_contents($newURL);

		// ----------------- Checks is PDF Has Answer Sheet ---------------
		$hasAnswer			 	= $_POST['hasAnswer'];
		if($hasAnswer == 'yes')
		{
			$ansFileName 		= $_POST['ansFileName'].'.pdf';
			$file = $_POST['ansFilePath'];
			//echo 'sent file: '.$file .'<br /><br />';
			$parsed_url = parse_url($file);
			$newURL = '';
			foreach ($parsed_url as $key => $value)
			{
				//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
				if($key == 'scheme')
				{

					$value = $value . '://';
				}
				if($key == 'port')
				{

					$value = ':'.$value;
				}
				if($key == 'query')
				{
					$value = '?'.$value;
				}
				if($key == 'path')
				{
					//echo 'path: '.$value .'<br />';
					$explodePath = explode("/", $value);
					//$newVal = '';
					foreach ($explodePath as $part => $val)
					{
						//echo 'val = '.$val.'<br />';


						//echo 'filenamepath : '. $val .'<br />';
						$encodedname = urlencode($val);
						//echo 'urlencode filename: '.$encodedname.'<br />';

						$val = $encodedname;

						//echo 'new val = '.$val.'<br /><br />';
						$explodePath[$part] = $val;
					}

					$implodedPath = implode('/', $explodePath);
					//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
					$value = $implodedPath;

				}



				$newURL .= $value;
			}
			//echo 'parsed file: '.$newURL.'<br /><br />';
			$ansFilePath = file_get_contents($newURL);
		}
		// ----------------------------------------------------------------


		//Load Submitted Values into new Variables
		//$customField			= unserialize($_POST['customField']);


		$currentUser 			= $_POST['currentUser'];
		$copydec				= $_POST['copyDeclaration'];
		$termsandconditions		= $_POST['termsAndConditions'];

		//Logo Image URL Parse
		if(!isset($_POST['logoImage']))
			$uploadedLogo 		= 'none';
		if(isset($_POST['logoImage']))
		{
			$file = $_POST['logoImage'];
			if($file == 'none')
				$uploadedLogo = 'none';
			else if($file == '')
				$uploadedLogo 	= 'none';
			else
			{
				$parsed_url = parse_url($file);
                                print_r($parsed_url);
				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$uploadedLogo = file_get_contents($newURL);
			}
		}//End isset logo image
		//URL Background Image Parse
		$stampBackground 		= $_POST['stampBackground'];
		if(!isset($_POST['backgroundImage']) && $stampBackground == 'image')
			$stampBackground 	= 'noBackground';
		if(isset($_POST['backgroundImage'])  && $stampBackground == 'image')
		{
			$file = $_POST['backgroundImage'];
			if($file == 'none')
				$stampBackground 	= 'noBackground';
			else if($file == '')
				$stampBackground 	= 'noBackground';
			else
			{
				$parsed_url = parse_url($file);
				$newURL = '';
				foreach ($parsed_url as $key => $value)
				{
					//echo 'key: '. $key . ' 		value: '. $value. '<br/>';
					if($key == 'scheme')
					{

						$value = $value . '://';
					}
					if($key == 'port')
					{

						$value = ':'.$value;
					}
					if($key == 'query')
					{
						$value = '?'.$value;
					}
					if($key == 'path')
					{
						//echo 'path: '.$value .'<br />';
						$explodePath = explode("/", $value);
						//$newVal = '';
						foreach ($explodePath as $part => $val)
						{
							//echo 'val = '.$val.'<br />';


							//echo 'filenamepath : '. $val .'<br />';
							$encodedname = urlencode($val);
							//echo 'urlencode filename: '.$encodedname.'<br />';

							$val = $encodedname;

							//echo 'new val = '.$val.'<br /><br />';
							$explodePath[$part] = $val;
						}

						$implodedPath = implode('/', $explodePath);
						//	echo 'implodedPath: '.$implodedPath .'<br /><br />';
						$value = $implodedPath;

					}



					$newURL .= $value;
				}


				$stampBackgroundImage = file_get_contents($newURL);
			}
		}//End isset logo image


		$logoSize	 			= $_POST['logoSize'];
		$logoWidth	 			= $_POST['logoWidth'];
		$logoHeight				= $_POST['logoHeight'];

		if(!isset($_POST['pdfFileName']))
		{
			$pdfFileName 		= 'example.pdf';
			$zipFileName		= 'example.zip';
		}
		else
		{
			$pdfFileName 		= $_POST['pdfFileName'].'.pdf';
			$zipFileName		= $_POST['pdfFileName'].'.zip';
		}

		$format 				= $_POST['pageFormat'];
		$stampType				= $_POST['stampPlacement'];


		$backColour 			= $_POST['backgroundColour'];
		$textColour				= $_POST['textColour'];
		$offset					= $_POST['heightOffset'];
		$stampHeight			= $_POST['stampHeight'];
		$watermarkRotate		= $_POST['wmRotation'];

		$downloadStatus = strtolower($_POST['downloadStatus']);

		//Set Default Distance From Edge(Any Lower Won't Print)
		$bottomOffset = 8;
		$topOffset	  = 22;

		//Adjust Height to setting option
		$bottomOffset 	= $bottomOffset + $offset;
		$topOffset 		= $topOffset 	+ $offset;

		//Break apart the Hex Code and turn into RGB values for Background Colour
		$back = str_replace ("#", "", $backColour);
		if(strlen($back) == 3)
		{
			$bk_R = hexdec(substr($back,0,1).substr($back,0,1));
			$bk_G = hexdec(substr($back,1,1).substr($back,1,1));
			$bk_B = hexdec(substr($back,2,1).substr($back,2,1));
		}
		else
		{
			$bk_R = hexdec(substr($back,0,2));
			$bk_G = hexdec(substr($back,2,2));
			$bk_B = hexdec(substr($back,4,2));
		}

		//RGB Values to change Background Colour
		$bk_R = $bk_R / 255;
		$bk_G = $bk_G / 255;
		$bk_B = $bk_B / 255;

		//Hex to Decimal Converter for Text Colour
		$text = str_replace ("#", "", $textColour);
		if(strlen($text) == 3)
		{
			$txt_R = hexdec(substr($text,0,1).substr($text,0,1));
			$txt_G = hexdec(substr($text,1,1).substr($text,1,1));
			$txt_B = hexdec(substr($text,2,1).substr($text,2,1));
		}
		else
		{
			$txt_R = hexdec(substr($text,0,2));
			$txt_G = hexdec(substr($text,2,2));
			$txt_B = hexdec(substr($text,4,2));
		}

		//RGB Values to change Background Colour
		$txt_R = $txt_R / 255;
		$txt_G = $txt_G / 255;
		$txt_B = $txt_B / 255;
	//----------------------------------Load Original PDF Document----------------------------------------------------------------------------------------------------------------------------\\
	$zip = new ZipArchive();
	$zip->open( '../../uploads/'.$zipFileName, ZIPARCHIVE::OVERWRITE);

    for($p=0; $p<=1; $p++):
		if($p == 1) {
			$pdfFilePath = $ansFilePath;
			$pdfFileName = $ansFileName;
		}

	// let's get access to the file
	$reader = new SetaPDF_Core_Reader_String($pdfFilePath);
	// create a HTTP writer
	if($downloadStatus == 'download')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, false);
	}
	if($downloadStatus == 'print')
	{
		$writer = new SetaPDF_Core_Writer_Http($pdfFileName, true);
	}

     //   $merger = new SetaPDF_Merger();

	// let's get the document
	$document = SetaPDF_Core_Document::load($reader, $writer);


	//---------------------------------Get The Number of Pages-------------------------------------\\
	$pages = $document->getCatalog()->getPages();
	$pageCount = $pages->count();

	//----------------------------------Determine if Landscape or Portrait-------------------------\\
	$myformat = $format;
	for ( $i=1; $i <= $pageCount; $i++)
	{
		//get page width and height
		$page = $document->getCatalog()->getPages()->getPage($i);
		list($width, $height) = $page->getWidthAndHeight(); // array(width, height)

		//if page width is longer than page height it is a landscape page
		//if not it is a portrait format page
		if ($format == 'default' )
		{
			if ($height > $width)
				$myformat = 'portrait';
			else
				$myformat = 'landscape';
		}


		//----------------------------------Create Background Stamper----------------------------------\\

		//create new stamper instance
		$stamper = new SetaPDF_Stamper($document);
		$bgHeight = $stampHeight;

		// initiate the stamps
		//If Background is set to No Background
		if ( $stampBackground == 'noBackground' );
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array ( 0, 0, $width, $bgHeight) );
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
			// set transparency
			$stamp->setOpacity(0);
		}
		//If Background is set to Image
		if ( $stampBackground == 'image' )
		{
			$reader = new SetaPDF_Core_Reader_String($stampBackgroundImage);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));
			$stamp->setOpacity(0.5);
			$stamp->setHeight($bgHeight);
		}

		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//If page is a Portrait format and background is set to colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Add Stamp Positions
			//if Stamp is a Header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$offset );
			//if Stamp is a Footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0 , $offset );
		}

		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			//If landscape stamp background is colour
			if ( $stampBackground == 'colour')
			{
				// initiate an xObject stamper instance
				$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, $width, $bgHeight));
				$canvas = $drawBackground->getCanvas();

				$canvas->path()->setLineWidth(1)->setColor(array(0,0,0));
				$canvas->draw()->setColor(array($bk_R,$bk_G,$bk_B), false)->rect(0, 0, $width, $bgHeight, 1);

				$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);
				$stamp->setOpacity(0.8);
			}

			//Adjust Height and Width to suit the landscape page measurements
			$stamp->setWidth($height);
			$stamp->setHeight($bgHeight);
			//if stamp is set to header
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $offset, 0, 90 );
			//if stmap is set to footer
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$offset, 0, 90 );

		}
		//if Stamp Type is set to Watermark
		if ( $stampType == 'watermark' )
		{
			//If stamp background is set to image
			if ( $stampBackground == 'image')
			{
				$stamp->setHeight(50);
				$stamp->setOpacity(0.8);
			}
			//if stamp background is set to colour
			if ( $stampBackground == 'colour')
			{
				$stamp->setWidth(200);
				$stamp->setHeight(150);
				$stamp->setOpacity(0.8);
			}

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.001;
			$y = 0.001;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}
		//Stamp to page
		$stamper->stamp();
		//----------------------------------Create Logo Stamper----------------------------------\\
		// initiate an Image stamper instance
		$stamper = new SetaPDF_Stamper($document);


		if($uploadedLogo == 'none')
		{
			$drawBackground = SetaPDF_Core_XObject_Form::create($document, array (0, 0, 10, 10));
			$stamp = new SetaPDF_Stamper_Stamp_XObject ($drawBackground);

			// set transparency
			$stamp->setOpacity(0);
		}
		else
		{
			$reader = new SetaPDF_Core_Reader_String($uploadedLogo);
			// initiate the stamps
			$stamp = new SetaPDF_Stamper_Stamp_Image(SetaPDF_Core_Image::get($reader));

			// set transparency
			$stamp->setOpacity(0.7);
		}

		// set height (and width until no setWidth is set the ratio will retain)
		if ( $logoSize == 'auto' )
			$stamp->setHeight($bgHeight- $topOffset);

		if ( $logoSize == 'newSet')
		{
			$stamp->setWidth($logoWidth);
			$stamp->setHeight($logoHeight);
		}
		//If the page format is Portrait, run this code
		if ( $myformat == 'portrait' )
		{
			//Add Stamp Positions
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20, -$bottomOffset-3 );

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20, $topOffset-3 );
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $bottomOffset ,20 , 90 );
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$topOffset ,20 , 90 );
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 60;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			// set transparency
			$stamp->setOpacity(0.6);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate );
		}

		//Stamp to page
		$stamper->stamp();

		//----------------------------------Create Text Stamper----------------------------------\\

		// init the font you want to use for the text
		//$font = SetaPDF_Core_Font_Standard_Helvetica::create($document);
		$font = SetaPDF_Core_Font_TrueType::create ( $document, 'stamp/library/SetaPDF/Core/Font/TrueType/SofiaPro.ttf', 'WinAnsiEncoding', 'auto');
		//Set text Stamp and desired Style and Size
		$stamp = new SetaPDF_Stamper_Stamp_Text($font, 10);
		$stamp->setTextColor(array($txt_R,$txt_G,$txt_B));

		//------------------------------------ CopyRight Declaration -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		//Change Stamp to Copyright Line
		$stamp->setText($copydec);

		// set transparency
		$stamp->setOpacity(0.9);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, 20 , -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, 20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		if ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_BOTTOM, $i, $topOffset , 20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i, -$bottomOffset , 20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -40;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i, $x , $y, $watermarkRotate);
		}

		//Stamp to page
		$stamper->stamp();
		//------------------------------------ CustomField Display  -------------------------------\\

                $stamper = new SetaPDF_Stamper($document);
		$stamp->setOpacity(1);
		//Add Custom Field Text to Stamp


                $stamp->setWidth(270);
                $stamp->setLineHeight(15);
                $stamp->setOutlineWidth(70);
                $x = 0;
                $y = 1;
                $marginW = 25;
                $marginH = 50;
                if ( ! empty( $user_query->results ) ) {
                        foreach ( $user_query->results as $idx  => $user ) {

                            $stamper = new SetaPDF_Stamper($document);
                            //First row
                             $stamp->setAlign(SetaPDF_Core_Text::ALIGN_CENTER);
                            if( $x == 0){
                                $y = -100;
                                 $stamp->setText( $user->first_name . " " . $user->last_name . "\n\n\n\n\n\n" . $user->license_key . "\n". 'temp_pwd' ."\n\n\n\n\n");
                                 $stamper->addStamp($stamp,array(
                                  'translateX' =>  $marginW,
                                   'translateY' =>  $y,
                                 ));
                                $x++;
                                $stamper->stamp();
                            } else
                                //Second row
                                  if( $x == 2){
                                      $y = -370;
                                    $stamp->setText( $user->first_name . ' ' . $user->last_name . "\n\n\n\n\n\n" . $user->license_key . "\n" . 'temp_pwd' ."\n\n\n\n\n");
                                     $stamper->addStamp($stamp,array(
                                       'translateX' =>  $marginW,
                                       'translateY' =>  $y,
                                     ));
                                $x++;
                                $stamper->stamp();
                            } else
                            //Thrid row
                              if ($x == 4){
                                  $y = -635;
                                  $stamp->setText( $user->first_name . ' ' . $user->last_name . "\n\n\n\n\n\n" . $user->license_key . "\n" . 'temp_pwd' ."\n\n\n\n\n");
                                  $stamper->addStamp($stamp,array(
                                       'translateX' =>  $marginW,
                                       'translateY' =>  $y,
                                     ));
                                $x++;
                                $stamper->stamp();

                              } else {
                                  $stamp->setText( $user->first_name . ' ' . $user->last_name . "\n\n\n\n\n\n" . $user->license_key . "\n" . 'temp_pwd' ."\n\n\n\n\n");
                                  $stamper->addStamp($stamp,array(
                                       'translateX' => 310,
                                       'translateY' => $y,
                                     ));
                                 $x++;
                                 $stamper->stamp();
                            }

                        }
                     }


		//Stamp to page

		//------------------------------------ Terms and Cond. -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
		$stamp->setOpacity(0.9);
                 $stamp->setBorderWidth(0);
		//Change Stamp to T and C's
		$stamp->setText($termsandconditions);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -20, -$topOffset);

			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_BOTTOM, $i,-20 , $bottomOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_TOP, $i, $topOffset, -20, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_TOP, $i, -$bottomOffset, -20, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = -30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}


		//Stamp to page
		$stamper->stamp();

		//------------------------------------ Current User -------------------------------\\

		// initiate a Text stamper instance
		$stamper = new SetaPDF_Stamper($document);
		// set transparency
                 $stamp->setBorderWidth(0);
		$stamp->setOpacity(0.9);
		//Change Stamp to Middle License Text
		$stamp->setText($currentUser);

		//If the page format is portrait, run this code
		if ( $myformat == 'portrait' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_TOP, $i, 0 , -$topOffset);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_BOTTOM, $i, 0, $topOffset);
		}
		//If the page format is landscape, run this code
		elseif ( $myformat == 'landscape' )
		{
			if ( $stampType == 'header' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_LEFT_MIDDLE, $i, $topOffset, 0, 90);
			if ( $stampType == 'footer' )
				$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_RIGHT_MIDDLE, $i, -$topOffset, 0, 90);
		}
		//If the page format is watermark, run this code
		if ( $stampType == 'watermark' )
		{
			// set transparency
			$stamp->setOpacity(0.8);

			//default x, y coord before being rotated
			//sin and cos functions wont work correctly with a 0 value,
			//temporarily use this instead.. Find Better work around
			$x = 0.1;
			$y = 30;

			//This section of code is for setting the rotation values for the stamp.
			//Due to having more than one stamp, each one is placed in different areas.
			//Must work out where to move and rotate them and make sure they stay in formation with each other
			$radius = round(sqrt( pow($x ,2) + pow($y,2)));
			$yx = $y / $x;
			$angle = 'x = ' .$x . ' y ='. $y . ' x/y =' . $yx;
			$tan = rad2deg(atan($yx)) + $watermarkRotate;

			//Set adjusted x,y coords for rotated watermark stamp
			$x = round(cos($tan * M_PI / 180) * $radius);
			$y = round(sin($tan * M_PI / 180) * $radius);

			//Add Stamp Position
			$stamper->addStamp($stamp, SetaPDF_Stamper::POSITION_CENTER_MIDDLE, $i,$x , $y, $watermarkRotate);
		}
		//Stamp to page
		$stamper->stamp();
	}

	$document->save()->finish();

	endfor;

	$document->cleanup();
} //End StudentsListDownloadStamper
