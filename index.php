<!DOCTYPE html> 
<html lang='en'> 
	<head> 
		<link rel="stylesheet" href="index.css">
		<title>Main Page</title> 
	</head> 

	<body>
		<img class="img1" src="assets/main_ui_elementmain.png"/>

		<!--view cleared button-->
		<a href="addnewinvoice.php" target="MainClicked"><button class="button" type="button"
			style="
				width: 90px;
				height: 60px;
				top: 517px;
				left: 30px;
				background-color: rgba(255, 255, 255, 0);
				border: none;
			"></button>
		</a>
		<!--clear paid button-->
		<a href="addnewinvoice.php" target="MainClicked"><button class="button" type="button"
			style="
				width: 90px;
				height: 60px;
				top: 440px;
				left: 30px;
				background-color: rgba(255, 255, 255, 0);
				border: none;
			"></button>
		</a>
		<!--question button-->
		<a href="addnewinvoice.php" target="MainClicked"><button class="button" type="button"
			style="
				width: 80px;
				height: 80px;
				top: 595px;
				left: 35px;
				background-color: rgba(255, 255, 255, 0);
				border: none;
			"></button>
		</a>
		<!--search button-->
		<a href="addnewinvoice.php" target="MainClicked"><button class="button" type="button"
			style="
				width: 80px;
				height: 90px;
				top: 35px;
				left: 30px;
				background-color: rgba(255, 255, 255, 0);
				border: none;
			"></button>
		</a>
		<!--refresh button-->
		<button class="button" type="button" onClick="window.location.reload();"
			style="
				width: 35px;
				height: 38px;
				top: 620px;
				left: 378px;
				background-color: rgba(255, 255, 255, 0);
				border: none;
			"></button>

	</body>
</html> 
 
<?php 
	$testdump = array("test1"=>1, "test2"=>1, "test3"=>1);
	verifyInvoiceData("invoice/test1234.txt",$testdump);

	function verifyInvoiceData($dataPath,$dataSet){ //either replaces invoice data with default or ignores, simply a safety check layer
		if (file_exists($dataPath)){
			return false;
		}else{
			writeInvoiceData($dataPath,$dataSet);
		}
		return true; //returns depending on if it replaced or not
	}
	function writeInvoiceData($dataPath,$dataSet){ //writes over the contents of invoice data in file(s)
		$jsonOut = json_encode($dataSet);
		$fileVerify = fopen($dataPath, "w");
		file_put_contents($dataPath, $jsonOut);
		fclose($fileVerify);
	}
	function pullDataFromFile($dataPath){; //not really a need for stuff like this, but its useful for management of code and such later
		$fileVerify = fopen($dataPath, "r");
		$outValue = readfile($dataPath);
		$jsonOut = json_decode($outValue, false);
		fclose($fileVerify);
		return($jsonOut);
	}
?> 
