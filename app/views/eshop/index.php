<?php
use App\Models\ProductModel;
renderHeader($data);
?>

<?php displaySessionMessage(); ?>

<section id="slider"><!--slider-->
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div id="slider-carousel" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
						<li data-target="#slider-carousel" data-slide-to="1"></li>
						<li data-target="#slider-carousel" data-slide-to="2"></li>
					</ol>

					<div class="carousel-inner">
						<div class="item active">
							<div class="col-sm-6">
								<h1><span>Uni</span>Mart</h1>
								<h2>Discover Amazing Deals</h2>
								<p>Explore a vast selection of products at unbeatable prices. Shop now and experience the difference.</p>
								<button type="button" class="btn btn-default get">Shop Now</button>
							</div>
							<div class="col-sm-6">
								<img src="<?= ASSETS . THEME ?>/images/home/girl1.jpg" class="girl img-responsive" alt="" />
								<img src="<?= ASSETS . THEME ?>/images/home/pricing.png" class="pricing" alt="" />
							</div>
						</div>
						<div class="item">
							<div class="col-sm-6">
								<h1><span>Uni</span>Mart</h1>
								<h2>Seamless Shopping Experience</h2>
								<p>Enjoy our fast, secure, and user-friendly platform designed for effortless browsing and checkout.</p>
								<button type="button" class="btn btn-default get">Shop Now</button>
							</div>
							<div class="col-sm-6">
								<img src="<?= ASSETS . THEME ?>/images/home/girl2.jpg" class="girl img-responsive" alt="" />
								<img src="<?= ASSETS . THEME ?>/images/home/pricing.png" class="pricing" alt="" />
							</div>
						</div>

						<div class="item">
							<div class="col-sm-6">
								<h1><span>Uni</span>Mart</h1>
								<h2>Your One-Stop Shop</h2>
								<p>Find everything you need in one place. Quality products, exceptional service, and unbeatable value.</p>
								<button type="button" class="btn btn-default get">Shop Now</button>
							</div>
							<div class="col-sm-6">
								<img src="<?= ASSETS . THEME ?>/images/home/girl3.jpg" class="girl img-responsive" alt="" />
								<img src="<?= ASSETS . THEME ?>/images/home/pricing.png" class="pricing" alt="" />
							</div>
						</div>
					</div>

					<a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
						<i class="fa fa-angle-left"></i>
					</a>
					<a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
						<i class="fa fa-angle-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</section><!--/slider-->

<section>
	<div class="container">
		<div class="row">
			<?php renderSidebar($data) ?>

			<div class="col-sm-9 padding-right">
				<div class="features_items"><!--features_items-->
					<h2 class="title text-center">Featured Products</h2>
					<?php renderProduct($data) ?>
				</div><!--features_items-->

				<div class="category-tab"><!--category-tab-->
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<?php foreach ($data['categoriesForProducts'] as $index => $category): ?>
								<li class="<?= $index === 0 ? 'active' : '' ?>"><a href="#category-<?= $category['id'] ?>" data-toggle="tab"><?= htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="tab-content">
						<?php foreach ($data['categoriesForProducts'] as $index => $category): ?>
							<div class="tab-pane fade <?= $index === 0 ? 'active in' : '' ?>" id="category-<?= $category['id'] ?>">
								<?php if (!empty($data['categoryProducts'][$category['id']]) && is_array($data['categoryProducts'][$category['id']])): ?>
									<?php foreach ($data['categoryProducts'][$category['id']] as $row): ?>
										<?php
										$description = $row['description'];
										$truncatedDescription = strlen($description) > 34 ? substr($description, 0, 26) . '...' : $description;
										?>
										<div class="col-sm-3">
											<div class="product-image-wrapper">
												<div class="single-products">
													<div class="productinfo text-center">
														<div class="pro-img-box">
															<img src="<?= htmlspecialchars(UPLOADS_URL . $row['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') ?>" />
														</div>
														<h2>$<?= htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') ?></h2>
														<a href="<?= BASE_URL ?>productDetails/<?= $row['slag'] ?>">
															<p title="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($truncatedDescription, ENT_QUOTES, 'UTF-8') ?></p>
														</a>
														<a href="<?= BASE_URL ?>add_to_cart/<?= $row['id'] ?>" class="btn btn-default add-to-cart" data-product-id="<?= $row['id'] ?>">
															<i class="fa fa-shopping-cart"></i>Add to Cart
														</a>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="col-sm-12 text-center">
										<p>No products available in this category.</p>
									</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div><!--/category-tab-->

				<div class="recommended_items"><!--recommended_items-->
					<h2 class="title text-center">You Might Also Like</h2>

					<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
						<div class="carousel-inner">
							<?php 
							$chunks = array_chunk($data['recommendedProducts'], 3);
							foreach ($chunks as $index => $chunk):
							?>
								<div class="item <?= $index === 0 ? 'active' : '' ?>">
									<?php foreach ($chunk as $product): 
										$description = $product['description'];
										$truncatedDescription = strlen($description) > 34 ? substr($description, 0, 26) . '...' : $description;
									?>
										<div class="col-sm-4">
											<div class="product-image-wrapper">
												<div class="single-products">
													<div class="productinfo text-center">
														<div class="pro-img-box">
															<img src="<?= htmlspecialchars(UPLOADS_URL . $product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') ?>" />
														</div>
														<h2>$<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></h2>
														<a href="<?= BASE_URL ?>productDetails/<?= $product['slag'] ?>">
															<p title="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($truncatedDescription, ENT_QUOTES, 'UTF-8') ?></p>
														</a>
														<a href="<?= BASE_URL ?>add_to_cart/<?= $product['id'] ?>" class="btn btn-default add-to-cart" data-product-id="<?= $product['id'] ?>">
															<i class="fa fa-shopping-cart"></i>Add to Cart
														</a>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
						<a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
							<i class="fa fa-angle-left"></i>
						</a>
						<a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
							<i class="fa fa-angle-right"></i>
						</a>
					</div>
				</div><!--/recommended_items-->
			</div>
		</div>
	</div>
</section>

<?php
renderFooter($data);
?>