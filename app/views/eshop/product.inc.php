<?php if (!empty($data['products']) && is_array($data['products'])): ?>
    <?php foreach ($data['products'] as $row):
        $description = $row['description'];
        $truncatedDescription = strlen($description) > 40 ? substr($description, 0, 29) . '...' : $description;
    ?>
        <!-- Start One Product -->
        <div class="col-sm-4">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <div class="pro-img-box">
                            <img src="<?= htmlspecialchars(UPLOADS_URL . $row['image'], ENT_QUOTES, 'UTF-8') ?>" alt="" />
                        </div>
                        <h2>$<?= htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <a href="<?= BASE_URL ?>productDetails/<?= $row['slag'] ?>">
                            <p title="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($truncatedDescription, ENT_QUOTES, 'UTF-8') ?></p>
                        </a>
                        <button class="btn btn-default add-to-cart" onclick="addCart(<?= $row['id'] ?>, <?= $row['quantity'] ?>)" <?= $row['quantity'] == 0 ? 'disabled' : '' ?>><i class="fa fa-shopping-cart"></i>Add to Cart</button>
                    </div>
                </div>
                <?php if ($row['quantity'] == 0): ?>
                    <div class="out-of-stock">Out of Stock</div>
                <?php endif ?>
                <div class="choose">
                    <ul class="nav nav-pills nav-justified">
                        <li><a href="#"><i class="fa fa-plus-square"></i>Add to Wishlist</a></li>
                        <li><a href="#"><i class="fa fa-plus-square"></i>Add to Compare</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End One Product -->
    <?php endforeach; ?>
<?php endif; ?>

<script>
    function addCart(id, maxQty) {
        if (id == '') {
            alert('Please provide product ID');
            return;
        }

        if (maxQty == 0) {
            alert('Product out ofded stock!');
            return;
        }

        sendData({
            id: id,
            maxQuantity: maxQty,
            dataType: 'add_to_cart'
        });
    }

    function sendData(data = {}, dataType) {
        const ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                handleResult(ajax.responseText, data.dataType);
            }
        });

        ajax.addEventListener('error', function() {
            alert('An error occurred while processing the request.');
        });

        ajax.open("POST", "<?= BASE_URL ?>AjaxCart", true);
        ajax.setRequestHeader('Content-Type', 'application/json');
        ajax.send(JSON.stringify(data));
    }

    function handleResult(result) {
        try {
            if (result !== '') {
                const obj = JSON.parse(result);
                if (obj.message) {
                    alert(obj.message);
                }
            } else {
                console.log('empty response');
            }
        } catch (e) {
            console.error('Error parsing JSON response:', e);
        }
    }
</script>

<style>
    .product-image-wrapper {
        border: 1px solid #f7f7f5;
        overflow: hidden;
        margin-bottom: 30px;
        position: relative;
    }
    .productinfo {
        padding: 10px;
    }
    .productinfo h2 {
        color: #FE980F;
        font-size: 24px;
    }
    .productinfo p {
        height: 40px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .add-to-cart {
        background-color: #FE980F;
        color: #fff;
        border: none;
        margin-top: 10px;
    }
    .add-to-cart:hover {
        background-color: #f7a700;
    }
    .out-of-stock {
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(255, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        font-size: 14px;
    }
</style>