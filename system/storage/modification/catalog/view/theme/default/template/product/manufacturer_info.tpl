<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($products) { ?>
      <div class="row">
        <div class="col-md-2 col-sm-6 hidden-xs">
          <div class="btn-group btn-group-sm">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="form-group">
            <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link"><?php echo $text_compare; ?></a>
          </div>
        </div>
        <div class="col-md-4 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-sort"><?php echo $text_sort; ?></label>
            <select id="input-sort" class="form-control" onchange="location = this.value;">
              <?php foreach ($sorts as $sorts) { ?>
              <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
              <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-md-3 col-xs-6">
          <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-limit"><?php echo $text_limit; ?></label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
              <?php foreach ($limits as $limits) { ?>
              <?php if ($limits['value'] == $limit) { ?>
              <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">

			<div class="box-label">			
			<?php if ($product['jan']) { ?><div class="label-product label_sale"><span><?php echo $product['jan']; ?></span></div><?php } ?>
			<?php if ($product['isbn']) { ?><div class="label-product label_new"><span><?php echo $product['isbn']; ?></span></div><?php } ?>
			<?php if ($product['mpn']) { ?><div class="label-product label_hit"><span><?php echo $product['mpn']; ?></span></div><?php } ?>		
			</div>			
			
          <div class="product-thumb">

				<div id="<?php echo $product['product_id']; ?>" class="modal fade">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
				<h4 class="modal-title"><?php echo $product['name']; ?></h4>
				</div>
				<div class="modal-body">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
				<div class="images"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>		
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<ul class="list-unstyled"> 
				<?php if ($product['manufacturer']) { ?>
					<li><?php echo $text_manufacturer; ?> <a href="<?php echo $product['manufacturers'] ?>"><?php echo $product['manufacturer']; ?></a></li>
				<?php } ?>
				<li><?php echo $text_model; ?> <?php echo $product['model']; ?></li>
				<?php if ($product['reward']) { ?>
					<li><?php echo $text_reward; ?> <?php echo $product['reward']; ?></li>
				<?php } ?>
				<li><?php echo $product['stock']; ?></li>
				</ul>
				<?php if ($product['price']) { ?>
					<ul class="list-unstyled">
					<?php if (!$product['special']) { ?>
						<li>
						<h2><?php echo $product['price']; ?></h2>
						</li>
						<?php } else { ?>
						<li><span style="text-decoration: line-through;"><?php echo $product['price']; ?></span></li>
						<li>
						<h2><?php echo $product['special']; ?></h2>
						</li>
					<?php } ?>
					<?php if ($product['tax']) { ?>
						<li><?php echo $text_tax; ?> <?php echo $product['tax']; ?></li>
					<?php } ?>
					<?php if ($product['points']) { ?>
						<li><?php echo $text_catpoints; ?> <?php echo $product['points']; ?></li>
					<?php } ?>
					<?php if ($product['discounts']) { ?>
						<?php foreach ($product['discounts'] as $discount) { ?>
							<li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
						<?php } ?>
					<?php } ?>
					</ul>
				<?php } ?>
				</div>
				<div class="row">
				<div class="col-xs-12">
				<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
				<?php if ($product['attribute_groups']) { ?>
					<li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
				<?php } ?>
				</ul>
				<div class="tab-content">
				<div class="tab-pane active" id="tab-description"><?php echo $product['catdescription']; ?></div>
				<?php if ($product['attribute_groups']) { ?>
					<div class="tab-pane" id="tab-specification">
					<table class="table table-bordered">
					<?php foreach ($product['attribute_groups'] as $attribute_group) { ?>
						<thead>
						<tr>
						<td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
							<tr>
							<td><?php echo $attribute['name']; ?></td>
							<td><?php echo $attribute['text']; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					<?php } ?>
					</table>
					</div>
				<?php } ?>
				</div>
				</div>
				</div>
				</div>
				<div class="modal-footer">
				<a href="<?php echo $product['href']; ?>"><button class="btn btn-default" type="button">Подробнее</button></a>
				<button class="btn btn-default" type="button" data-dismiss="modal">Закрыть</button></div>
				</div>
				</div>
				</div>
			

				<button class="btn-modal" type="button" data-toggle="modal" data-target="#<?php echo $product['product_id']; ?>">Показать</button>
			
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p><?php echo $product['description']; ?></p>
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

				<style>
				.btn-modal {
				display: none;
				border: none;
				background-color: #eee;
				color: #888;
				line-height: 25px;
				font-weight: bold;
				text-align: center;
				cursor: pointer;
				position: absolute;
				top: 20%;
				left: 32%;
				padding: 4px 10px;
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
				text-transform: uppercase;
				z-index:99
				}
				.product-thumb:hover .btn-modal {
				display: block;
				}
				.product-list .btn-modal {
				top: 31%;
				left: 13%;
				}
				</style>				
			