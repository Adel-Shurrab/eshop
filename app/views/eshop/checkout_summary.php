<?php
renderHeader($data);
?>

<section id="order_summary">
    <div class="container">
        <div class="breadcrumbs" style="background: #f5f5f5; padding: 10px; border-radius: 5px;">
            <ol class="breadcrumb">
                <li><a href="<?= BASE_URL ?>">Home</a></li>
                <li class="active">Check Out</li>
                <li class="active">Order Summary</li>
            </ol>
        </div>

        <div class="order-details">
            <h2>Order Summary</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['cart'])): ?>
                        <?php foreach ($data['cart'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= (int) $item['cart_qty'] ?></td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td>$<?= number_format($item['price'] * $item['cart_qty'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No items in your cart.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($data['total'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="checkout-actions" style="margin: 30px 0;">
            <div class="row">
                <div class="col-sm-6 text-left">
                    <a href="<?= BASE_URL ?>/checkout" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i>
                        Back to checkout
                    </a>
                </div>
                <div class="col-sm-6 text-right">
                    <form method="post" action="<?= BASE_URL ?>checkout/summary">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i>
                            Confirm Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
renderFooter($data);
?>