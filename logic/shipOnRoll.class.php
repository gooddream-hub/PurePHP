<?php
class ShipOnRoll{
	public $rollCost;

	function setShipRollCost(){
		if ($_SESSION['ship_on_roll']['shipSepHidden'] == 1 && strtoupper($_SESSION['user']['shipco'] == 'US' )) {
			for ($i = 1; $i<=count($_SESSION['ship_on_roll']['selAr']); $i++) {
				$rollCost +=18;
			}
		} else {
			for ($i = 1; $i<=count($_SESSION['ship_on_roll']['selAr']); $i++) {
				if(1 == $i) {
					$rollCost +=18;
				}
				else {
					$rollCost +=6;
				}
			}
		}
		$_SESSION['ship_on_roll']['cost'] = $rollCost;
	}

}