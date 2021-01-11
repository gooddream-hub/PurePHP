<?php
if($_GET['type'] == 'Zippers') {
	if($prods) {
		$color = '';
		foreach($prods as $row) {
			if($color != $row['color']) {
				$color = $row['color'];
				$separating = (!empty($row['seperating']) && $row['seperating'] == 1 ? 'separating' : 'non-separating');
				$style = (!empty($row['3way']) ? '3-way' : '');
				$title = str_replace("-"," ",$row['color'])." ".$separating." ".$row['teeth_type']." ".$style;
				$url = $row['color'].','.str_replace(' ', '-', $row['length']).','.$separating.','.$row['teeth_type'].',Zippers,'.$row['invid'];
?>
				<div class="col <?=$class?>" id="pid-<?=$row['invid']?>" itemscope itemtype="http://schema.org/IndividualProduct">
					<a class="catImg" href="products.<?=$url?>"><img itemprop="image" class="catProd" src="http://mjtrends-672530.c.cdn77.org/images/product/<?=$row['invid']?>/<?=$row['img']?>_370x280.jpg" alt="<?=$row['alt']?>" /></a> 
					<a href="products.<?=$url?>" itemprop="url">
						<h4 itemprop="name"><?=$title?></h4>
					</a>

					<select class="group-param">
						<?foreach($prods as $l):?>
							<?if($l['color'] == $row['color']):?>
								<?php $url = $l['color'].','.str_replace(' ', '-', $l['length']).','.$separating.','.$l['teeth_type'].',Zippers,'.$l['invid']; ?>
								<option data-price="List Price <?=$l['price']?>" data-title="<?=$title?>" data-href="products.<?=$url?>" <?=($row['invid'] == $l['invid']) ? 'selected="selected"' : ''; ?> value="<?=$l['invid']?>"><?php echo $l['length'].' $'.$l['price']; ?></option>
							<?endif;?>
						<?endforeach;?>
					</select>
					
					<?if($row['sale'] != ''):?>
						<img class="imgSale" src="http://mjtrends-672530.c.cdn77.org/images/sale-star.png">
					<?endif;?>
				</div>
<?php
			}
		}
	}
}
?>
