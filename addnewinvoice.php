<!DOCTYPE html> 
<html lang='en'> 
	<head> 
		<link rel="stylesheet" href="index.css">
		<meta name="viewport" content="width=device-width, initial-scale=0.78, user-scalable=no">
		<title>Add Invoice</title> 
		<style>
			body {
				height: 100%;
				margin: 0;
				overflow: hidden;
				display: flex;
				flex-direction: column;
				justify-content: flex-end;
				align-items: top left;
			}
			.zoom-container {
				height: 100%;
				width: 100%;
				display: flex;
				justify-content: top left;
				align-items: top left;
			}
		</style>
		<script> //using some javascript to make stuff easier
			let pageUrl = new URL(window.location.href); //moved this up here instead of inside functions, for easy editting
			const searchParams = new URLSearchParams(pageUrl.search);

			function selectionToggle(index){ //toggle a specific invoice cache index from selection
				selectionState = -1;
				if (searchParams.has('selection'+index)){
					selectionState = (searchParams.get('selection'+index));
				}
				selectionState *= -1; //workaround
				searchParams.set('selection'+index, selectionState);

				pageUrl.search = searchParams.toString();
				window.location.href = pageUrl.toString();
			}

			function selectionClear(){ //clear all invoice cache selections
				pageUrl.search = "";
				window.location.href = pageUrl.toString();
			}

			function mainScroll(direction){  //handles scrolling in a direction, also scroll buttons
				searchParams.set('scrollDirection', +searchParams.get('scrollDirection') + +direction);

				pageUrl.search = searchParams.toString();
				window.location.href = pageUrl.toString();
			}

			window.addEventListener('wheel', function(event) { //input scrolling event
				var direction = event.deltaY > 0 ? -1 : 1; //limit the speed to a good range
				mainScroll(direction);
        	});
		</script>
	</head> 

	<body>
		<div class="zoom-container">
			<img class="img1" src="assets/main_ui_elementmain_new.png"/>

			<!--scroll up manual control button-->
			<button class="button" type="button"
				onClick="mainScroll(1);"
				style="
					width: 25px;
					height: 25px;
					top: 15px;
					left: 124px;
					background-color: rgba(255, 0, 0, 0);
					border: none;
				">
			</button>
			<!--scroll down manual control button-->
			<button class="button" type="button"
				onClick="mainScroll(-1);"
				style="
					width: 25px;
					height: 25px;
					top: 677px;
					left: 135px;
					background-color: rgba(255, 0, 0, 0);
					border: none;
				">
			</button>

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
			<button class="button" type="button" onClick="selectionClear();"
				style="
					width: 35px;
					height: 38px;
					top: 620px;
					left: 378px;
					background-color: rgba(255, 255, 255, 0);
					border: none;
				">
			</button>
		</div>
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
		),
		array(
			"to"=>"Steve",
			"from"=>"You",
			"number"=>382846195,
			"date"=>date("d/m/y", mktime(0, 0, 0, 21, 9, 2023)),
			"status"=>true,
			"submitted"=>true
		),
		array(
			"to"=>"You",
			"from"=>"Bob",
			"number"=>619293701,
			"date"=>date("d/m/y", mktime(0, 0, 0, 4, 11, 2023)),
			"status"=>true,
			"submitted"=>false
		),
		array(
			"to"=>"You",
			"from"=>"John",
			"number"=>276272894,
			"date"=>date("d/m/y", mktime(0, 0, 0, 23, 6, 2023)),
			"status"=>true,
			"submitted"=>true
		),
		array(
			"to"=>"John",
			"from"=>"You",
			"number"=>981661123,
			"date"=>date("d/m/y", mktime(0, 0, 0, 9, 5, 2023)),
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
		//echo "<br> Scroll position: " . htmlspecialchars($scrollDirection);
		$cacheOffset = htmlspecialchars($scrollDirection);
	}

	$selectionCurrent = array(); //used to store the current invoice cache entry selections
	for($i = 0; $i < $cacheAmt; $i++){ //collects the invoice selection information from the url
		if (isset($_GET['selection'.$i])) {
			$selectionCurrent[$i] = (htmlspecialchars($_GET['selection'. $i]));
		}else{
			$selectionCurrent[$i] = -1;
		}
	}

	function selectionGet($index){ //returns the value of the selection status of the invoice cache rendering entry
		return($GLOBALS['selectionCurrent'][$index]);
	}

	//$cacheAmt = 16;
	if ($cacheAmt > count($cache)) return; //just to be safe

	if ($cacheOffset > 0) $cacheOffset = 0; //placeholder workaround
	$cacheAmtVisual = clamp(($cacheAmt + $cacheOffset) - 1,-1,5); //revise later
	if($cacheAmtVisual > -1){
		for ($i = 0; $i <= $cacheAmtVisual; $i++){ //renders the invoices list ui
			$invoiceInstance = $cache[$i - $cacheOffset];
			echo '<img src="assets/main_entry'.$i.((selectionGet($i - $cacheOffset) < 0) ? '_false.png"' : '_true.png"').' class="img2"/>';
			echo('<div id="text"
				style="
					z-index: 1;
					color: rgba(44,24,16,0.9);
					font-size: 20px;
					font-weight: bold;
					font-family: Arial;
					position: absolute;
					top: '. 45 + (87 * $i).'px;
					left: 150px;"
				>number: '. $invoiceInstance["number"] . $i + 1 - $cacheOffset .'<br>to: '. $invoiceInstance["to"] . ' from: '. $invoiceInstance["from"] .' '.
				($invoiceInstance["status"] ? '💰Paid' : '💰Unpaid'). '<br>date: '. $invoiceInstance["date"] .' '.($invoiceInstance["submitted"] ? 'Submitted' : '📨').'</div>'
			);
			
			echo('<button class="button" type="button"
				onClick="selectionToggle('. ($i - $cacheOffset) .');"
				style="
					z-index: 101;
					width: 25px;
					height: 25px;
					top: '. 105 + (87 * $i).'px;
					left: 395px;
					background-color: rgba(255, 0, 0, 0);
					border: none;
				"></button>'
			);
		}
	}
	$scrollBarTop = 0;
	$scrollBarLeft = 0;
	if (-$cacheOffset < $cacheAmt){
		$scrollBarTop = ($cacheOffset < 0 ? ((-$cacheOffset / $cacheAmt) * 533) : 0);
		$scrollBarLeft = ($cacheOffset < 0 ? ((-$cacheOffset / $cacheAmt) * 5) : 0);
	}else{
		$scrollBarTop = 533;
		$scrollBarLeft = 5;
	}

	echo '<img src="assets/main_ui_elementscroll.png" class="img2" 
		style="
			top: '. $scrollBarTop .'px;
			left: '. $scrollBarLeft .'px;
		"/>';
	echo '<img class="img2" src="assets/invoice_ui_element1.png"/>'; //add invoice ui

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
