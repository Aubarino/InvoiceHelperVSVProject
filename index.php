<!DOCTYPE html> 
<html lang='en'> 
	<head> 
		<link rel="stylesheet" href="index.css">
		<meta name="viewport" content="width=device-width, initial-scale=0.78, user-scalable=no">
		<title>Main Page</title> 
		<style>
			body {
				height: 100%;
				margin: 0;
				overflow: hidden;
				display: flex;
				flex-direction: column;
            	justify-content: flex-end;
				align-items: center;
			}
			.zoom-container {
				height: 100%;
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
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

			function invoiceDelete(deleteIn){ //removes an invoice provided
				searchParams.set('invoiceDelete', 1);
				pageUrl.search = searchParams.toString();
				window.location.href = pageUrl.toString();
			}
			function invoiceAdd(){ //adds an empty new invoice
				searchParams.set('invoiceAdd', 1);

				pageUrl.search = searchParams.toString();
				window.location.href = pageUrl.toString();
			}
			function invoiceAddSet(setTo){ //avoids looping
				searchParams.set('invoiceAdd', setTo);

				pageUrl.search = searchParams.toString();
				window.location.href = pageUrl.toString();
			}
			function invoiceEdit(index){ //opens the invoice adding / editting page, with correct simple data
				searchParams.set('invoiceEdit', index);
				pageUrl.pathname = "/addnewinvoice.php";
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
			<button class="button" type="button"
				onClick=""
				style="
					width: 90px;
					height: 60px;
					top: 517px;
					left: 30px;
					background-color: rgba(255, 255, 255, 0);
					border: none;
				">
			</button>
			<!--clear paid button-->
			<button class="button" type="button"
				onClick=""
				style="
					width: 90px;
					height: 60px;
					top: 440px;
					left: 30px;
					background-color: rgba(255, 255, 255, 0);
					border: none;
				">
			</button>
			<!--question button-->
			<button class="button" type="button"
				onClick=""
				style="
					width: 80px;
					height: 80px;
					top: 595px;
					left: 35px;
					background-color: rgba(255, 255, 255, 0);
					border: none;
				">
			</button>
			<!--search button-->
			<button class="button" type="button"
				onClick=""
				style="
					width: 80px;
					height: 90px;
					top: 35px;
					left: 30px;
					background-color: rgba(255, 255, 255, 0);
					border: none;
				">
			</button>
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
	include "main_functions.php";
?> 
