<? if($_GET['type'] == 'kits'):?>
	<? if($prods):?>
		<?foreach($prods as $row): ?>
			<div class="col <?=$class?>" itemscope itemtype="http://schema.org/IndividualProduct">
				<a class="catImg" href="kit.<?=str_replace(" ", "-", $row['type'])?>"><img itemprop="image" class="catProd" src="http://mjtrends-672530.c.cdn77.org/images/product/<?=$row['img']?>_370x280.jpg" alt="<?=$row['alt']?>" /></a>
				<a href="kit.<?=str_replace(" ", "-", $row['type'])?>" itemprop="url"><h4 itemprop="name"><?=$row['type']?> kit</h4></a>
				<span>List Price $<?=$row['retail']?></span>
				<?=$row['sale']?>
				
				<?if($row['sale'] != ''):?>
					<img class="imgSale" alt="<?=$row['color']?> <?=$row['type']?> currently on sale" src="http://mjtrends-672530.c.cdn77.org/images/sale-star.png">
				<?endif;?>
			</div>
		<?endforeach;?>
	<?endif;?>
<?endif;?>