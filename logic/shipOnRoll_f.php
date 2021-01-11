<?php

function shipOnRollCalculations(){
	global $shipInfoCost, $addedShipOnRoll, $gtotal, $colspan;
	if (1 == $_SESSION['rws_cart']['shipSepHidden'] &&  'US' == strtoupper($_SESSION['usrNfo']['fedCo'])) {
		for ($i = 1; $i<=count($_SESSION['rws_cart']['selAr']); $i++) {
			$shipInfoCost +=18;
			//$gtotal	+= 18;
			
			$addedShipOnRoll += 18;
		}
	} else {
		for ($i = 1; $i<=count($_SESSION['rws_cart']['selAr']); $i++) {
			if(1 == $i) {
				$shipInfoCost +=18;
				//$gtotal += 18;
				
				$addedShipOnRoll += 18;
			}
			else {
				$shipInfoCost +=6;
				//$gtotal += 6;
				
				$addedShipOnRoll += 6;
			}
		}
	}
	$_SESSION['addedShipOnRoll']	= $addedShipOnRoll;
}

?>