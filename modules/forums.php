<?php
include ("../logic/global.php");
conn();

function forums(){
	global $vinyl, $latex;
	$tArr = array('latex', 'vinyl');
	foreach($tArr as $value){
		$query = "SELECT topic, discussion, thread_num, username FROM forum LEFT JOIN users ON forum.uid = users.uid WHERE sub_num = 0 AND forum_type = '$value' ORDER BY date_time DESC LIMIT 0, 2";
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);

		$queryRows = "SELECT ID FROM forum WHERE forum_type = '$value' ";
		$resultRows = mysql_query($queryRows);
		$num_rows = mysql_num_rows($resultRows);

		$$value = array();

		mysql_data_seek($result,0);
		while ($row = mysql_fetch_assoc($result)){
			$discussion = strip_tags($row['discussion']);
			$discussion = splitString($discussion);
			array_push($$value, array('topic' => $row['topic'], 'name' => $row['username'], 'discussion' => $discussion, 'thread_num' => $row['thread_num'], 'num_rows' => $num_rows));
		}
	}
}

function splitString($string){
	(strlen($string) > 170) ? $ellipsis = "..." : $ellipsis = '';
	$string = substr($string,0,170); //take string and limit it to 110 chars
	$string = explode(" ",$string); //break string into array elements, seperated by spaces
	array_pop($string);//pop off last element of array (b/c is it probably a partial word)
	$string = implode(' ',$string);//put array back to string
	$string .= $ellipsis;
	return $string;
}

forums();
// start the output buffer
ob_start();
?>
<div class="forum">
	<div class="container">
		<h3>Share Your Knowledge</h3>
		<div class="wrapper">
			<div class="recent">
				Recent Posts in our Forums:
			</div>
			<? for($i = 0; $i < count($vinyl); $i++):?>
				<div class="<?if($i%2==1) echo "dark"?>">
					<h4><?=$vinyl[$i]['topic']?></h4>
					<p class="from">Posted by: <?=$vinyl[$i]['name']?></p>
					<p class="descr"><?=$vinyl[$i]['discussion']?></p>
					<p class="reply"><a href="http://www.mjtrends.com/forum/thread/<?=$vinyl[$i]['thread_num']?>/<?=str_replace(' ','-',$vinyl[$i]['topic'])?>">Read More & Reply</a></p>
				</div>
			<? endfor;?>
			<? for($i = 0; $i < count($latex); $i++):?>
				<div class="<?if($i%2==1) echo "dark"?>">
					<h4><?=$latex[$i]['topic']?></h4>
					<p class="from">Posted by: <?=$latex[$i]['name']?></p>
					<p class="descr"><?=$latex[$i]['discussion']?></p>
					<p class="reply"><a href="http://www.mjtrends.com/forum/thread/<?=$latex[$i]['thread_num']?>/<?=str_replace(' ','-',$latex[$i]['topic'])?>">Read More & Reply</a></p>
				</div>
			<? endfor;?>
			<div class="links">
				<a href="http://www.mjtrends.com/forum?listType=vinyl">View All Vinyl Posts (<?=$vinyl[0]['num_rows']?>)</a>
				<a href="http://www.mjtrends.com/forum?listType=latex">View All Latex Posts (<?=$latex[0]['num_rows']?>)</a>
			</div>
		</div>
	</div>
</div>

<?php
$cachefile = "../cache/forums.html";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>