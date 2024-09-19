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

	if (isset($_GET['scrollDirection'])){ //scrolling code
		$scrollDirection = $_GET['scrollDirection'];
		//echo "<br> Scroll position: " . htmlspecialchars($scrollDirection);
		$cacheOffset = htmlspecialchars($scrollDirection);
	}

	if (isset($_GET['invoiceAdd'])){ //runs from some stuff in the javascript in the main html above ^^^, handles adding a new empty invoice
		$invoiceAdd = htmlspecialchars($_GET['invoiceAdd']);
		//weird workaround i came up with
		if ($invoiceAdd == 1){
			$invoices[count($invoices)] = array(
				"to"=>"...",
				"from"=>"...",
				"number"=>000000000,
				"date"=>date("d/m/y"),
				"status"=>false,
				"submitted"=>false
			);
			$cache = $invoices;
			$cacheAmt = count($cache);
			writeInvoiceData("invoice/invoices.txt",$invoices); //saves it to the file for now, so when the page reloads it is fine
			echo('<script type="text/javascript">
				invoiceAddSet(0);
				invoiceEdit('.($cacheAmt).');
			</script>');
		}
	}

	if (isset($_GET['invoiceEdit'])){ //runs from some stuff in the javascript in the main html above ^^^, handles editting invoice
		$invoiceEdit = htmlspecialchars($_GET['invoiceEdit']);
		echo('attempting to edit invoice '.$invoiceEdit);
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

	if ($cacheAmt > count($cache)) return; //just to be safe

	if ($cacheOffset > 0) $cacheOffset = 0; //placeholder workaround
	$cacheAmtVisual = clamp(($cacheAmt + $cacheOffset) - 1,-1,5); //revise later
	$anySelected = false;
	if($cacheAmtVisual > -1){
		for ($i = 0; $i <= $cacheAmtVisual; $i++){ //renders the invoices list ui
			$invoiceInstance = $cache[$i - $cacheOffset];
			if ((selectionGet($i - $cacheOffset) < 0)){
				echo '<img src="assets/main_entry'.$i.('_false.png"').' class="img2"/>';
			}else{
				echo '<img src="assets/main_entry'.$i.('_true.png"').' class="img2" style="filter: hue-rotate(140deg) saturate(3);" />';
				$anySelected = true; //lazy placeholder, it being entirely visual is a bad idea for the future, revise later
			}
			echo('<div id="text"
				style="
					z-index: 100;
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

		if ($anySelected){ //delete button
			echo '<img src="assets/history_ui_element27.png" class="img3" style="top: -165px; left: -140px;"/>';
			echo('<button class="button" type="button"
			onClick="invoiceDelete();"
			style="
				z-index: 101;
				width: 100px;
				height: 70px;
				top: 350px;
				left: 25px;
				background-color: rgba(255, 0, 0, 0);
				border: none;
			"></button>'
			);
		}
	}
	echo '<img src="assets/main_entrybutton'. $cacheAmtVisual + 1 .'.png" class="img2"/>';
	echo('<button class="button" type="button"
		onClick="invoiceAdd();"
		style="
			z-index: 101;
			width: 150px;
			height: 60px;
			top: '. 145 + (87 * $cacheAmtVisual + 1).'px;
			left: 210px;
			background-color: rgba(255, 0, 0, 0);
			border: none;
		"></button>'
	);
	
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
