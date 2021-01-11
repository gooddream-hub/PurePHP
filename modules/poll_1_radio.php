<div class="question-title"><?php echo $question['text'];?></div>
<hr>
<div class="question-answer-header row">
	<div class="answer-title">&nbsp;</div>
	<div class="answer-answer">Must Have</div>
	<div class="answer-answer">Like it</div>
	<div class="answer-answer">Don't care</div>
</div>
<?php
$i = 0;
foreach ($question['answers'] as $answer_id => $answer) {
	$style = 'dark';
	if (($i % 2)) {
		$style = '';
	}
?>
<div class="row <?php echo $style;?>">
	<div class="answer-title"><?php echo $answer['text'];?></div>
	<div class="answer-answer"><input type="radio" name="<?php echo $question_id.'_'.$answer_id;?>" id="<?php echo $question_id.'_'.$answer_id.'_0';?>" value="2"></div>
	<div class="answer-answer"><input type="radio" name="<?php echo $question_id.'_'.$answer_id;?>" id="<?php echo $question_id.'_'.$answer_id.'_1';?>" value="1"></div>
	<div class="answer-answer"><input type="radio" name="<?php echo $question_id.'_'.$answer_id;?>" id="<?php echo $question_id.'_'.$answer_id.'_2';?>" value="0"></div>
</div>
<?php
	$i++;
}
?>