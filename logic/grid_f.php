<?
function getProds()
{
    $cat = mysql_real_escape_string(strip_tags($_GET['cat']));
    if (strcasecmp($cat, "latex-sheeting") == 0) {
        $query = "SELECT * FROM inven_mj WHERE cat = 'Latex-Sheeting' AND active = 1 ORDER BY color asc, type asc";
    } else {
        $query = "SELECT * FROM inven_mj WHERE type = '$cat' AND cat != 'Clearance' AND active = 1 ORDER BY color asc, type asc";
    }
    $result = mysql_query($query);
    setProds($result);
}
function setProds($result)
{
    global $prod, $noprod;
    $noprod = "";
    $row    = mysql_fetch_assoc($result);
    if (!$row) {
        $noprod = "Sorry, there are currently no products in this category.  Please check back at a later date.";
        $prod   = array(
            ''
        );
    } else {
        mysql_data_seek($result, 0);
        while ($row = mysql_fetch_assoc($result)) {
        	$imgArray = json_decode($row['img'], true);

            $safety_type = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $row['type'] ) );



            $p = array(
                "invid"    => $row['invid'],
                "color"    => $row['color'],
                "type"     => $row['type'],
                "price"    => $row['retail'],
                "sale"     => $row['saleprice'],
                "quant"    => $row['invamount'],
                "img"      => $imgArray[0]["path"],
                "img_name" => $imgArray[0]["path"],
                "alt"      => $imgArray[0]["alt"],
                "img2"     => $imgArray[0]["path"],
                "cat"      => $row['cat'],
                "type"     => $row['type'],
                "weight"   => $row['weight'],
                "volume"   => $row['volume'],
                "minLength"=> $row['minlength'],
                "minWidth" => $row['minwidth'],
                "minHeight"=> $row['minheight'],
				'imgs'     => array(),
                'safety_type' => $safety_type
            );
			unset($imgArray[0]);
			if ($imgArray) {
				foreach ($imgArray as $img) {
					$p['imgs'][] = array(
						'path' => $img["path"],
						'alt'  => $img["alt"],
					);
				}
			}
			$prod[] = $p;
        }
    }
}