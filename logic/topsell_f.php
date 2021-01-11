<?
conn();

function topSell(){
global $top;
	$query = "SELECT * FROM inven_mj where cat = 'Fabric' OR cat = 'Latex-Sheeting' ORDER BY RAND() LIMIT 3";
	$result = mysql_query($query); 
	$row = mysql_fetch_assoc($result);
	
	mysql_data_seek($result,0);
	while ($row= mysql_fetch_assoc($result)){
		if($row['saleprice']!=""){
			$sale = "<br><span>Sale Price: </span><span class='red'>$".number_format($row['saleprice'],2,'.','')."</span>";
		}
		$top[] = array("products.".$row['color'].",".$row['type'].",".$row['cat'], $row['img_url'], $row['color']." ".$row['type']." ".$row['cat'], number_format($row['retail'],2,'.',''), $sale);
	}
}
?>
