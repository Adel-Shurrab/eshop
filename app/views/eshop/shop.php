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
					<h2 class="title text-center">
						<?= htmlspecialchars($data['category_name'], ENT_QUOTES, 'UTF-8') ?>
					</h2>
					<?php renderProduct($data) ?>
					<br style="clear: both;">
					<?= $data['pagination'] ?>
				</div><!--features_items-->
			</div>
		</div>
	</div>
</section>

<style>
	.pagination {
		display: flex;
		justify-content: center;
		padding: 20px 0;
	}
	.pagination li {
		display: inline;
		margin: 0 5px;
	}
	.pagination a {
		color: #FE980F;
		padding: 8px 16px;
		text-decoration: none;
		border: 1px solid #ddd;
		border-radius: 5px;
	}
	.pagination a:hover {
		background-color: #ddd;
	}
	.pagination .active a {
		background-color: #FE980F;
		color: white;
		border: 1px solid #FE980F;
	}
</style>
<?php
renderFooter($data);
?>