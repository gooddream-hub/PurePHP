<? if($_GET['type'] != 'Busks' && $_GET['type'] != 'kits' && $_GET['type'] != 'Spiral-boning' && $_GET['type'] != 'Zippers' && $_GET['type'] != 'Flat-boning' && $_GET['type'] != 'Polyester-boning-precut'):?>
	<? if($prods):?>
		<?foreach($prods as $row): ?>
			<div class="col <?=$class?>" id="pid-<?=$row['invid']?>" itemscope itemtype="http://schema.org/IndividualProduct">
				<a class="catImg" href="products.<?=$row['color']?>,<?=$row['type']?>,<?=$row['cat']?>"><img itemprop="image" class="catProd" src="http://mjtrends.b-cdn.net/images/product/<?=$row['invid']?>/<?=$row['img']?>_370x280.jpg" alt="<?=$row['alt']?>" /></a>
				<a href="products.<?=$row['color']?>,<?=$row['type']?>,<?=$row['cat']?>" itemprop="url">
					<h4 itemprop="name">
						<?=str_replace("-"," ",$row['color'])." ".str_replace("-"," ",$row['type'])?>
					</h4>
				</a>
				<span>List Price $<?=$row['retail']?></span>
				<?=$row['sale']?>
				
				<?if($row['sale'] != ''):?>
					<img class="imgSale" alt="<?=$row['color']?> <?=$row['type']?> currently on sale" src="http://mjtrends.b-cdn.net/images/sale-star.png">
				<?endif;?>

			</div>
		<? endforeach;?>
	<?endif;?>
<?endif;?>