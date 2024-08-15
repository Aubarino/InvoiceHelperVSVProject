<!DOCTYPE html> 
<html lang='en'> 
	<head> 
		<link rel="stylesheet" href="index.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Main Page</title> 
		<style>
			body {
				height: 100vh;
				overflow: hidden;
				display: flex;
				justify-content: center;
				align-items: center;
				font-size: 30px;
			}
		</style>
		<script> //using some javascript to make stuff easier
			window.addEventListener('wheel', function(event) {
				var direction = event.deltaY > 0 ? -1 : 1; //limit the speed to a good range
				let pageUrl = new URL(window.location.href);
				pageUrl.searchParams.set('scrollDirection', +pageUrl.searchParams.get('scrollDirection') + +direction);
				window.location.href = pageUrl.toString();
        	});
		</script>
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
			">
		</button>
	</body>
</html> 
 
<?php 
	date_default_timezone_set("Australia/Melbourne");
	//-= FACTORY-DEFAULT INVOICES =-
	$invoices = [
		array(
			"to"=>"Steve",
			"from"=>"You",
			"number"=>883851321,
			"date"=>date("d/m/y", mktime(0, 0, 0, 31, 7, 2024)),
			"status"=>false,
			"submitted"=>false
		),
		array(
			"to"=>"Bob",
			"from"=>"You",
			"number"=>431100187,
			"date"=>date("d/m/y", mktime(0, 0, 0, 12, 8, 2024)),
			"status"=>false,
			"submitted"=>false
		)
	];
	
	verifyInvoiceData("invoice/invoices.txt",$invoices);
	$invoices = pullDataFromFile("invoice/invoices.txt"); //all invoices loaded
	$cache = $invoices; //current invoices cache, from whatever active search result
	$cacheAmt = count($cache); //the current length of the invoices cache at any time
	$cacheAmtVisual = 0; //the current visual length of currently rendered invoices (used internally)
	$cacheOffset = 0; //offset the readable area of the invoices cache, scrolling up or down...

	if (isset($_GET['scrollDirection'])) { //scrolling code
		$scrollDirection = $_GET['scrollDirection'];
		echo "<br> Scroll position: " . htmlspecialchars($scrollDirection);
		$cacheOffset = htmlspecialchars($scrollDirection);
	}

	$cacheAmt = 16;
	if ($cacheOffset > 0) $cacheOffset = 0; //placeholder workaround
	$cacheAmtVisual = clamp(($cacheAmt + $cacheOffset) - 1,-1,5); //revise later
	if($cacheAmtVisual > -1){
		for ($i = 0; $i <= $cacheAmtVisual; $i++){
			echo '<img src="assets/main_entry'.$i.'_false.png" class="img2"/>';
			echo('<div id="text"
				style="
					z-index: 100;
					color: rgba(44,24,16,0.9);
					font-size: 30px;
					font-weight: bold;
					font-family: Cursive;
					position: absolute;
					top: '. 40 + (87 * $i).'px;
					left: 150px;"
			>invoice_'. $i + 1 - $cacheOffset .' <br> 2nd line :)</div>');
		}
	}

	//-= FILES RELATED FUNCTIONS =-

	function verifyInvoiceData($dataPath,$dataSet){ //either replaces invoice data with default or ignores, simply a safety check layer
		if (file_exists($dataPath)){
			return false;
		}else{
			writeInvoiceData($dataPath,$dataSet);
		}
		return true; //returns depending on if it replaced or not
	}
	function writeInvoiceData($dataPath,$dataSet){ //writes over the contents of invoice data in file(s)
		$jsonOut = json_encode($dataSet,JSON_PRETTY_PRINT);
		$fileVerify = fopen($dataPath, "w");
		file_put_contents($dataPath, $jsonOut);
		fclose($fileVerify);
	}
	function pullDataFromFile($dataPath){; //not really a need for stuff like this, but its useful for management of code and such later
		$fileVerify = fopen($dataPath, "r");
		$outValue = file_get_contents($dataPath);
		$jsonOut = json_decode($outValue, true);
		fclose($fileVerify);
		settype($jsonOut,"array"); //revise later
		writeInvoiceData(str_replace(".txt", "_backup.txt", $dataPath),$jsonOut); //saves a backup file of the last loaded content
		return($jsonOut);
	}

	//-= SEARCH RELATED FUNCTIONS =-

	function quickRefresh(){ //a partial refresh of only the invoices cache
		//fill with search related stuff later
		$cacheAmt = count($cache);
	}

	//-= OTHER =-

	function clamp($value, $min, $max){ //not the best way to do this, couldn't find much better on the topic, this is safe enough for now.
		return max($min, min($max, $value));
	}
?> 
