<?php
class Article{
	
	public $titles;
	public $tutorial;
	public $mysqli;

	function getTutorial(){
		$title = mysqli_real_escape_string($this->mysqli,str_replace("-", " ",$_GET['title']));
		$query = "SELECT * FROM articles WHERE title = '". $title ."'";

		$result = mysqli_query($this->mysqli, $query);
		$this->tutorial = mysqli_fetch_assoc($result);
		

		$file = 'test-export-article.txt';

		$current = json_encode($this->tutorial);



		file_put_contents($file, $current);
	}

	function getTitles($count){
		if($count != "All"){
			$limit = "LIMIT ".$count;
		} else {
			$limit = "";
		}

		$query = "SELECT title,thumb FROM articles ORDER BY date asc ".$limit;
		$result = $this->mysqli->query($query);
		
		 while ($row = $result->fetch_assoc()) {
		        $this->titles[] = $row;
		}	

	}

}