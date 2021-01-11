<?php 
class Forum{
	public $threads;
	public $pages;
	public $pagination = 10;

	function getThreads($listType, $page = 0){

		$limit = ($page != 0) ? ($page*$pagination) : $this->pagination; 
		
		if($listType == 'recent'){
			$tNumQuery = "SELECT DISTINCT(thread_num) FROM forum ORDER BY thread_num DESC LIMIT $limit,1";
			$query = 'SELECT topic, discussion, thread_num, sub_num, date_time, shipfirst, avatar, points, forum.uid FROM forum LEFT JOIN users ON forum.uid = users.uid WHERE approved = 1 AND thread_num > $tNum ORDER BY thread_num DESC, sub_num DESC';
		} elseif($listType == 'latex'){
			$tNumQuery = "SELECT DISTINCT(thread_num) FROM forum WHERE forum_type = 'latex' ORDER BY thread_num DESC LIMIT $limit,1";
			$query = 'SELECT topic, discussion, thread_num, sub_num, date_time, shipfirst, avatar, points, forum.uid FROM forum LEFT JOIN users ON forum.uid = users.uid WHERE approved = 1 AND forum_type = "latex" ORDER BY thread_num desc, sub_num DESC';
		} elseif($listType == 'vinyl'){
			$tNumQuery = "SELECT DISTINCT(thread_num) FROM forum WHERE forum_type = 'vinyl' ORDER BY thread_num DESC LIMIT $limit,1";
			$query = 'SELECT topic, discussion, thread_num, sub_num, date_time, shipfirst, avatar, points, forum.uid FROM forum LEFT JOIN users ON forum.uid = users.uid WHERE approved = 1 AND forum_type = "vinyl" ORDER BY thread_num desc, sub_num DESC';
		} elseif($listType == 'trending'){
			$tNumQuery = "SELECT DISTINCT(thread_num) FROM forum ORDER BY date_time DESC LIMIT $limit,1";
			$query = 'SELECT topic, discussion, thread_num, sub_num, date_time, shipfirst, avatar, points, forum.uid FROM forum LEFT JOIN users ON forum.uid = users.uid  ORDER BY date_time desc';
		} 

		//get the thread number that we should seek back to
		$mysqli = mysqliConn();
		$result = $mysqli->query($tNumQuery);
		$obj = $result->fetch_object();
		$tNum = $obj->thread_num;
		$result->close();		

		//get the thread data
		eval("\$query = \"$query\";"); //have to use eval becaase we are defining tNum after setting the string.  FYI - must use single quotes on string.
		$result2 = $mysqli->query($query);
		$theadnum = '';
		$subnum = 0;
		while ($row = $result2->fetch_assoc()) {
			if($row['sub_num'] != 0 && $subnum < 2){ // only show 2 subthreads
				$subnum++;
				$this->threads[$row['thread_num']]['sub_thread'][] = $row;
				if($this->threads[$row['thread_num']]['responseCount'] == '') $this->threads[$row['thread_num']]['responseCount'] = $row['sub_num'];
			} else {
				$user = new User;
				$user->setStatus($row['points']);
				$this->threads[$row['thread_num']]['status'] = $user->status;
				$this->threads[$row['thread_num']]['thread'] = $row;
				$subnum = 0;
			}
		}
		$result2->close();
			
		//get the total row count for pagination
		$query = "SELECT COUNT(ID) AS total FROM forum";
	    $result3 = $mysqli->query($query);
		$obj = $result3->fetch_object();
		$this->pages = ceil($obj->total / $this->pagination);
		$result3->close();

		$mysqli->close();
	}
}

/*
function getThreads(){
	$page = $_GET['page'];
	$forum_type = $_GET['forum_type'];
	$limit = 50; 

	if(empty($page)){ 
		$page = 1; 
	} 

	$limitvalue = $page * $limit - ($limit);

	$query_count ="SELECT count(*) from forum WHERE forum_type = '".$forum_type."' AND approved = 1";
	$result_count= mysql_query($query_count); 
	$query ="SELECT topic, thread_num, sub_num, date_time, name from forum WHERE forum_type = '".$forum_type."' AND approved = 1 ORDER BY thread_num desc, sub_num ASC LIMIT $limitvalue, $limit";
	$result = mysql_query($query); 
	$totalrows = mysql_result($result_count,0); 
	
	setThreads($result);
	pagination($page, $limit, $totalrows);
}

function pagination($page, $limit, $totalrows){
	global $pagination;
	$forum_type = $_GET['forum_type'];
	if($page != 1){ 
		$pageprev = $page-1; 
		$pagination .=  "<a href=\"threads-$forum_type-$pageprev.html\">PREV</a>"; 
	} else { 
		$pagination .= "<span>PREV</span>"; 
	} 

	$numofpages = ceil($totalrows / $limit); 

	for($i = 1; $i <= $numofpages; $i++){
		if($i == $page){ 
		$pagination .= $i; 
		} else { 
			$pagination .=  '<a class="pNum" href="threads-'.$forum_type.'-'.$i.'.html">'.$i.'</a>'; 
		} 
	}

	if(($totalrows - ($limit * $page)) >=0){ 
		$pagenext = $page+1; 
		$pagination .= '<a href="threads-'.$forum_type.'-'.$pagenext.'.html">NEXT</a>'; 
	}else{ 
		$pagination .="<span>NEXT</span>"; 
	} 
}

function setThreads($result){
	global $threads;
	$page = $_GET['page'];
	$forum_type = $_GET['forum_type'];
	$rowbegin = ($page-1)*$limit;
	mysql_data_seek($result,0);
	while ($row = mysql_fetch_assoc($result)){
		if ($row['sub_num'] == 0){
			$threads .= '<div class="new"><div class="expand">+</div>';		
		} else {
			$threads .= '<div>';
			for($i = 0; $i < $row['sub_num']; $i++){
				$threads .= '<span class="dash"></span><span class="dash"></span>';
			}
		}
		$threads .= '<a href="read-'.$forum_type.'-'.$row['thread_num'].'-'.$row['sub_num'].'.html">'.$row['topic'].'</a>';
		$threads .= '<span>Posted by: '.$row['name'].' '.$row['date_time'].'</span>';
		$threads .= "</div>";
	}
}
*/
?>  
