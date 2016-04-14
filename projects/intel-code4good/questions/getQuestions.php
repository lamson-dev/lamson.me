<?php

 require_once("../includes/init.php");

 $numQuestions = $_POST["numQuestions"];
 
 $query = "Select * FROM questions ORDER BY RAND() LIMIT " . $numQuestions;

 $result = mysql_query($query);
 
 $output = array();
 while($row = mysql_fetch_assoc($result))
 {
	$output[] = array(
		'question' => $row['question'],
		'answer' => $row['answer'],
		'type' => $row['type'],
		'fake_answers' => $row['fake_answers']
	);
 }

 echo json_encode($output); 

?>