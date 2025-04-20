<?php
renderHeader($data);
?>
<section id="advertisement">
	<div class="container">
		<img src="<?= ASSETS . THEME ?>/images/shop/advertisement.jpg" alt="" />
	</div>
</section>

<section>
	<div class="container">
		<div class="row">
			<?php renderSidebar($data) ?>
			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">Features Items</h2>
					<?php renderProduct($data) ?>
					<br style="clear: both;">
					<ul class="pagination">
						<li class="active"><a href="">1</a></li>
						<li><a href="">2</a></li>
						<li><a href="">3</a></li>
						<li><a href="">&raquo;</a></li>
					</ul>
				</div><!--features_items-->
			</div>
		</div>
	</div>
</section>
<?php
renderFooter($data);
?>