<div class="col-md-12">
    <div class="row">
    <div class="col-md-8 main" id="etsyCat">
    <div class="panel panel-primary">
        <div class="panel-body">
        <form method="post" action="../../controller/marketing/pinterest.php" id="product_pin_form" name="product_pin_form"  enctype="multipart/form-data" class="form-horizontal">
            <div class="form-group">
            <div class="row">
            <label for="title" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" id="title" class="form-control" required=""/>
            </div>
            </div>                        
            </div>
            <div class="form-group">
            <div class="row">
            <label for="img_url" class="col-sm-2 control-label">Image url</label>
            <div class="col-sm-10">
                <input type="url" name="img_url" id="img_url" required="" class="form-control" />
            </div>
            </div>
            </div>
            <div class="form-group">
            <div class="row">
            <label for="title" class="col-sm-2 control-label">Image name</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="title" required="" class="form-control"/>
            </div>
            </div>
            </div>
            <div class="form-group">
            <div class="row">
            <label for="desc" class="col-sm-2 control-label">Image description</label>
            <div class="col-sm-10">
                <textarea name="desc" id="desc" required="" class="form-control" /></textarea>
            </div>
            </div>
            </div>

            <div class="form-group">
            <div class="row">
            <label for="desc" class="col-sm-2 control-label">Product</label>
            <div class="col-sm-10">
                <div class=" form-inline">
                <div class="form-group col-sm-6">
                    <input type="text" name="product_suggest" id="product_suggest" autocomplete="off" class="form-control" style="width:100%;">
                </div>
                
                <div class="form-group col-sm-6">
                    <label for="product_quantity" class="col-sm-6 control-label">Quantity</label>
                    <div class="col-sm-6 input-group">
                    <input type="text" name="product_quantity" id="product_quantity" class="form-control" value="1" style="width:65px;">
                    <button type="button" class="input-group-addon btn add-new" style="padding:9px 14px">Add</button>
                    </div>
                </div>
                
                </div>
            </div>
            </div>
            </div>

            <div class="form-group">
            <div class="row">
            <label for="desc" class="col-sm-2 control-label">&nbsp;</label>
            <div class="col-sm-10">
                <div>
                <table class="table table-condensed">
                    <tr class="col-md-12">
                        <th class="col-md-9">Product</th>
                        <th class="col-md-2">Quantity</th>
                        <th class="col-md-1">Action</th>
                    </tr>
                    <tbody class="product_list"></tbody>
                </table>
                </div>
            </div>
            </div>
            </div>

            <div class="form-group">
            <div class="row">
            <label for="pinterest_board" class="col-sm-2 control-label">Pinterest board</label>
            <div class="col-sm-4">
                <select name="pinterest_board" id="pinterest_board" class="form-control" />
                <?php foreach ($boards as $board) { ?>
                <option value="<?php echo $board->id; ?>"><?php echo $board->name; ?></option>
                <?php } ?>
                </select>
            </div>
            </div>
            </div>
            
            <div class="form-group">
            <div class="row">
            <label for="pinterest_board" class="col-sm-2 control-label">Socials</label>
            <div class="col-sm-4">
                <label><input type="checkbox" name="add_to_facebook" value="1"> Add to Facebook</label><br/>
                <label><input type="checkbox" name="add_to_twitter" value="1"> Add to Twitter</label><br/>
            </div>
            <div class="col-sm-4">
                <label><input type="checkbox" name="add_to_flickr" value="1"> Add to Flickr</label><br/>
                <label><input type="checkbox" name="add_to_googleplus" value="1"> Add to Google Plus</label>
            </div>
            </div>
            </div>
            <hr>
            <div class="form-group">
            <div class="row">
            <label for="post_instantly" class="col-sm-2 control-label">Post instantly</label>
            <label>
                <input type="checkbox" name="post_instantly" value="1">
            </label>
            </div>
            </div>

            <button class="btn btn-success" id="new_submit">Send</button>
        </form>
        </div>
    </div>
    </div>
    <div class="col-md-4 main">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest added PINS</div>
			<div class="panel-body row">
				<?php if($pins){ ?>
				<?php foreach($pins as $pin){ ?>
					<?php
						$parts = pathinfo($pin['name']);
						if(isset($parts['extension'])){
							$thumb_name = $parts['filename'].'_x_150.'.$parts['extension'];
							if (is_numeric($pin['invid'])){
								$invid = $pin['invid'];
							} else {
								$encoded = json_decode($pin['invid'], true);
								$invid = (int)$encoded[0]['product'];
							}
					?>
					<div class="col-md-6 col-sm-6 col-xs-6" style="height:151px;overflow:hidden;">
						<div style="padding:10px 0;">
							<a class="catImg" href="pins.php?name=<?php echo $pin['name']; ?>" target="_blank">
								<img class="img-responsive" src="<?php echo $config['cdn_url_https'] .'/'. ltrim($config['pin']['img_cdn'], '/') . $thumb_name; ?>" alt="<?php echo $pin['desc']; ?>" title="<?php echo $pin['desc']; ?>">
							</a>
						</div>
					</div>
					<?php } ?>
				<?php } ?>
				<?php } else { ?>
				<h4 class="text-center text-warning">No pins found</h4>
				<?php } ?>
			</div>
		</div>
	</div>
    </div>
    
</div>

