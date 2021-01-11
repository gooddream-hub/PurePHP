<div class="question-title"><?php echo $question['text'];?></div>
<hr>
<?php
foreach ($question['answers'] as $answer_id => $answer) {
	$progress_max = $question['max']+$question['max']*0.5;
	if ($answer_id == 'other') {
		foreach ($answer as $other_answer) {
			$progress_css_width = $other_answer['amount']/$progress_max*100;
?>
<div class="row results dark">
	<div class="progress-bar-title"><?php echo $other_answer['text'];?></div>
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $other_answer['amount'];?>"	aria-valuemin="0" aria-valuemax="<?php echo $progress_max;?>" style="width:<?php echo $progress_css_width; ?>%">
			<span class="sr-only">&nbsp;</span>
		</div>
	</div>
	<div class="progress-bar-value">(<?php echo $other_answer['amount'];?> votes)</div>
</div>
<?php
		}
	}
	else {
		$progress_css_width = $answer['amount']/$progress_max*100;
?>
<div class="row results dark">
	<div class="progress-bar-title"><?php echo $answer['text'];?></div>
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $answer['amount'];?>"	aria-valuemin="0" aria-valuemax="<?php echo $progress_max;?>" style="width:<?php echo $progress_css_width; ?>%">
			<span class="sr-only">&nbsp;</span>
		</div>
	</div>
	<div class="progress-bar-value">(<?php echo $answer['amount'];?> votes)</div>
</div>
<?php
	}
}
?>