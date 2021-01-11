<?
if ($_GET['pwid'] != 'snarf69vag397mui883') {
    die();
}
//cron job for homepage
include("global.php");
include("RollingCurl.php");
include("RollingCurlGroup.php");
include("../config/config.php");

include("../fedex/fedexTrack.php");
conn();


//delFiles('/home/mjtrends/public_html/cache');
$config->domain = 'https://www.mjtrends.com/';
//prodCache($config->domain);
//catCache($config->domain);
//zipper_cats($config->domain);
kitCache($config->domain);
//runModules();
//filterCache();
//gridSprite($config->domain);
//fedexDelivered();
//uspsDelivered();


//delete all files in cache folder
function delFiles($dir)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($file)) {
                    unlink($dir . "/" . $file);
                }
            }
        }
    }
}

//repopulate cache (loop through dbase, creating include files for all the category and product pages)
function prodCache($domain)
{
    $mysqli = mysqliConn();
    $query = "SELECT invid, type, color, cat FROM inven_mj WHERE active = 1 ORDER BY inven_mj.invid DESC";

    $result = $mysqli->query($query);
    echo "result is: ";
    print_r($result);
    $row = $result->fetch_assoc();

    $result->data_seek(0);

    $rc = new RollingCurl("");
    $rc->window_size = 10;

    while ($row = $result->fetch_assoc()) {
        //$rating = file_get_contents("http://testing.mjtrends.com/products.".$row['color'].",".$row['type'].",fabric");
        $url = $domain . "modules/product.php?cat=" . $row['cat'] . "&type=" . $row['type'] . "&color=" . $row['color'] . "&invid=" . $row['invid'];
        $request = new RollingCurlRequest($url);
        $rc->add($request);
    }

    $rc->execute();

    //create ajax snippets for grouped products
    //include_once("../modules/product-groups.php");
}

function catCache($domain){
    $query = "SELECT cat, type FROM inven_mj where active = 1 GROUP BY type";
    $result = mysql_query($query);

    $rc = new RollingCurl("");
    $rc->window_size = 50;

    while ($row = mysql_fetch_assoc($result)){
        $url = $domain."modules/category.php?cat=".$row['cat']."&type=".$row['type'];
        echo $url;
        $request = new RollingCurlRequest($url);
        $rc->add($request);
    }

    //one off pages
    $url = $domain."modules/category.php?type=sale";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $url = $domain."modules/category.php?type=clearance";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $url = $domain."modules/category.php?type=upholstery";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $url = $domain."modules/category.php?type=kits";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $rc->execute();
}

function zipper_cats($domain)
{
    $query = "SELECT teeth_type FROM inven_traits WHERE teeth_type IS NOT NULL GROUP BY teeth_type";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    $rc = new RollingCurl("");
    $rc->window_size = 50;

    mysql_data_seek($result, 0);
    while ($row = mysql_fetch_assoc($result)) {
        $url = $domain . "modules/category.php?type=Zippers&trait=" . strtolower($row['teeth_type']) . '-separating';
        $request = new RollingCurlRequest($url);
        $rc->add($request);

        $url = $domain . "modules/category.php?type=Zippers&trait=" . strtolower($row['teeth_type']) . '-non-separating';
        echo $url;
        $request = new RollingCurlRequest($url);
        $rc->add($request);
    }

    $url = $domain . "modules/category.php?type=Zippers&trait=hidden";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $url = $domain . "modules/category.php?type=Zippers&trait=3-way";
    $request = new RollingCurlRequest($url);
    $rc->add($request);

    $rc->execute();
}

function kitCache($domain)
{
    $mysqli = mysqliConn();
    $query = "SELECT title FROM kit";
    $result = $mysqli->query($query);

    $rc = new RollingCurl("");
    $rc->window_size = 50;

    while ($row = $result->fetch_assoc()) {
        $title = strtolower(str_replace(" ", "-", $row['title']));
        $url = $domain . 'modules/kit.php?name=' . $title;
        echo $url;

        $request = new RollingCurlRequest($url);
        $rc->add($request);
    }
    $rc->execute();
}

function runModules()
{
    file_get_contents("http://testing.mjtrends.com/modules/forums.php");
    file_get_contents("http://testing.mjtrends.com/modules/header.php");
    file_get_contents("http://testing.mjtrends.com/modules/navigation.php");
    file_get_contents("http://testing.mjtrends.com/modules/sale.php");
    file_get_contents("http://testing.mjtrends.com/modules/new_items.php");
    file_get_contents("http://testing.mjtrends.com/modules/sitemap.php");
    file_get_contents("http://testing.mjtrends.com/modules/tips.php");
    file_get_contents("http://testing.mjtrends.com/modules/top7.php");
    file_get_contents("http://testing.mjtrends.com/modules/email.php");
    file_get_contents("http://testing.mjtrends.com/modules/footer.php");
    file_get_contents("http://testing.mjtrends.com/modules/sitemap_xml.php");
}

function gridSprite($domain)
{
    $query = "SELECT type FROM inven_mj where cat ='Fabric' or cat='Latex-Sheeting' ";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    mysql_data_seek($result, 0);
    while ($row = mysql_fetch_assoc($result)) {
        $gridSprite = file_get_contents($domain."modules/category_sprite.php?type=" . $row['type']);
    }

    //one off
    $gridSprite = file_get_contents($domain."modules/category_sprite.php?type=Latex-Sheeting");
}

function filterCache()
{
    include_once("category_f.php");

    $type_filter = array(
        'stretch' => array(
            '',
            '2-way',
            '4-way',
            'no-stretch',
        ),
        'finish-type' => array(
            '',
            'metallic',
            'solid',
            'print',
        ),
        'upholstery' => array(
            '',
            '1',
            '0'
        ),
        'type' => array(
            '',
            'all',
            'Faux-Leather',
            'Patent-Vinyl',
            'PVC',
            'Snakeskin',
            'Stretch-PVC',
        ),
    );

    $category_filter = array(
        'finish-type' => array(
            '',
            'metallic',
            'solid',
        ),
        'transparent' => array(
            '',
            '1',
            '0',
        ),
        'price' => array(
            '',
            '0-8',
            '9-14',
            '14+',
        ),
        'thickness' => array(
            '',
            '.20mm',
            '.30mm',
            '.50mm',
            '.60mm',
            '.70mm',
            '.80mm',
        ),
    );

    $filter = array();
    foreach ($type_filter['stretch'] as $v) {
        if ($v) {
            $filter['stretch'] = $v;
        } else {
            unset($filter['stretch']);
        }
        foreach ($type_filter['finish-type'] as $v1) {
            if ($v1) {
                $filter['finish-type'] = $v1;
            } else {
                unset($filter['finish-type']);
            }
            foreach ($type_filter['upholstery'] as $v2) {
                if ($v2 != '') {
                    $filter['upholstery'] = $v2;
                } else {
                    unset($filter['upholstery']);
                }
                foreach ($type_filter['type'] as $v3) {
                    if ($v3) {
                        $filter['type'] = $v3;
                    } else {
                        unset($filter['type']);
                    }
                    filter_prods($filter);
                }
            }
        }
    }
    $filter = array();
    foreach ($category_filter['finish-type'] as $v) {
        if ($v) {
            $filter['finish-type'] = $v;
        } else {
            unset($filter['finish-type']);
        }
        foreach ($category_filter['transparent'] as $v1) {
            if ($v1) {
                $filter['transparent'] = $v1;
            } else {
                unset($filter['transparent']);
            }
            foreach ($category_filter['price'] as $v2) {
                if ($v2 != '') {
                    $filter['price'] = $v2;
                } else {
                    unset($filter['price']);
                }
                foreach ($category_filter['thickness'] as $v3) {
                    if ($v3) {
                        $filter['thickness'] = $v3;
                    } else {
                        unset($filter['thickness']);
                    }
                    filter_prods($filter);
                }
            }
        }
    }
}
