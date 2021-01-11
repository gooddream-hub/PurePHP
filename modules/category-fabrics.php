<?php
$types_array = array(
	'Faux-Leather',
	'Patent-Vinyl',
	'PVC',
	'Snakeskin',
	'Stretch-PVC',
);
$categories_array = array(
	'Latex-Sheeting',
);
?>

<?if(in_array($_GET['type'], $types_array)):?>
	<div class="filter-type">
		<div class="elem">
			<select name="stretch">
				<option value="">Stretch type</option>
				<option value="2-way">2-way</option>
				<option value="4-way">4-way</option>
				<option value="no-stretch">none</option>
			</select>
		</div>
		<div class="elem">
			<select name="finish-type">
				<option value="">Finish</option>
				<option value="metallic">Metallic</option>
				<option value="solid">Solid</option>
				<option value="print">Print</option>
			</select>
		</div>
		<div class="elem">
			<select name="upholstery">
				<option value="">Use</option>
				<option value="1">Upholstery</option>
				<option value="0">Fashion</option>
			</select>
		</div>
		<div class="elem">
			<select name="type">
				<option value="">Fabric Type</option>
				<option value="all">All</option>
				<option <?php echo ($_GET['type'] == 'Faux-Leather') ? 'selected="selected"' : ''; ?> value="Faux-Leather">faux leather</option>
				<option <?php echo ($_GET['type'] == 'Patent-Vinyl') ? 'selected="selected"' : ''; ?> value="Patent-Vinyl">patent vinyl</option>
				<option <?php echo ($_GET['type'] == 'PVC') ? 'selected="selected"' : ''; ?> value="PVC">pvc</option>
				<option <?php echo ($_GET['type'] == 'Snakeskin') ? 'selected="selected"' : ''; ?> value="Snakeskin">snakeskin</option>
				<option <?php echo ($_GET['type'] == 'Stretch-PVC') ? 'selected="selected"' : ''; ?> value="Stretch-PVC">stretch pvc</option>
			</select>
		</div>
		<button style="display: none;"  id = "filter-button" class="filter-button elem" onclick="window.location = window.location;">Clear<span> Filters</span></button>
	</div>
	<div class="clearfix"></div>

<?elseif($prods && $prods[0]['cat'] == 'Latex-Sheeting'):?>

	<div class="filter-cat">
		<div class="elem">
			<select name="finish-type">
				<option value="">Finish</option>
				<option value="metallic">Metallic</option>
				<option value="solid">Solid</option>
			</select>
		</div>
		<div class="elem">
			<select name="transparent">
				<option value="">Transparency</option>
				<option value="1">Semi-Transparent</option>
				<option value="0">Solid</option>
			</select>
		</div>
		<div class="elem">
			<select name="price">
				<option value="">Price</option>
				<option value="0-8">0-8</option>
				<option value="9-14">9-14</option>
				<option value="14+">14+</option>
			</select>
		</div>
		<div class="elem">
			<select name="thickness">
				<option value="">Thickness</option>
				<option <?php echo ($_GET['type'] == '.20mm') ? 'selected="selected"' : ''; ?> value=".20mm">.20mm</option>
				<option <?php echo ($_GET['type'] == '.30mm') ? 'selected="selected"' : ''; ?> value=".30mm">.30mm</option>
				<option <?php echo ($_GET['type'] == '.50mm') ? 'selected="selected"' : ''; ?> value=".50mm">.50mm</option>
				<option <?php echo ($_GET['type'] == '.60mm') ? 'selected="selected"' : ''; ?> value=".60mm">.60mm</option>
				<option <?php echo ($_GET['type'] == '.70mm') ? 'selected="selected"' : ''; ?> value=".70mm">.70mm</option>
				<option <?php echo ($_GET['type'] == '.80mm') ? 'selected="selected"' : ''; ?> value=".80mm">.80mm</option>
			</select>
		</div>
		<input type="button" style="display: none;" value="Clear Filters" class="filter-button elem" onclick="window.location = window.location;">
	</div>
	<div class="clearfix"></div>
	
<?elseif($_GET['cat'] == 'Zippers'):?>

	<div class="filter-cat">
		<div class="elem">
			<select name="color">
				<option value="">Color</option>
				<?php
					foreach ($colors as $c) {
						echo '<option value="'.$c.'">'.$c.'</option>';
					}
				?>
			</select>
		</div>
		<div class="elem">
			<select name="teeth_type">
				<option value="">Teeth Type</option>
				<option <?php echo ($_GET['teeth_type'] == 'brass') ? 'selected="selected"' : ''; ?> value="brass">Brass</option>
				<option <?php echo ($_GET['teeth_type'] == 'aluminum') ? 'selected="selected"' : ''; ?> value="aluminum">Aluminum</option>
				<option <?php echo ($_GET['teeth_type'] == 'plastic') ? 'selected="selected"' : ''; ?> value="plastic">Plastic</option>
				<option <?php echo ($_GET['teeth_type'] == 'nylon') ? 'selected="selected"' : ''; ?> value="nylon">Nylon</option>
			</select>
		</div>
		<div class="elem">
			<select name="zipper_style">
				<option value="">Zipper Style</option>
				<option value="hidden">Hidden</option>
				<option value="3-way">3-way</option>
				<option value="standard">Standard</option>
			</select>
		</div>
		<div class="elem">
			<select name="teeth_size">
				<option value="">Teeth Size</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
		</div>
		<div class="elem">
			<select name="separating">
				<option value="">Separating</option>
				<option <?php echo ($_GET['separating'] == 'separating') ? 'selected="selected"' : ''; ?> value="1">separating</option>
				<option <?php echo ($_GET['separating'] == 'non-separating') ? 'selected="selected"' : ''; ?> value="0">non separating</option>
			</select>
		</div>
		<input type="button" style="display: none;" value="Clear Filters" class="filter-button elem" onclick="window.location = window.location;">
	</div>
	<div class="clearfix"></div>
<?endif;?>




