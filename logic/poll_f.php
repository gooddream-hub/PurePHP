<?php

function getPoll($id = 0)
{
	$polls = array();
	$query = "SELECT p.id AS poll_id, q.id AS question_id, qa.id AS answer_id, p.name, q.`text`, q.`type`, qa.answer
FROM polls p
INNER JOIN questions q ON q.poll_id = p.id
LEFT JOIN question_answers qa ON qa.question_id = q.id";
	
	if ($id) {
		$query .= ' WHERE p.id = '.$id;
	}

	$result = mysql_query($query); 
	
	while ($row = mysql_fetch_assoc($result)) {
		if (!isset($polls[$row['poll_id']])) {
			$polls[$row['poll_id']] = array(
				'name'      => $row['name'],
				'questions' => array(
					$row['question_id'] => array(
						'text'    => $row['text'],
						'type'    => $row['type'],
						'answers' => array(
							$row['answer_id'] => array(
								'text'    => $row['answer'],
							),
						)
					),
				),
			);
		}
		if (!isset($polls[$row['poll_id']]['questions'][$row['question_id']])) {
			$polls[$row['poll_id']]['questions'][$row['question_id']] =  array(
				'text'    => $row['text'],
				'type'    => $row['type'],
				'answers' => array(
					$row['answer_id'] => array(
						'text'    => $row['answer'],
					),
				)
			);
		}
		if (!isset($polls[$row['poll_id']]['questions'][$row['question_id']]['answers'][$row['answer_id']])) {
			if ($row['answer_id']) {
				$polls[$row['poll_id']]['questions'][$row['question_id']]['answers'][$row['answer_id']] = array(
					'text'    => $row['answer'],
				);
			}
			else {
				$polls[$row['poll_id']]['questions'][$row['question_id']]['answers'][] = array(
					'text'    => $row['answer'],
				);
			}
		}
	}

	return $polls;
}

function getResults($id = 0)
{
	$polls = array();
	$where = '';
	if ($id) {
		$where = ' WHERE pa.poll_id = '.$id;
	}

	$query = "SELECT COUNT(pa.id) as amount, pa.*, p.name, q.`text`, qa.answer
FROM poll_answers pa
INNER JOIN polls p ON p.id = pa.poll_id
INNER JOIN questions q ON q.id = pa.question_id
LEFT JOIN question_answers qa ON qa.id = pa.answer_id
".$where."
GROUP BY pa.answer_id, pa.val, pa.other
ORDER BY poll_id, question_id";
	
	$result = mysql_query($query); 
	
	while ($row = mysql_fetch_assoc($result)) {
		$answer = $row['answer'] ? $row['answer'] : $row['other'];
		if (!isset($polls[$row['poll_id']])) {
			$polls[$row['poll_id']] = array(
				'name'      => $row['name'],
				'questions' => array(
					$row['question_id'] => array(
						'text'    => $row['text'],
						'max'     => $row['amount'],
						'answers' => array()
					),
				),
			);
		}
		if (!isset($polls[$row['poll_id']]['questions'][$row['question_id']])) {
			$polls[$row['poll_id']]['questions'][$row['question_id']] =  array(
				'text'    => $row['text'],
				'max'     => $row['amount'],
				'answers' => array()
			);
		}
		if ($row['answer_id'] > 0) {
			if (!isset($polls[$row['poll_id']]['questions'][$row['question_id']]['answers'][$row['answer_id']])) {
				$polls[$row['poll_id']]['questions'][$row['question_id']]['answers'][$row['answer_id']] = array(
					'text'    => $answer,
					'amount'  => $row['amount'],
				);
			}
		}
		else {
			$polls[$row['poll_id']]['questions'][$row['question_id']]['answers']['other'][] = array(
				'text'    => $answer,
				'amount'  => $row['amount'],
			);
		}
		if ($polls[$row['poll_id']]['questions'][$row['question_id']]['max'] < $row['amount']) {
			$polls[$row['poll_id']]['questions'][$row['question_id']]['max'] = $row['amount'];
		}
	}

	return $polls;
}
