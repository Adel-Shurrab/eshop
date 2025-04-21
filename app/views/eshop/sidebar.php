<div class="col-sm-3">
    <div class="left-sidebar">
        <h2>Categories</h2>
        <div class="panel-group category-products" id="accordian"><!--category-products-->
            <?php if (!empty($data['categories']) && is_array($data['categories'])): ?>
                <?php foreach ($data['categories'] as $category): ?>
                    <?php if ($category['parent'] == 0): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#category-<?= $category['id'] ?>">
                                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                        <?= htmlspecialchars($category['category'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="category-<?= $category['id'] ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul>
                                        <?php foreach ($data['categories'] as $subCategory): ?>
                                            <?php if ($subCategory['parent'] == $category['id']): ?>
                                                <li><a href="<?= BASE_URL . 'shop?category=' . $subCategory['id'] ?>"><?= htmlspecialchars($subCategory['category'], ENT_QUOTES, 'UTF-8') ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div><!--/category-products-->

        <div class="price-range"><!--price-range-->
            <h2>Price Range</h2>
            <div class="well text-center">
                <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="2000" data-slider-step="5" data-slider-value="[250,450]" id="sl2"><br />
                <b class="pull-left">$ 0</b> <b class="pull-right">$ 2000</b>
            </div>
        </div><!--/price-range-->

        <div class="shipping text-center"><!--shipping-->
            <img src="<?= ASSETS . THEME ?>/images/home/shipping.jpg" alt="" />
        </div><!--/shipping-->
    </div>
</div>